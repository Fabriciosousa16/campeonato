<?php

namespace App\Http\Controllers\Dashboard\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Campeonato;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            // Total de campeonatos
            $totalCampeonatos = Campeonato::count();
        
            // Campeonatos com status 1
            $campeonatosStatus1 = Campeonato::where('status_id', 1)->count();
        
            // Campeonatos com status 2
            $campeonatosStatus2 = Campeonato::where('status_id', 2)->count();
        
            // Campeonatos com status 4
            $campeonatosStatus4 = Campeonato::where('status_id', 4)->count();
        
            return response()->json([
                'status' => 200,
                'message' => 'Estatísticas de campeonatos obtidas com sucesso.',
                'campeonato' => [
                    'total' => $totalCampeonatos,
                    'abertos' => $campeonatosStatus1,
                    'em_andamento' => $campeonatosStatus2,
                    'finalizados' => $campeonatosStatus4,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Erro ao obter estatísticas de campeonatos.',
                'error' => $e->getMessage(),
            ]);
        }
        
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
