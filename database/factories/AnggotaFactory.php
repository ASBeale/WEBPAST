<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Anggota>
 */
class AnggotaFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->name(),
            'DoB' => fake()->date(),
            'TelpNo' => fake()->phoneNumber(),
            'ortu_nama' => fake()->name(),
            'ortu_telp_no' => fake()->phoneNumber(),
            'tanggal_bergabung' => fake()->date(),
            'jabatan_id' => \App\Models\Jabatan::inRandomOrder()->first()->id,
            'password' => static::$password ??= Hash::make('password'),
        ];
    }
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
