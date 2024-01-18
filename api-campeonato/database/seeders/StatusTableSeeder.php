<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Status;

class StatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Status::create([
            'id' => 1,
            'name' => 'Aberto',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Status::create([
            'id' => 2,
            'name' => 'Em Andamento',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Status::create([
            'id' => 3,
            'name' => 'Cancelado',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Status::create([
            'id' => 4,
            'name' => 'Finalizado',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
