<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class TokenRequest extends FormRequest
{
    public function rules()
    {
        return [
            'sessionId' => ['required', 'string'],
            'deviceName' => ['required', 'string'],
        ];
    }

    public function authorize()
    {
        return true;
    }
}
