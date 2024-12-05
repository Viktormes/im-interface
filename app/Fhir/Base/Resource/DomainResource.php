<?php

namespace App\Fhir\Base\Resource;

use App\Exceptions\InvalidContentException;
use App\Exceptions\NotSupportedException;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirId;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirInstant;
use App\Fhir\Base\Element\Extension;
use App\Fhir\Base\Element\Meta;
use App\Fhir\Base\Element\Narrative;
use App\Fhir\Base\Resource;
use App\Fhir\Cardinality;
use App\Models\FhirProxy;
use ErrorException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;

class DomainResource extends Resource
{
    public bool $justCreated = false;

    public ?string $_id = null;

    public ?string $_versionId = null;

    public ?Carbon $_lastUpdated = null;

    public ?string $_resourceStatus = null;

    public function __construct(?array $data = null)
    {
        parent::__construct();

        if ($data !== null) {
            $this->fill($data);
        }
    }

    public static function __callStatic(string $name, array $arguments)
    {
        return static::query()->{$name}(...$arguments);
    }

    public static function query(): Builder
    {
        $table = (new static)->getTable();

        $query = FhirProxy::query()->from($table);
        $query->getModel()->setTable($table);

        return $query;
    }

    public function getTable()
    {
        if (property_exists($this, 'table')) {
            return $this->table;
        }

        return str(class_basename(static::class))->plural()->snake()->toString();
    }

    public static function all(): Collection
    {
        return static::query()->get();
    }

    public static function queryHistory(): Builder
    {
        $table = (new static)->getTable().'_history';

        $query = FhirProxy::query()->from($table);
        $query->getModel()->setTable($table);

        return $query;
    }

    public static function create(array $attributes = []): static
    {
        $inst = static::make($attributes);
        $inst->save();

        return $inst;
    }

    public static function make(array $attributes = []): static
    {
        $inst = new static;
        $inst->fill($attributes);

        return $inst;
    }

    public function save()
    {
        $this->justCreated = false;

        if (! $this->exists()) {
            $newId = strtolower((string) Str::ulid());

            $this->id = new FhirId($newId);

            if (! $this->meta) {
                $this->meta = new Meta;
            }

            $this->_id = $newId;
            $this->_resourceStatus = 'created';
            $this->_versionId = '1';
            $this->_lastUpdated = now();
            $this->meta->versionId = new FhirId($this->_versionId);
            $this->meta->lastUpdated = new FhirInstant($this->_lastUpdated->toIso8601String());
            $this->justCreated = true;

            return DB::table($this->getTable())->insert([
                'id' => $newId,
                'version_id' => $this->_versionId,
                'last_updated' => $this->_lastUpdated,
                'status' => 'created',
                'resource' => json_encode($this->serializeForSaving()),
            ]);
        }

        $record = DB::table($this->getTable())->where('id', $this->id->value)->first();

        DB::table($this->getTable().'_history')->insert([
            'id' => $this->id->value,
            'version_id' => $record->version_id,
            'last_updated' => $record->last_updated,
            'status' => $record->status,
            'resource' => $record->resource,
        ]);

        $this->_resourceStatus = $this->_resourceStatus === 'deleted' ? 'restored' : 'updated';
        $this->_versionId = ''.($this->_versionId + 1);
        $this->_lastUpdated = now();

        if (! $this->meta) {
            $this->meta = new Meta;
        }
        $this->meta->versionId = new FhirId($this->_versionId);
        $this->meta->lastUpdated = new FhirInstant($this->_lastUpdated->toIso8601String());

        return DB::table($this->getTable())->where('id', $this->id->value)->update([
            'version_id' => $this->_versionId,
            'last_updated' => $this->_lastUpdated,
            'status' => $this->_resourceStatus,
            'resource' => json_encode($this->serializeForSaving()),
        ]);
    }

    public function exists(): bool
    {
        return $this->_id !== null;
    }

    public function update(array $data)
    {
        if (! $this->exists()) {
            throw new ErrorException('Unable to update resource since it does not exist.');
        }

        $id = $this->id;
        $this->reset();
        $this->fill($data);
        $this->id = $id;
        $this->save();
    }

    public static function searchCount(array $params): int
    {
        $query = static::buildQuery($params);

        return $query->count();
    }

    private static function buildQuery(array $params): Builder
    {
        $params = collect(static::parseParams($params) ?? []);

        $searchParameters = static::searchParameters();
        $query = static::query();

        $params->each(function ($p) use ($query, $searchParameters) {
            $name = $p['name'];
            $param = [
                'modifier' => $p['modifier'],
                'value' => $p['value'],
            ];

            if (str_starts_with($name, '_')) {
                return;
            }

            if (! array_key_exists($name, $searchParameters)) {
                throw new NotSupportedException("Invalid search parameter: '{$name}'");
            }

            $sp = $searchParameters[$name];

            if (array_key_exists('query', $sp)) {
                $sp['query']($query, $param['value'], $param['modifier']);

                return;
            }

            switch ($sp['type']) {
                case 'code':
                    $method = array_key_exists('method', $sp) ? $sp['method'] : 'normal';

                    switch ($method) {
                        case 'fuzzy-exact':
                            $operator = $param['modifier'] === 'not' ? 'NOT LIKE' : 'LIKE';
                            $query->where("resource->{$sp['field']}", $operator, "%\"{$param['value']}\"%");
                            break;
                        case 'normal':
                            $operator = $param['modifier'] === 'not' ? '<>' : '=';
                            $query->where("resource->{$sp['field']}", $operator, $param['value']);
                            break;
                        default:
                            dd('unsupported method', $method);
                    }

                    break;
                case 'token':
                    $system = null;
                    $code = $param['value'];

                    if (Str::contains($code, '|')) {
                        [$system,$code] = explode('|', $code);
                    }

                    $method = $param['modifier'] === 'not' ? 'whereNot' : 'where';

                    $query->{$method}(function ($q) use ($sp, $system, $code) {
                        $codeOrValue = $sp['field-type'] === 'identifier' ? 'value' : 'code';

                        if (! empty($system)) {
                            $q->where("resource->{$sp['field']}->system", 'LIKE', "%\"$system\"%");
                        }
                        if (! empty($code)) {
                            $q->where(function ($q2) use ($code, $sp, $codeOrValue) {
                                foreach (explode(',', $code) as $c) {
                                    $q2->orWhere("resource->{$sp['field']}->{$codeOrValue}", 'LIKE', "%\"$c\"%");
                                }
                            });
                        }
                    });
                    break;
                case 'boolean':
                    $operator = $param['modifier'] === 'not' ? '<>' : '=';
                    $query->where("resource->{$sp['field']}", $operator, $param['value'] === 'true' ? 1 : 0);
                    break;
                case 'string':
                    //TODO: Change this since it might not work with SQL Server
                    $v = mb_strtolower($param['value']);
                    $path = '$.'.str_replace('->', '.', $sp['field']);
                    $path = preg_replace('/([a-z]+)/si', '"$1"', $path);
                    $query->whereRaw("LOWER(json_unquote(json_extract(`resource`, '$path'))) LIKE ?", "%$v%");
                    break;
                case 'date':
                    $query->where(function ($q) use ($sp, $param, $name) {
                        if (! preg_match('/^(eq|ne|gt|lt|ge|le|sa|eb|ap)?(\d{4}(?:-\d{2}(?:-\d{2}(?:T\d{2}(?::\d{2}(?::\d{2}.*?)?)?)?)?)?)$/', $param['value'], $matches)) {
                            throw new InvalidContentException("Invalid date '{$param['value']}' provided for parameter '$name'");
                        }

                        $operator = ! empty($matches[1]) ? $matches[1] : 'eq';
                        $value = $matches[2];
                        $from = '';
                        $to = '';
                        $smallestUnit = null;

                        switch (true) {
                            case preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}.*?$/', $value):
                                $from = $to = (new Carbon($value))->format('Y-m-d H:i:s');
                                $smallestUnit = 'second';
                                break;
                            case preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/', $value):
                                $from = (new Carbon($value))->format('Y-m-d H:i:00');
                                $to = (new Carbon($value))->format('Y-m-d H:i:59');
                                $smallestUnit = 'hour';
                                break;
                            case preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}$/', $value):
                                $from = (new Carbon($value))->format('Y-m-d H:00:00');
                                $to = (new Carbon($value))->format('Y-m-d H:59:59');
                                $smallestUnit = 'minute';
                                break;
                            case preg_match('/^\d{4}-\d{2}-\d{2}$/', $value):
                                $from = (new Carbon($value))->format('Y-m-d 00:00:00');
                                $to = (new Carbon($value))->format('Y-m-d 23:59:59');
                                $smallestUnit = 'day';
                                break;
                            case preg_match('/^\d{4}-\d{2}$/', $value):
                                $d = new Carbon($value);
                                $dom = $d->daysInMonth();

                                $from = (new Carbon($value))->format('Y-m-01 00:00:00');
                                $to = (new Carbon($value))->format("Y-m-$dom 23:59:59");
                                $smallestUnit = 'month';
                                break;
                            case preg_match('/^\d{4}$/', $value):
                                $from = "$value-01-01 00:00:00";
                                $to = "$value-12-31 23:59:59";
                                $smallestUnit = 'year';
                                break;
                            default:
                                throw new InvalidContentException("Invalid date '$value' provided for parameter '$name'");
                        }

                        $q->whereNotNull("resource->{$sp['field']}");

                        switch ($operator) {
                            case 'eq':
                                $q->whereDate("resource->{$sp['field']}", '>=', $from)
                                    ->whereDate("resource->{$sp['field']}", '<=', $to);
                                break;
                            case 'ne':
                                $q->whereDate("resource->{$sp['field']}", '<', $from)
                                    ->orWhereDate("resource->{$sp['field']}", '>', $to);
                                break;
                            case 'gt':
                            case 'sa':
                                $q->whereDate("resource->{$sp['field']}", '>', $to);
                                break;
                            case 'lt':
                            case 'eb':
                                $q->whereDate("resource->{$sp['field']}", '<', $from);
                                break;
                            case 'ge':
                                $q->whereDate("resource->{$sp['field']}", '>=', $from);
                                break;
                            case 'le':
                                $q->whereDate("resource->{$sp['field']}", '<=', $to);
                                break;
                            case 'ap':
                                switch ($smallestUnit) {
                                    case 'second':
                                        $from = (new Carbon($from))->subSeconds(30)->format('Y-m-d H:i:s');
                                        $to = (new Carbon($to))->addSeconds(30)->format('Y-m-d H:i:s');
                                        break;
                                    case 'minute':
                                        $from = (new Carbon($from))->subMinutes(30)->format('Y-m-d H:i:s');
                                        $to = (new Carbon($to))->addMinutes(30)->format('Y-m-d H:i:s');
                                        break;
                                    case 'hour':
                                        $from = (new Carbon($from))->subHours(12)->format('Y-m-d H:i:s');
                                        $to = (new Carbon($to))->addHours(12)->format('Y-m-d H:i:s');
                                        break;
                                    case 'day':
                                        $from = (new Carbon($from))->subDays(15)->format('Y-m-d H:i:s');
                                        $to = (new Carbon($to))->addDays(15)->format('Y-m-d H:i:s');
                                        break;
                                    case 'month':
                                        $from = (new Carbon($from))->subMonths(6)->format('Y-m-d H:i:s');
                                        $to = (new Carbon($to))->addMonths(6)->format('Y-m-d H:i:s');
                                        break;
                                    case 'year':
                                        $from = (new Carbon($from))->subYears(2)->format('Y-m-d H:i:s');
                                        $to = (new Carbon($to))->addYears(2)->format('Y-m-d H:i:s');
                                        break;
                                }

                                $q->whereDate("resource->{$sp['field']}", '>=', $from)
                                    ->whereDate("resource->{$sp['field']}", '<=', $to);
                                break;
                        }
                    });
                    break;
                case 'reference':
                    $value = $param['value'];
                    if (empty($value)) {
                        if (! empty($param['modifier'])) {
                            $value = $param['modifier'];
                        } else {
                            throw new InvalidContentException("Invalid reference '{$param['value']}' provided for parameter '$name'");
                        }
                    }

                    if (preg_match('/^(([A-Za-z]+)\/([0-7][0-9A-HJKMNP-TV-Z]{25}))$/i', $value, $match)) {
                        $query->where(function ($q) use ($match, $sp) {
                            $q->where("resource->{$sp['field']}->reference", 'LIKE', "%$match[1]%")
                                ->orWhere("resource->{$sp['field']}->reference", 'LIKE', '%'.url('/api/fhir/'.$match[1]).'%')
                                ->orWhere("resource->{$sp['field']}->reference", 'LIKE', '%'.$match[1].'/_history/1%');
                        });
                    } elseif (preg_match('/\/?(([A-Za-z]+)\/([0-7][0-9A-HJKMNP-TV-Z]{25}))$/i', $value, $match)) {
                        $query->where(function ($q) use ($match, $sp) {
                            $q->where("resource->{$sp['field']}->reference", 'LIKE', "%$match[1]%")
                                ->orWhere("resource->{$sp['field']}->reference", '%'.url('/api/fhir/'.$match[1]).'%');
                        });
                    } elseif (preg_match('/^([0-7][0-9A-HJKMNP-TV-Z]{25})$/i', $value, $match)) {
                        $query->where("resource->{$sp['field']}->reference", 'LIKE', '%/'.$match[1].'%');
                    } else {
                        throw new InvalidContentException("Invalid reference '{$value}' provided for parameter '$name'");
                    }

                    //dd($sp, $param);
                    break;
                default:
                    dd($sp);
            }
        });

        return $query;
    }

    private static function parseParams(array $params): array
    {
        $flattenedParams = [];

        foreach ($params as $key => $value) {
            $modifier = null;
            if (Str::contains($key, ':')) {
                [$key, $modifier] = explode(':', $key);
            }

            $value = is_array($value) ? $value : [$value];

            foreach ($value as $item) {
                $flattenedParams[] = [
                    'name' => $key,
                    'modifier' => $modifier,
                    'value' => $item,
                ];
            }
        }

        return $flattenedParams;
    }

    // TODO: Extract search to separate service and correctly implement all supported params

    public static function searchParameters(): array
    {
        return [];
    }

    // TODO: Extract search to separate service and correctly implement all supported params

    public static function search(array $params)
    {
        /*DB::listen(function ($query) {
            dump($query->sql, $query->bindings);
        });*/

        $query = static::buildQuery($params);

        if (array_key_exists('_sort', $params)) {
            $dir = str_starts_with($params['_sort'], '-') ? 'desc' : 'asc';

            $field = ltrim($params['_sort'], '-');
            switch ($field) {
                case 'lastUpdated':
                    $field = 'last_updated';
                    break;
                default:
                    dd('NOT SUPPORTED!');
                    break;
            }

            $query->orderBy($field, $dir);
        }

        if (array_key_exists('_maxresults', $params)) {
            $query->limit($params['_maxresults']);
        }

        $total = $query->count();

        if (array_key_exists('_maxresults', $params)) {
            $total = min($params['_maxresults'], $total);
        }

        if (array_key_exists('_count', $params)) {
            if (array_key_exists('_maxresults', $params)) {
                $query->limit(min($params['_count'], $params['_maxresults']));
            } else {
                $query->limit($params['_count']);
            }
        }

        $items = $query->get();

        return (object) [
            'total' => $total,
            'items' => $items,
        ];
    }

    public function getRelated(string $related)
    {
        if (array_key_exists($related, $this->attributes)) {
            ddh('in attrs');
        }

        if (array_key_exists($related, $rel = $this->relations())) {
            $reference = class_basename($this).'/'.$this->_id;

            return $rel[$related]['resource']::query()
                ->where("resource->{$rel[$related]['ref']}->reference", 'LIKE', "%$reference%")
                ->get();
        }

        throw new InvalidArgumentException('Invalid relation '.$related);
    }

    public function relations(): array
    {
        return [];
    }

    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'text' => [
                'type' => Narrative::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'contained' => [
                'type' => Resource::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
            'extension' => [
                'type' => Extension::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
            'modifierExtension' => [
                'type' => Extension::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
                'isModifier' => true,
            ],
        ]);
    }

    public function delete()
    {
        if (! $this->exists()) {
            throw new ErrorException('Unable to update resource since it does not exist.');
        }

        $record = DB::table($this->getTable())->where('id', $this->id->value)->first();

        DB::table($this->getTable().'_history')->insert([
            'id' => $this->id->value,
            'version_id' => $record->version_id,
            'last_updated' => $record->last_updated,
            'status' => $record->status,
            'resource' => $record->resource,
        ]);

        $this->reset();

        $this->id = new FhirId($this->_id);
        $this->_resourceStatus = 'deleted';
        $this->_versionId = ''.($this->_versionId + 1);
        $this->_lastUpdated = now();

        return DB::table($this->getTable())->where('id', $this->_id)->update([
            'version_id' => $this->_versionId,
            'last_updated' => $this->_lastUpdated,
            'status' => 'deleted',
            'resource' => json_encode($this->serializeForSaving()),
        ]);
    }
}
