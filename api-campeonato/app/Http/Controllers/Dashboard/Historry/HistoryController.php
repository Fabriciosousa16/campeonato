<?php

namespace App\Http\Controllers\Dashboard\Historry;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $historys = DB::table('resultados as r')
        ->select(
            't.name as time_name',
            'c.name as campeonato_name',
            DB::raw("'Fase 4' as fase_name"),
            'r.gols_equipe_a',
            'r.gols_equipe_b'
        )
        ->join('times as t', 'r.equipe_a_id', '=', 't.id')
        ->join('campeonatos as c', 't.campeonato_id', '=', 'c.id')
        ->where('r.fase_id', 4)
        ->where('c.id', 1)
    
        ->unionAll(DB::table('resultados as r')
            ->select(
                't.name as time_name',
                'c.name as campeonato_name',
                DB::raw("'Fase 4' as fase_name"),
                'r.gols_equipe_b',
                'r.gols_equipe_a'
            )
            ->join('times as t', 'r.equipe_b_id', '=', 't.id')
            ->join('campeonatos as c', 't.campeonato_id', '=', 'c.id')
            ->where('r.fase_id', 4)
            ->where('c.id', $request->campeonato_id)
        )
    
        ->unionAll(DB::table('resultados as r')
            ->select(
                't.name as time_name',
                'c.name as campeonato_name',
                DB::raw("'Fase 3' as fase_name"),
                'r.gols_equipe_a',
                'r.gols_equipe_b'
            )
            ->join('times as t', 'r.equipe_a_id', '=', 't.id')
            ->join('campeonatos as c', 't.campeonato_id', '=', 'c.id')
            ->where('r.fase_id', 3)
            ->where('c.id',  $request->campeonato_id)
        )
    
        ->unionAll(DB::table('resultados as r')
            ->select(
                't.name as time_name',
                'c.name as campeonato_name',
                DB::raw("'Fase 3' as fase_name"),
                'r.gols_equipe_b',
                'r.gols_equipe_a'
            )
            ->join('times as t', 'r.equipe_b_id', '=', 't.id')
            ->join('campeonatos as c', 't.campeonato_id', '=', 'c.id')
            ->where('r.fase_id', 3)
            ->where('c.id',  $request->campeonato_id)
            
        )
        ->orderByDesc('fase_name')
        ->orderByDesc('gols_equipe_a')
        ->orderBy('gols_equipe_b')
        ->get();
    
    return response()->json([
        'historys' => $historys->map(function ($resp) {
            return [
                'time_name' => $resp->time_name,
                'campeonato_name' => $resp->campeonato_name,
                'fase_name' => $resp->fase_name,
                'gols_equipe_a' => $resp->gols_equipe_a,
                'gols_equipe_b' => $resp->gols_equipe_b,
            ];
        }),
    ]);
    
    }

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
