<?php

namespace App\Http\Controllers\Dashboard\Historry;

use App\Http\Controllers\Controller;
use App\Models\Campeonato;
use App\Models\Status;
use App\Models\Torneio;
use App\Models\Penalty;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(){
        
        $simulacao = Campeonato::where('status_id', 4)->get();

        return response()->json([
            'history' => $simulacao->map(function ($resp) {
                $status = Status::find($resp->status_id);
                $torneio = Torneio::find($resp->torneio_id);

                return [
                    'id' => $resp->id,
                    'name' => $resp->name,
                    'torneio_id' => $torneio ? $torneio->name : null, // Nome do torneio
                    'status' => $status ? $status->name : null, // Nome do status
                    'created_at' => $resp->created_at->format("d/m/Y H:i:s"),
                    'updated_at' => $resp->updated_at->format("d/m/Y H:i:s")
                ];
            })
        ]);
    }


    public function listHistoryForChampionship($id)
{
    try {

        // Fase 3 - Disputa de 3º Lugar
        $subqueryFase3A = DB::table('times as t')
            ->select(
                't.id as time_id',
                't.name as time_name',
                'c.name as campeonato_name',
                'c.created_at as data_campeonato',
                'r.fase_id',
                'r.gols_equipe_a as gols_equipe',
                'p.gols_equipe_a as gols_penaltys'
            )
            ->join('campeonatos as c', 't.campeonato_id', '=', 'c.id')
            ->join('resultados as r', function ($join) {
                $join->on('t.id', '=', 'r.equipe_a_id')
                    ->where('r.fase_id', 3);
            })
            ->leftJoin('penaltys as p', 'r.id', '=', 'p.resultado_id')
            ->where('c.id', $id);
        
        $subqueryFase3B = DB::table('times as t')
            ->select(
                't.id as time_id',
                't.name as time_name',
                'c.name as campeonato_name',
                'c.created_at as data_campeonato',
                'r.fase_id',
                'r.gols_equipe_b as gols_equipe',
                'p.gols_equipe_b as gols_penaltys'
            )
            ->join('campeonatos as c', 't.campeonato_id', '=', 'c.id')
            ->join('resultados as r', function ($join) {
                $join->on('t.id', '=', 'r.equipe_b_id')
                    ->where('r.fase_id', 3);
            })
            ->leftJoin('penaltys as p', 'r.id', '=', 'p.resultado_id')
            ->where('c.id', $id);
        
        // Fase 4 - Final
        $subqueryFase4A = DB::table('times as t')
            ->select(
                't.id as time_id',
                't.name as time_name',
                'c.name as campeonato_name',
                'c.created_at as data_campeonato',
                'r.fase_id',
                'r.gols_equipe_a as gols_equipe',
                'p.gols_equipe_a as gols_penaltys'
            )
            ->join('campeonatos as c', 't.campeonato_id', '=', 'c.id')
            ->join('resultados as r', function ($join) {
                $join->on('t.id', '=', 'r.equipe_a_id')
                    ->where('r.fase_id', 4);
            })
            ->leftJoin('penaltys as p', 'r.id', '=', 'p.resultado_id')
            ->where('c.id', $id);
        
        $subqueryFase4B = DB::table('times as t')
            ->select(
                't.id as time_id',
                't.name as time_name',
                'c.name as campeonato_name',
                'c.created_at as data_campeonato',
                'r.fase_id',
                'r.gols_equipe_b as gols_equipe',
                'p.gols_equipe_b as gols_penaltys'
            )
            ->join('campeonatos as c', 't.campeonato_id', '=', 'c.id')
            ->join('resultados as r', function ($join) {
                $join->on('t.id', '=', 'r.equipe_b_id')
                    ->where('r.fase_id', 4);
            })
            ->leftJoin('penaltys as p', 'r.id', '=', 'p.resultado_id')
            ->where('c.id', $id);
        
        // Unir os resultados
        $resultados = $subqueryFase3A
            ->unionAll($subqueryFase3B)
            ->unionAll($subqueryFase4A)
            ->unionAll($subqueryFase4B)
            ->orderBy('campeonato_name')
            ->orderByDesc('fase_id')
            ->orderByDesc('gols_equipe')
            ->orderByDesc('gols_penaltys')
            ->get();
        
        return response()->json([
            'result' => $resultados->map(function ($resp) {
                return [
                    'time_id' => $resp->time_id,
                    'time_name' => $resp->time_name,
                    'campeonato_name' => $resp->campeonato_name,
                    'fase_id' => $resp->fase_id,
                    'gols_equipe' => $resp->gols_equipe,
                    'gols_penaltys' => $resp->gols_penaltys,
                    'data_campeonato' => date("d/m/Y H:i:s", strtotime($resp->data_campeonato)),
                ];
            }),
        ]);
        

    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Ocorreu um erro ao buscar os dados: ' . $e->getMessage(),
        ], 500);
    }
}


  // $historys = DB::table('resultados as r')
            //         ->select(
            //             't.name as time_name',
            //             'c.name as campeonato_name',
            //             'c.created_at as data_campeonato',
            //             DB::raw("'Fase 4' as fase_name"),
            //             'r.gols_equipe_a',
            //             'r.gols_equipe_b'
            //         )
            //         ->join('times as t', 'r.equipe_a_id', '=', 't.id')
            //         ->join('campeonatos as c', 't.campeonato_id', '=', 'c.id')
            //         ->where('r.fase_id', 4)
            //         ->where('c.id', $id)
                
            //         ->unionAll(DB::table('resultados as r')
            //             ->select(
            //                 't.name as time_name',
            //                 'c.name as campeonato_name',
            //                 'c.created_at as data_campeonato',
            //                 DB::raw("'Fase 4' as fase_name"),
            //                 'r.gols_equipe_b',
            //                 'r.gols_equipe_a'
            //             )
            //             ->join('times as t', 'r.equipe_b_id', '=', 't.id')
            //             ->join('campeonatos as c', 't.campeonato_id', '=', 'c.id')
            //             ->where('r.fase_id', 4)
            //             ->where('c.id', $id)
            //         )
                
            //         ->unionAll(DB::table('resultados as r')
            //             ->select(
            //                 't.name as time_name',
            //                 'c.name as campeonato_name',
            //                 'c.created_at as data_campeonato',
            //                 DB::raw("'Fase 3' as fase_name"),
            //                 'r.gols_equipe_a',
            //                 'r.gols_equipe_b'
            //             )
            //             ->join('times as t', 'r.equipe_a_id', '=', 't.id')
            //             ->join('campeonatos as c', 't.campeonato_id', '=', 'c.id')
            //             ->where('r.fase_id', 3)
            //             ->where('c.id',  $id)
            //         )
                
            //         ->unionAll(DB::table('resultados as r')
            //             ->select(
            //                 't.name as time_name',
            //                 'c.name as campeonato_name',
            //                 'c.created_at as data_campeonato',
            //                 DB::raw("'Fase 3' as fase_name"),
            //                 'r.gols_equipe_b',
            //                 'r.gols_equipe_a'
            //             )
            //             ->join('times as t', 'r.equipe_b_id', '=', 't.id')
            //             ->join('campeonatos as c', 't.campeonato_id', '=', 'c.id')
            //             ->where('r.fase_id', 3)
            //             ->where('c.id',  $id)
                        
            //         )
            //         ->orderByDesc('fase_name')
            //         ->orderByDesc('gols_equipe_a')
            //         ->orderBy('gols_equipe_b')
            //         ->get();
                
            //         return response()->json([
            //             'result' => $historys->map(function ($resp) {
            //                 return [
            //                     'time_name' => $resp->time_name,
            //                     'campeonato_name' => $resp->campeonato_name,
            //                     'fase_name' => $resp->fase_name,
            //                     'gols_equipe_a' => $resp->gols_equipe_a,
            //                     'gols_equipe_b' => $resp->gols_equipe_b,
            //                     // 'data_campeonato'=>$resp->data_campeonato->format("Y-m-d H:i:s"),
            //                     'data_campeonato' => date("d/m/Y H:i:s", strtotime($resp->data_campeonato)),
                                                
            //                 ];
            //             }),
            //         ]);


    // public function listHistoryForChampionship($id)
    // {

    //     try {
    //         $historys = DB::table('resultados as r')
    //         ->select(
    //             't.name as time_name',
    //             'c.name as campeonato_name',
    //             'c.created_at as data_campeonato',
    //             DB::raw("'Fase 4' as fase_name"),
    //             'r.gols_equipe_a',
    //             'r.gols_equipe_b'
    //         )
    //         ->join('times as t', 'r.equipe_a_id', '=', 't.id')
    //         ->join('campeonatos as c', 't.campeonato_id', '=', 'c.id')
    //         ->where('r.fase_id', 4)
    //         ->where('c.id', $id)
        
    //         ->unionAll(DB::table('resultados as r')
    //             ->select(
    //                 't.name as time_name',
    //                 'c.name as campeonato_name',
    //                 'c.created_at as data_campeonato',
    //                 DB::raw("'Fase 4' as fase_name"),
    //                 'r.gols_equipe_b',
    //                 'r.gols_equipe_a'
    //             )
    //             ->join('times as t', 'r.equipe_b_id', '=', 't.id')
    //             ->join('campeonatos as c', 't.campeonato_id', '=', 'c.id')
    //             ->where('r.fase_id', 4)
    //             ->where('c.id', $id)
    //         )
        
    //         ->unionAll(DB::table('resultados as r')
    //             ->select(
    //                 't.name as time_name',
    //                 'c.name as campeonato_name',
    //                 'c.created_at as data_campeonato',
    //                 DB::raw("'Fase 3' as fase_name"),
    //                 'r.gols_equipe_a',
    //                 'r.gols_equipe_b'
    //             )
    //             ->join('times as t', 'r.equipe_a_id', '=', 't.id')
    //             ->join('campeonatos as c', 't.campeonato_id', '=', 'c.id')
    //             ->where('r.fase_id', 3)
    //             ->where('c.id',  $id)
    //         )
        
    //         ->unionAll(DB::table('resultados as r')
    //             ->select(
    //                 't.name as time_name',
    //                 'c.name as campeonato_name',
    //                 'c.created_at as data_campeonato',
    //                 DB::raw("'Fase 3' as fase_name"),
    //                 'r.gols_equipe_b',
    //                 'r.gols_equipe_a'
    //             )
    //             ->join('times as t', 'r.equipe_b_id', '=', 't.id')
    //             ->join('campeonatos as c', 't.campeonato_id', '=', 'c.id')
    //             ->where('r.fase_id', 3)
    //             ->where('c.id',  $id)
                
    //         )
    //         ->orderByDesc('fase_name')
    //         ->orderByDesc('gols_equipe_a')
    //         ->orderBy('gols_equipe_b')
    //         ->get();
        
    //         return response()->json([
    //             'result' => $historys->map(function ($resp) {
    //                 return [
    //                     'time_name' => $resp->time_name,
    //                     'campeonato_name' => $resp->campeonato_name,
    //                     'fase_name' => $resp->fase_name,
    //                     'gols_equipe_a' => $resp->gols_equipe_a,
    //                     'gols_equipe_b' => $resp->gols_equipe_b,
    //                     // 'data_campeonato'=>$resp->data_campeonato->format("Y-m-d H:i:s"),
    //                     'data_campeonato' => date("d/m/Y H:i:s", strtotime($resp->data_campeonato)),
                                        
    //                 ];
    //             }),
    //         ]);
    //     } catch (\Exception $e) {
    //         // Lidar com a exceção
    //         return response()->json([
    //             'error' => 'Ocorreu um erro ao buscar os dados: ' . $e->getMessage(),
    //         ], 500);
    //     }
    
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

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
    public function update(Request $request, $id)
    {
        //
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
}
