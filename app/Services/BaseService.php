<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;

class BaseService
{
    public function rules()
    {
        return [];
    }

    /**
     * Get the validation messages that apply to the service.
     *
     * @return array
     */
    public function messages()
    {
        return [];
    }

    public function validate(array $data)
    {
        Validator::make($data, $this->rules(), $this->messages())
            ->validate();
    }
}
