<?php

namespace Database\Seeders;

use App\Models\Elkin\ElkinUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        ElkinUser::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
