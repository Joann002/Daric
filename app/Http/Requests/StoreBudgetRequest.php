<?php

namespace App\Http\Requests;

use App\Rules\AccessibleCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBudgetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => [
                'required',
                new AccessibleCategory,
                Rule::unique('budgets')->where(fn ($q) => $q
                    ->where('user_id', auth()->id())
                    ->where('month', $this->input('month'))),
            ],
            'month' => ['required', 'string', 'regex:/^\d{4}-\d{2}$/'],
            'limit_amount' => ['required', 'numeric', 'min:0.01'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'La catégorie est obligatoire',
            'category_id.unique' => 'Un budget existe déjà pour cette catégorie ce mois-ci',
            'month.required' => 'Le mois est obligatoire',
            'month.regex' => 'Le format du mois est invalide (YYYY-MM)',
            'limit_amount.required' => 'Le montant limite est obligatoire',
            'limit_amount.min' => 'Le montant doit être supérieur à 0',
        ];
    }
}
