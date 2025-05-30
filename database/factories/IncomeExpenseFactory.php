<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\IncomeExpense>
 */
class IncomeExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => 1,
            'amount' => $this->faker->randomFloat(2, 10, 5000),
            'description' => $this->faker->sentence(),
            'type' => $this->faker->randomElement(['income', 'expense']),
            'category' => $this->faker->randomElement(['Salário', 'Aluguel', 'Alimentação', 'Transporte', 'Lazer', 'Outros']),
            'date' => $this->faker->date(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
