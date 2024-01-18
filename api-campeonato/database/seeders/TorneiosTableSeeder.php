<?php

namespace Database\Seeders;
use App\Models\Torneio;

use Illuminate\Database\Seeder;

class TorneiosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Torneio::create([
            'id' => 1,
            'name' => 'Copa',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
