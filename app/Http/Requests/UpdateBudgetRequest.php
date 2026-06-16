<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBudgetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:categories,id'],
            'month' => ['required', 'string', 'regex:/^\d{4}-\d{2}$/'],
            'limit_amount' => ['required', 'numeric', 'min:0.01'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'La catégorie est obligatoire',
            'month.required' => 'Le mois est obligatoire',
            'month.regex' => 'Le format du mois est invalide (YYYY-MM)',
            'limit_amount.required' => 'Le montant limite est obligatoire',
            'limit_amount.min' => 'Le montant doit être supérieur à 0',
        ];
    }
}
