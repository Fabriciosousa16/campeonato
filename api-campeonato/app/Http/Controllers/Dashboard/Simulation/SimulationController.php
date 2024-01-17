<?php

namespace App\Http\Controllers\Dashboard\Simulation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Campeonato;
use App\Models\Time;
use App\Models\Resultado;



class SimulationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $simulacao = Campeonato::where('status_id', '!=', 3)->get();

        return response()->json([
            'simulacao' => $simulacao->map(function($resp){
                return [
                    'id' => $resp->id,
                    'name' => $resp->name,
                    'torneio_id' => $resp->torneio_id,
                    'status_id' => $resp->status_id,
                    'created_at' => $resp->created_at->format("Y-m-d H:i:s"),
                    'update_at' => $resp->updated_at->format("Y-m-d H:i:s")
                ];
            })
        ]);
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     public function prizeDraw(Request $request)
{
    // Verificar se há exatamente 8 times cadastrados no campeonato
    $countTimes = Time::where('campeonato_id', 9)->count();

    if ($countTimes !== 8) {
        // Se não houver exatamente 8 times, retornar uma resposta indicando o problema
        return response()->json([
            'status' => 403,
            'message' => 'O sorteio só pode ser realizado se houver exatamente 8 times cadastrados no campeonato.'
        ]);
    }

    // Se houver exatamente 8 times, prosseguir com o sorteio
    $equipes = Time::join('campeonatos', 'times.campeonato_id', '=', 'campeonatos.id')
        ->where('times.campeonato_id', 9)
        ->where('campeonatos.status_id', 1)
        ->get(['times.id']);

    /// Embaralhar as equipes
$equipesEmbaralhadas = $equipes->shuffle()->all();

// Dividir as equipes em pares para as quartas de finais
$paresDeEquipes = array_chunk($equipesEmbaralhadas, 2);

// Inicializar um array para rastrear as equipes que já foram emparelhadas
$equipesEmparelhadas = [];

// Iterar sobre os pares de equipes e criar os confrontos
foreach ($paresDeEquipes as $par) {
    $equipeAId = optional($par[0])['id'];
    $equipeBId = optional($par[1])['id'];

    // Verificar se ambas as equipes não foram emparelhadas antes
    if ($equipeAId && $equipeBId && !in_array($equipeAId, $equipesEmparelhadas) && !in_array($equipeBId, $equipesEmparelhadas)) {
        $resultado = new Resultado([
            'fase_id' => 1,
            'equipe_a_id' => $equipeAId,
            'equipe_b_id' => $equipeBId,
            'gols_equipe_a' => null,
            'gols_equipe_b' => null,
        ]);
        $resultado->save();

        // Adicionar as equipes ao array de equipes emparelhadas
        $equipesEmparelhadas[] = $equipeAId;
        $equipesEmparelhadas[] = $equipeBId;
    }
}

    // Alterar o status do campeonato para 2 na tabela campeonatos
    Campeonato::where('id', 9)->update(['status_id' => 2]);

    // Retornar uma resposta de sucesso com os valores gerados
    return response()->json([
        'status' => 'success',
        'message' => 'Sorteio realizado com sucesso! O status do campeonato foi alterado para 2.',
    ]);
}


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function simulateGame($id)
    
    {
    // Encontrar o resultado pelo ID
    $resultado = Resultado::find($id);

    // Verificar se o resultado foi encontrado
    if (!$resultado) {
        return response()->json([
            'status' => 404,
            'message' => 'Resultado não encontrado.',
        ], 404);
    }

    // Verificar se o jogo já foi simulado
    if ($resultado->gols_equipe_a !== null && $resultado->gols_equipe_b !== null) {
        return response()->json([
            'status' => 400,
            'message' => 'Este jogo já foi simulado anteriormente.',
        ], 400);
    }

    // Simular o jogo (gerar gols aleatórios, por exemplo)
    $golsEquipeA = random_int(0, 5); // Simulação de gols para a equipe A
    $golsEquipeB = random_int(0, 5); // Simulação de gols para a equipe B

    // Atualizar o resultado com os gols simulados
    $resultado->update([
        'gols_equipe_a' => $golsEquipeA,
        'gols_equipe_b' => $golsEquipeB,
    ]);

    return response()->json([
        'status' => 200,
        'message' => 'Simulação realizada com sucesso.',
        'gols_equipe_a' => $golsEquipeA,
        'gols_equipe_b' => $golsEquipeB,
    ]);

    
}


// public function generateSemifinals(Request $request)
// {
//     // Verificar se a fase anterior (quartas de finais) foi concluída
//     // $quartasFinaisConcluidas = Resultado::where('fase_id', 1)->whereHas('campeonato', function ($query) {
//     //     $query->where('id', 9); // Filtrar pelo campeonato_id desejado
//     // })->count() == 4;

//         // Verificar se a fase anterior (quartas de finais) foi concluída
//         $quartasFinaisConcluidas = DB::table('resultados')
//         ->join('campeonatos', 'resultados.campeonato_id', '=', 'campeonatos.id')
//         ->where('resultados.fase_id', 1)
//         ->where('campeonatos.id', 9)
//         ->count() == 4;

//     if (!$quartasFinaisConcluidas) {
//         return response()->json([
//             'status' => 403,
//             'message' => 'A fase anterior (quartas de finais) não foi concluída ainda.'
//         ]);
//     }

//     // Obter os vencedores das quartas de finais
//     $vencedoresQuartasFinais = DB::table('resultados')
//         ->join('campeonatos', 'resultados.campeonato_id', '=', 'campeonatos.id')
//         ->where('resultados.fase_id', 1)
//         ->where('campeonatos.id', 9)
//         ->selectRaw('CASE WHEN gols_equipe_a > gols_equipe_b THEN equipe_a_id ELSE equipe_b_id END AS equipe_vencedora_id')
//         ->pluck('equipe_vencedora_id');

//     // Ordenar os vencedores com base no saldo de gols
//     $vencedoresOrdenados = $this->sortWinnersByGoalDifference($vencedoresQuartasFinais);

//     // Dividir os vencedores em pares para as semifinais
//     $semifinais = collect($vencedoresOrdenados)->chunk(2);

//     // Inicializar um array para rastrear as equipes que já foram emparelhadas nas semifinais
//     $equipesEmparelhadas = [];

//     // Inserir os novos jogos das semifinais
//     foreach ($semifinais as $confronto) {
//         $equipeAId = optional($confronto->get(0))['id'];
//         $equipeBId = optional($confronto->get(1))['id'];

//         // Verificar se ambas as equipes não foram emparelhadas antes
//         if ($equipeAId && $equipeBId && !in_array($equipeAId, $equipesEmparelhadas) && !in_array($equipeBId, $equipesEmparelhadas)) {
//             $resultado = new Resultado([
//                 'fase_id' => 2,
//                 'equipe_a_id' => $equipeAId,
//                 'equipe_b_id' => $equipeBId,
//                 'campeonato_id' => 9
//             ]);
//             $resultado->save();
// ;
//             // Adicionar as equipes ao array de equipes emparelhadas nas semifinais
//             $equipesEmparelhadas[] = $equipeAId;
//             $equipesEmparelhadas[] = $equipeBId;
//         }
//     }

//     return response()->json([
//         'status' => 200,
//         'message' => 'Semifinais geradas com sucesso!',
//     ]);
// }

public function generateSemifinals(Request $request)
{
    $campeonatoId = 9; // Defina o campeonato em disputa

    // Verificar se a fase anterior (quartas de finais) foi concluída
    $quartasFinaisConcluidas = Resultado::where('fase_id', 1)
        ->where('campeonato_id', $campeonatoId)
        ->count() == 4;

    if (!$quartasFinaisConcluidas) {
        return response()->json([
            'status' => 403,
            'message' => 'A fase anterior (quartas de finais) não foi concluída ainda para o campeonato ' . $campeonatoId . '.',
        ]);
    }

    // Obter os resultados das quartas de finais
$resultadosQuartasFinais = Resultado::where('fase_id', 1)
->where('campeonato_id', $campeonatoId)
->get(['equipe_a_id', 'equipe_b_id', 'gols_equipe_a', 'gols_equipe_b']);

// Inicializar um array para rastrear as equipes vencedoras
$vencedoresQuartasFinais = [];

// Determinar as equipes vencedoras
foreach ($resultadosQuartasFinais as $resultado) {
if ($resultado->gols_equipe_a > $resultado->gols_equipe_b) {
    $vencedoresQuartasFinais[] = $resultado->equipe_a_id;
} elseif ($resultado->gols_equipe_a < $resultado->gols_equipe_b) {
    $vencedoresQuartasFinais[] = $resultado->equipe_b_id;
} else {
    // Em caso de empate, você pode adotar alguma lógica específica, como sorteio ou critérios de desempate
    // Por enquanto, deixaremos como está (nenhuma equipe vencedora)
}
}

// Verificar se há equipes suficientes para gerar as semifinais
if (count($vencedoresQuartasFinais) >= 2) {
// Dividir os vencedores em pares para as semifinais
$semifinais = array_chunk($vencedoresQuartasFinais, 2);

// Inserir os novos jogos das semifinais
foreach ($semifinais as $confronto) {
    $resultado = new Resultado([
        'fase_id' => 2,
        'campeonato_id' => $campeonatoId,
        'equipe_a_id' => $confronto[0],
        'equipe_b_id' => $confronto[1],
        'gols_equipe_a' => null,
        'gols_equipe_b' => null,
    ]);
    $resultado->save();
}

return response()->json([
    'status' => 200,
    'message' => 'Semifinais geradas com sucesso para o campeonato ' . $campeonatoId . '.',
    'semifinais' => $semifinais,
]);
} else {
return response()->json([
    'status' => 403,
    'message' => 'Não há equipes suficientes para gerar as semifinais para o campeonato ' . $campeonatoId . '.',
]);
}

}

public function generateThirdPlaceMatch(Request $request)
{
    $campeonatoId = 9; // Defina o campeonato em disputa

    // Obter as equipes que perderam nas semifinais
    $perdedoresSemifinais = Resultado::where('fase_id', 2)
        ->where('campeonato_id', $campeonatoId)
        ->get(['equipe_a_id', 'equipe_b_id', 'gols_equipe_a', 'gols_equipe_b'])
        ->map(function ($resultado) {
            return $resultado->gols_equipe_a > $resultado->gols_equipe_b
                ? $resultado->equipe_b_id
                : $resultado->equipe_a_id;
        });

    // Verificar se há equipes suficientes para gerar a disputa de terceiro lugar
    if ($perdedoresSemifinais->count() >= 2) {
        // Inserir o jogo da disputa de terceiro lugar
        $resultado = new Resultado([
            'fase_id' => 3,
            'campeonato_id' => $campeonatoId,
            'equipe_a_id' => $perdedoresSemifinais[0],
            'equipe_b_id' => $perdedoresSemifinais[1],
        ]);
        $resultado->save();

        return response()->json([
            'status' => 200,
            'message' => 'Disputa de terceiro lugar gerada com sucesso para o campeonato ' . $campeonatoId . '.',
            'terceiro_lugar' => $perdedoresSemifinais,
        ]);
    } else {
        return response()->json([
            'status' => 403,
            'message' => 'Não há equipes suficientes para gerar a disputa de terceiro lugar para o campeonato ' . $campeonatoId . '.',
        ]);
    }
}


public function generateFinalMatch(Request $request)
{
    $campeonatoId = 9; // Defina o campeonato em disputa

    // Obter as equipes que venceram nas semifinais
    $vencedoresSemifinais = Resultado::where('fase_id', 2)
        ->where('campeonato_id', $campeonatoId)
        ->get(['equipe_a_id', 'equipe_b_id', 'gols_equipe_a', 'gols_equipe_b'])
        ->map(function ($resultado) {
            return $resultado->gols_equipe_a > $resultado->gols_equipe_b
                ? $resultado->equipe_a_id
                : $resultado->equipe_b_id;
        });

    // Verificar se há equipes suficientes para gerar a final
    if ($vencedoresSemifinais->count() >= 2) {
        // Inserir o jogo da final
        $resultado = new Resultado([
            'fase_id' => 4,
            'campeonato_id' => $campeonatoId,
            'equipe_a_id' => $vencedoresSemifinais[0],
            'equipe_b_id' => $vencedoresSemifinais[1],
            'gols_equipe_a' => null,
            'gols_equipe_b' => null,
        ]);
        $resultado->save();

        return response()->json([
            'status' => 200,
            'message' => 'Final gerada com sucesso para o campeonato ' . $campeonatoId . '.',
            'final' => $vencedoresSemifinais,
        ]);
    } else {
        return response()->json([
            'status' => 403,
            'message' => 'Não há equipes suficientes para gerar a final para o campeonato ' . $campeonatoId . '.',
        ]);
    }
}



}
