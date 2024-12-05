<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::preventLazyLoading(! $this->app->isProduction());

        Collection::macro('whereContains', function ($key, $match) {
            return $this->filter(function ($item) use ($key, $match) {
                return Str::contains($item->{$key}->value, $match);
            });
        });

        Collection::macro('whereEquals', function ($key, $match) {
            return $this->filter(function ($item) use ($key, $match) {
                return $item->{$key}->value === $match;
            });
        });

        $this->addBlueprintMacros();
    }

    private function addBlueprintMacros()
    {
        Blueprint::macro('element', function () {
            $this->id();
        });

        Blueprint::macro('resource', function () {
            $this->id();
            $this->string('implicitRules', 255)->nullable();
            $this->string('language')->nullable();
        });

        Blueprint::macro('domainResource', function () {
            $this->resource();
        });

        Blueprint::macro('morphsWithSubType', function (string $name, string $indexName) {
            $this->string("{$name}_type");
            $this->unsignedBigInteger("{$name}_id");
            $this->string("{$name}_sub_type")->nullable();

            $this->index(["{$name}_id", "{$name}_type", "{$name}_sub_type"], $indexName);
        });
    }
}
