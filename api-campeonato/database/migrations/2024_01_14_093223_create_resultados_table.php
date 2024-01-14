<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resultados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fase_id')->constrained('fases');
            $table->foreignId('equipe_a_id')->constrained('times');
            $table->foreignId('equipe_b_id')->constrained('times');
            $table->integer('gols_equipe_a')->default(0);
            $table->integer('gols_equipe_b')->default(0);
            // outros campos, se necessário
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resultados');
    }
}
