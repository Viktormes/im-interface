<?php

namespace App\Http\Requests;

use App\Rules\ArrayKeysAreDatesRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreHealthDataRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'payload' => ['required', 'array', new ArrayKeysAreDatesRule],
            'payload.*.*.*.dateTime' => ['sometimes', 'date_format:Y-m-d\TH:i:s'],
            'payload.*.*' => ['sometimes', 'array', 'min:1'],
            'payload.*.*.*.min' => ['sometimes', 'integer'],
            'payload.*.*.*.max' => ['sometimes', 'integer'],
            'payload.*.*.*.value' => ['sometimes', 'numeric'],
            'payload.*.*.*.seconds' => ['sometimes', 'integer'],
            'payload.*.*.*.level' => ['sometimes', 'string', 'in:rem,light,deep'],
            'device.name' => ['required', 'string', 'in:Fitbit,Apple'],
            'device.model' => ['required', 'string'],
            'device.serialNumber' => ['required', 'string'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
