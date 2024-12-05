<?php

namespace App\Http\Controllers\Api\App;

use App\Constants\System;
use App\Fhir\Base\Resource\DomainResource\Patient;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\TokenRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function federatedLogin()
    {
        // talk to grandID
        // /json1.1/FederatedLogin?apiKey={apiKey}&authenticateServiceKey={authenticateServiceKey}&gui=false
        sleep(1);
        $sessionId = Str::random(32);
        $grandIdResponse = [
            'sessionId' => "{$sessionId}",
            'autoStartToken' => 'AUTO_START_TOKEN',
        ];

        return response()->json($grandIdResponse);
    }

    public function token(TokenRequest $request)
    {
        $sessionId = $request->get('sessionId');

        // talk to grandID
        // /json1.1/GetSession?apiKey={apiKey}&authenticateServiceKey={authenticateServiceKey}&sessionId={sessionId}
        sleep(1);
        $grandIdResponse = [
            'sessionId' => "{$sessionId}",
            'username' => '191212121212',
            'userAttributes' => [
                'personalNumber' => '191212121212',
                'name' => 'Testor Testsson',
                'givenName' => 'Testor',
                'surname' => 'Testsson',
            ],
        ];

        $formattedPersonNumber = Str::substr($grandIdResponse['username'], 0, 8).'-'.Str::substr($grandIdResponse['username'], 8, 4);

        $patient = Patient::query()
            ->where('resource->identifier[*]->system', 'LIKE', '%"'.System::SwedishPersonalIdentityNumber.'"%')
            ->where('resource->identifier[*]->value', 'LIKE', '%"'.$formattedPersonNumber.'"%')
            ->first();

        // make sure we have an exact match since SQL query can match system and value from different identifiers
        if (! $patient->get('identifier')->whereEquals('system', System::SwedishPersonalIdentityNumber)->whereEquals('value', $formattedPersonNumber)->count()) {
            $patient = null;
        }

        if (is_null($patient)) {
            // Create the patient
        }

        $user = User::where('authable_type', $patient::class)
            ->where('authable_id', $patient->_id)
            ->first();

        if (is_null($user)) {
            // Create connected user
            $user = User::forceCreate([
                'authable_type' => $patient::class,
                'authable_id' => $patient->_id,
            ]);
        }

        [$access_token, $refresh_token] = $this->generateTokenPair($user, $request->deviceName);

        $name = collect($patient->name)->where(fn ($itm) => $itm->use->value === 'official')->first();

        return response()->json([
            'user' => [
                'first_name' => collect($name->given)->map(fn ($n) => $n->value)->join(' '),
                'last_name' => $name->family->value,
                'person_number' => collect($patient->identifier)->where(fn ($itm) => $itm->system->value === System::SwedishPersonalIdentityNumber)->first()->value->value,
                // TODO: Move these to the step where the app is fetching journal data
                'diagnoses' => $patient->getRelated('conditions')->map(fn ($item) => [
                    ...$item->get('code.coding')->whereContains('system', 'icd-10')->first()->only(['system', 'code']),
                    'display' => $item->get('code.text'),
                ]),
                'substances' => [
                    [
                        'code' => 'N02BE01',
                        'system' => 'https://www.who.int/tools/atc-ddd-toolkit/atc-classification',
                        'display' => 'Paracetamol (temp)',
                    ],
                ],
            ],
            'token' => [
                'access_token' => $access_token->plainTextToken,
                'refresh_token' => $refresh_token->plainTextToken,
                'expires_in' => 7200,
            ],
        ]);
    }

    private function generateTokenPair(User $user, string $name): array
    {
        $this->clearTokens($user, $name);

        $access_token = $user->createToken($name, ['access-app-api'], now()->addSeconds(7200));
        $refresh_token = $user->createToken($name, ['issue-access-token'], now()->addDays(30));

        return [$access_token, $refresh_token];
    }

    private function clearTokens(User $user, string $name): void
    {
        $user->tokens()->where('name', $name)->delete();
    }

    public function refreshToken(Request $request)
    {
        $user = $request->user();
        $deviceName = $user->currentAccessToken()->name;

        [$access_token, $refresh_token] = $this->generateTokenPair($user, $deviceName);

        return response()->json([
            'token' => [
                'access_token' => $access_token->plainTextToken,
                'refresh_token' => $refresh_token->plainTextToken,
                'expires_in' => 7200,
            ],
        ]);
    }

    public function user(Request $request)
    {
        if ($request->user()?->authable_type !== Patient::class) {
            return response()->json(['user' => null], 404);
        }

        $patient = Patient::query()
            ->where('id', $request->user()->authable_id)
            ->first();

        if (! $patient) {
            return response()->json(['user' => null], 404);
        }

        $name = collect($patient->name)->where(fn ($itm) => $itm->use->value === 'official')->first();

        return response()->json([
            'user' => [
                'first_name' => collect($name->given)->map(fn ($n) => $n->value)->join(' '),
                'last_name' => $name->family->value,
                'person_number' => collect($patient->identifier)->where(fn ($itm) => $itm->system->value === System::SwedishPersonalIdentityNumber)->first()->value->value,
            ],
        ]);
    }
}
