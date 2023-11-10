<?php

namespace Admingate\Api\Http\Requests;

use Admingate\Support\Http\Requests\Request;

class VerifyEmailRequest extends Request
{
    public function rules(): array
    {
        return [
            'email' => 'required|email|string',
            'token' => 'required',
        ];
    }
}
