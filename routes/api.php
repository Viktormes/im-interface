<?php

use App\Http\Controllers\Api\App\AuthController as AppAuthController;
use App\Http\Controllers\Api\App\ShareHealthDataController;
use App\Http\Controllers\Api\App\ShareQuestionnaireController;
use App\Http\Controllers\Api\FhirController;
use App\Http\Middleware\EnsureApplicationFhirJsonMiddleware;
use Illuminate\Support\Facades\Route;

Route::prefix('/app')->group(function () {
    Route::post('/federated-login', [AppAuthController::class, 'federatedLogin']);
    Route::post('/token', [AppAuthController::class, 'token']);

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/refresh-token', [AppAuthController::class, 'refreshToken'])->middleware(['abilities:issue-access-token']);

        Route::middleware(['abilities:access-app-api'])->group(function () {
            Route::get('/user', [AppAuthController::class, 'user']);

            Route::post('/share/data', [ShareHealthDataController::class, 'store']);
            Route::post('/share/questionnaire/basdai', [ShareQuestionnaireController::class, 'basdai']);
        });
    });

    /*
    Route::prefix('/fhir')->middleware([
        \App\Http\Middleware\EnsureApplicationFhirJsonMiddleware::class,
    ])->group(function () {
        Route::post('/validate', function (\App\Http\Requests\FhirCreateRequest $request) {
            $options = 0;
            if ($request->query('_pretty') === 'true') {
                $options = JSON_PRETTY_PRINT;
            }

            return response()->json([
                'status' => 'valid',
                'resource' => $request->json()->all(),
            ], options: $options);
        });
    });
    */
});

Route::prefix('/fhir')->middleware([EnsureApplicationFhirJsonMiddleware::class])->group(function () {
    Route::get('/{type}/{id}/_history', [FhirController::class, 'history']);
    Route::get('/{type}/_history', [FhirController::class, 'history']);
    Route::get('/_history', [FhirController::class, 'history']);

    Route::get('/metadata', [FhirController::class, 'capabilities']);

    Route::get('/{type}/{id}', [FhirController::class, 'read']);
    Route::get('/{type}/{id}/_history/{vid}', [FhirController::class, 'vread']);
    Route::put('/{type}/{id}', [FhirController::class, 'update']);
    Route::put('/{type}', [FhirController::class, 'update']);
    Route::patch('/{type}/{id}', [FhirController::class, 'patch']);
    Route::delete('/{type}/{id}', [FhirController::class, 'delete']);
    Route::delete('/{type}', [FhirController::class, 'delete']);
    Route::delete('/', [FhirController::class, 'delete']);

    Route::post('/_search', [FhirController::class, 'search']);
    Route::post('/{type}/_search', [FhirController::class, 'search']);
    Route::post('/{compartment-type}/{compartment-id}/_search', [FhirController::class, 'compartmentSearch']);
    Route::post('/{compartment-type}/{compartment-id}/{type}/_search', [FhirController::class, 'compartmentSearch']);

    Route::get('/', [FhirController::class, 'search']);
    Route::get('/{type}', [FhirController::class, 'search']);
    Route::get('/{compartmentType}/{compartmentId}/*', [FhirController::class, 'compartmentSearch']);
    Route::get('/{compartmentType}/{compartmentId}/{type}', [FhirController::class, 'compartmentSearch']);

    Route::post('/', [FhirController::class, 'batch']);

    Route::post('/{type}', [FhirController::class, 'create']);
});
