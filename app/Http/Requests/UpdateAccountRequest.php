<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:cash,banque,mobile_money'],
            'currency' => ['required', 'string', 'size:3'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom du compte est obligatoire',
            'type.required' => 'Le type de compte est obligatoire',
            'type.in' => 'Le type de compte doit être cash, banque ou mobile_money',
            'currency.required' => 'La devise est obligatoire',
            'currency.size' => 'La devise doit contenir 3 caractères',
        ];
    }
}
