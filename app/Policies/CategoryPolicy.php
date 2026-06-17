<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy
{
    public function view(User $user, Category $category): bool
    {
        // Global (shared) categories or the user's own.
        return $category->user_id === null || $category->user_id === $user->id;
    }

    public function update(User $user, Category $category): bool
    {
        // Only personal categories can be modified (never the default/global ones).
        return $category->user_id !== null && $category->user_id === $user->id;
    }

    public function delete(User $user, Category $category): bool
    {
        return $category->user_id !== null && $category->user_id === $user->id;
    }
}
