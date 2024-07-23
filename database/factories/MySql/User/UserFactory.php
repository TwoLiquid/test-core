<?php

namespace Database\Factories\MySql\User;

use App\Models\MySql\User\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class UserFactory
 *
 * @package Database\Factories\MySql\User
 */
class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() : array
    {
        return [
            'username'           => str_replace('.', '_', $this->faker->unique()->userName),
            'birth_date'         => $this->faker->date(),
            'description'        => $this->faker->realText(255),
            'verified_celebrity' => $this->faker->boolean,
            'verify_blocked'     => $this->faker->boolean,
            'hide_gender'        => $this->faker->boolean,
            'hide_age'           => $this->faker->boolean,
            'hide_location'      => $this->faker->boolean,
            'verified_partner'   => $this->faker->boolean,
            'signed_up_at'       => Carbon::now()->format('Y-m-d H:i:s')
        ];
    }
}
