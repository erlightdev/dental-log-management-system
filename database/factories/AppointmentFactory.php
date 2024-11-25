<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\;
use App\Models\Appointment;
use App\Models\Patient;

class AppointmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Appointment::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'patient_id' => Patient::factory(),
            'doctor_id' => ::factory(),
            'staff_id' => $this->faker->word(),
            'appointment_date' => $this->faker->date(),
            'estimated_duration' => $this->faker->word(),
            'status' => $this->faker->randomElement(["scheduled","reschedule","completed","cancelled"]),
            'cancellation_reason' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'appointment_amount_deposits' => $this->faker->word(),
            'payment_status' => $this->faker->randomElement(["paid","unpaid"]),
            'total_appointment_amount_deposits' => $this->faker->word(),
            'notes' => $this->faker->text(),
        ];
    }
}
