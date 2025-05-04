<?php

namespace Database\Seeders;

use App\Models\PlayerModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PlayerModel::create([
            'player_id' => 1,
            'team_id' => 1,
            'first_name' => 'John',
            'middle_name' => 'Doe',
            'nickname' => 'Johnny',
            'last_name' => 'Smith',
            'age' => 25,
            'height' => 6.1,
            'weight' => 180,
            'position' => 'Forward',
            'jersey_number' => 10
        ]);
    }
}
