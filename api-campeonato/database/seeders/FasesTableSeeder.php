<?php

namespace Database\Seeders;

use App\Models\Fase;
use Illuminate\Database\Seeder;

class FasesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Fase::create([
            'id' => 1,
            'name' => 'Quartas de Finais',
            'torneio_id'=>1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Fase::create([
            'id' => 2,
            'name' => 'Semi Finais',
            'torneio_id'=>1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Fase::create([
            'id' => 3,
            'name' => 'Disputa de Teceiro Lugar',
            'torneio_id'=>1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Fase::create([
            'id' => 4,
            'name' => 'Final',
            'torneio_id'=>1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
