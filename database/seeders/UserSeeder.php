<?php

namespace Database\Seeders;

use App\Models\UserModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Seed the users' table.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        for ($i = 1; $i <= 10; $i++) {
            UserModel::factory()->create([
                'name' => 'Test User' . $i,
                'email' => 'test' . $i . '@example.com',
                'password' => bcrypt('password123'),
            ]);
        }
    }
}
