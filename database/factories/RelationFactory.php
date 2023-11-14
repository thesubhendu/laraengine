<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Crud;
use App\Models\Relation;

class RelationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Relation::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'type' => $this->faker->word,
            'with' => $this->faker->word,
            'data' => '{}',
            'crud_id' => Crud::factory(),
        ];
    }
}
