<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
    public function definition()
    {
        return [
            'firstname'      => $this->faker->firstName,
            'lastname'       => $this->faker->lastName,
            'email'          => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password'       => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'plan'           => 1,
            'type'           => 'user',
            'status'         => null,          // subscription/payment status (legacy)
            'account_status' => null,          // moderation status (null = ACTIVE)
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the user is an administrator.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function admin()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'admin',
            ];
        });
    }

    /**
     * Indicate that the user account is suspended.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function suspended()
    {
        return $this->state(function (array $attributes) {
            return [
                'account_status' => 'SUSPENDED',
            ];
        });
    }

    /**
     * Indicate that the user account is banned.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function banned()
    {
        return $this->state(function (array $attributes) {
            return [
                'account_status' => 'BANNED',
            ];
        });
    }

    /**
     * Indicate that the user has two factor authentication enabled.
     *
     * @param string $secret
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withTwoFactor(string $secret = 'K44XG4ZVLNEVG3LM')
    {
        return $this->state(function (array $attributes) use ($secret) {
            return [
                'two_factor_secret' => encrypt($secret),
            ];
        });
    }
}
