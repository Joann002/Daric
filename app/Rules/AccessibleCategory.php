<?php

namespace App\Rules;

use App\Models\Category;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Ensures a category exists and is either global (shared) or owned by the
 * authenticated user. Prevents assigning records to another user's category.
 */
class AccessibleCategory implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $accessible = Category::where('id', $value)
            ->where(function ($query) {
                $query->whereNull('user_id')
                    ->orWhere('user_id', auth()->id());
            })
            ->exists();

        if (! $accessible) {
            $fail('La catégorie sélectionnée est invalide.');
        }
    }
}
