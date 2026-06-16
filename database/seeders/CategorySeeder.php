<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $defaultCategories = [
            // Catégories de revenus
            ['name' => 'Salaire', 'type' => 'income', 'color' => '#10B981', 'icon' => '💰'],
            ['name' => 'Freelance', 'type' => 'income', 'color' => '#3B82F6', 'icon' => '💼'],
            ['name' => 'Investissement', 'type' => 'income', 'color' => '#8B5CF6', 'icon' => '📈'],
            ['name' => 'Cadeau reçu', 'type' => 'income', 'color' => '#EC4899', 'icon' => '🎁'],
            ['name' => 'Autre revenu', 'type' => 'income', 'color' => '#6B7280', 'icon' => '💵'],
            
            // Catégories de dépenses
            ['name' => 'Alimentation', 'type' => 'expense', 'color' => '#EF4444', 'icon' => '🍔'],
            ['name' => 'Transport', 'type' => 'expense', 'color' => '#F59E0B', 'icon' => '🚗'],
            ['name' => 'Logement', 'type' => 'expense', 'color' => '#8B5CF6', 'icon' => '🏠'],
            ['name' => 'Santé', 'type' => 'expense', 'color' => '#EF4444', 'icon' => '⚕️'],
            ['name' => 'Éducation', 'type' => 'expense', 'color' => '#3B82F6', 'icon' => '📚'],
            ['name' => 'Loisirs', 'type' => 'expense', 'color' => '#EC4899', 'icon' => '🎮'],
            ['name' => 'Vêtements', 'type' => 'expense', 'color' => '#8B5CF6', 'icon' => '👕'],
            ['name' => 'Téléphone/Internet', 'type' => 'expense', 'color' => '#06B6D4', 'icon' => '📱'],
            ['name' => 'Abonnements', 'type' => 'expense', 'color' => '#F59E0B', 'icon' => '📺'],
            ['name' => 'Cadeau offert', 'type' => 'expense', 'color' => '#EC4899', 'icon' => '🎁'],
            ['name' => 'Autre dépense', 'type' => 'expense', 'color' => '#6B7280', 'icon' => '💸'],
        ];

        foreach ($defaultCategories as $category) {
            Category::create(array_merge($category, ['user_id' => null]));
        }
    }
}
