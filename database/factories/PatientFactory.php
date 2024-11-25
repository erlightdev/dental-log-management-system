<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Patient;
use App\Models\User;

class PatientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Patient::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'patient_before_image' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'patient_after_image' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'gender' => $this->faker->randomElement(["male","female","other"]),
            'dob' => $this->faker->word(),
            'blood_type' => $this->faker->randomElement(["A+","A-","B+","B-","AB+","AB-","O+","O-"]),
            'address' => $this->faker->word(),
            'medical_history_current_medications' => $this->faker->word(),
            'treatment_name' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'treatment_price' => $this->faker->word(),
            'service_name' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'service_price' => $this->faker->word(),
            'initial_deposit' => $this->faker->word(),
            'total_appointment_amount_deposits' => $this->faker->word(),
            'total_amount' => $this->faker->word(),
            'registration_date' => $this->faker->date(),
            'notes' => $this->faker->word(),
        ];
    }
}
