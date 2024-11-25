<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Staff;
use App\Models\User;

class StaffFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Staff::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'job_title' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'department' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'bio' => $this->faker->text(),
        ];
    }
}
