<?php

namespace Database\Factories\MySql\Vybe;

use App\Models\MySql\Vybe\Vybe;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class VybeFactory
 *
 * @package Database\Factories\MySql\Vybe
 */
class VybeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Vybe::class;

    /**
     * Order advance random variants
     *
     * @var array
     *
     */
    protected const ORDER_ADVANCES = [
        0 => 7,
        1 => 10,
        2 => 15,
        3 => 20,
        4 => 30,
        5 => 60,
        6 => 90
    ];

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() : array
    {
        return [
            'title'         => $this->faker->word,
            'user_count'    => $this->faker->numberBetween(1, 20),
            'order_advance' => self::ORDER_ADVANCES[rand(0, 6)]
        ];
    }
}
