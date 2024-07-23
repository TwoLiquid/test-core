<?php

namespace Database\Factories\MySql\Order;

use App\Models\MySql\Order\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class OrderFactory
 *
 * @package Database\Factories\MySql\Order
 */
class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() : array
    {
        return [
            'guid'     => $this->faker->text(20),
            'payed_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
