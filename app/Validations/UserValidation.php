<?php

namespace App\Validations;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Utils\Messages\ValidateMessage;

class UserValidation
{
    public function createUser($request): void
    {
        $messages = [
            'name.required' => ValidateMessage::DEFAULTREQUIRED,
            'email.required' => ValidateMessage::UNIQUE,
        ];

        $validateConfig = Validator::make(
            (array)$request->all(),
            [
                'name' => 'required',
                'email' => 'required'
            ],
            $messages
        )->validate();
    }

    public function updateUser($request): void
    {
        $messages = [
            'name.required' => ValidateMessage::DEFAULTREQUIRED,
            'email.required' => ValidateMessage::UNIQUE,
        ];

        $validateConfig = Validator::make(
            (array)$request->all(),
            [
                'name' => 'required',
                'email' => 'required'
            ],
            $messages
        )->validate();
    }
}
