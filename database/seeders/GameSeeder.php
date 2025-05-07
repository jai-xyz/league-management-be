<?php

namespace Database\Seeders;

use App\Models\GameModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GameModel::factory()
            ->count(10)
            ->create();
    }
}
