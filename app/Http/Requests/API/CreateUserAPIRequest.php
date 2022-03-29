<?php

namespace App\Http\Requests\API;

use App\Models\User;
use InfyOm\Generator\Request\APIRequest;

class CreateUserAPIRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return User::$rules;
    }

    /**
     * Get custom validate messages
     *
     * @return array
     */
    public function messages()
    {
        return [
            'password.regex' => 'Password must contains at least an uppercase letter, a lowercase letter and one special character'
        ];
    }
}
