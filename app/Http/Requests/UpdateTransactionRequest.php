<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'account_id' => ['required', 'exists:accounts,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'type' => ['required', 'in:income,expense'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'date' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'account_id.required' => 'Le compte est obligatoire',
            'account_id.exists' => 'Le compte sélectionné n\'existe pas',
            'category_id.required' => 'La catégorie est obligatoire',
            'category_id.exists' => 'La catégorie sélectionnée n\'existe pas',
            'type.required' => 'Le type de transaction est obligatoire',
            'type.in' => 'Le type doit être revenu ou dépense',
            'amount.required' => 'Le montant est obligatoire',
            'amount.numeric' => 'Le montant doit être un nombre',
            'amount.min' => 'Le montant doit être supérieur à 0',
            'date.required' => 'La date est obligatoire',
            'date.date' => 'La date doit être une date valide',
        ];
    }
}
