<?php

namespace App\Http\Controllers\Dashboard\Teams;

use App\Http\Controllers\Controller;
use App\Models\Time;
use App\Models\Campeonato;
use Illuminate\Http\Request;

class TeamsController extends Controller
{
    public function index()
    {
      // Seu controlador
        $times = Time::with('campeonato')->get();

        return response()->json([
            'times' => $times->map(function ($resp) {
                return [
                    'id' => $resp->id,
                    'name' => $resp->name,
                    'campeonato_name' => $resp->campeonato ? $resp->campeonato->name : null, 
                    'created_at' => $resp->created_at->format("d/m/Y H:i:s"), 
                    'updated_at' => $resp->updated_at->format("d/m/Y H:i:s"), 
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
        $verifyName = Time::where('name', $request->name)
        ->where('campeonato_id', $request->campeonato_id)
        ->first();

        $countTimesInCampeonato = Time::where('campeonato_id', $request->campeonato_id)->count();

        if ($countTimesInCampeonato>7)  {
            return response()->json([
                "status" => 403,
                "message" => "Campeonato Já possui a quantidade maxima de times permitidas"
            ]);
        }
    

        if ($verifyName) {
            return response()->json([
                "status" => 403,
                "message" => "Nome do Time já cadastrado no Campeonato"
            ]);
        }
        
        try {
            $time = Time::create($request->all());

            if ($time){
                return response()->json([
                    "status" => 200,
                    "message" => "Adicionado com Sucesso"
                ]);
            }
        
          
        } catch (\Exception $e) {
            return response()->json([
                "status" => 500,
                "message" => "Erro ao adicionar o Time",
                "error" => $e->getMessage()
            ]);
        }        

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $search = Time::findOrFail($id);

        return response()->json([
            'id' => $search->id,
            'name' => $search->name,
            'campeonato_id' => $search->campeonato_id,
            'created_at' => $search->created_at->format("Y-m-d H:i:s"),
            'update_at' => $search->updated_at->format("Y-m-d H:i:s")
        ]);
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
        $verifyName = Time::where('name', $request->name)
        ->where('campeonato_id', $request->campeonato_id)
        ->first();

        if ($verifyName) {
            return response()->json([
                "status" => 403,
                "message" => "Nome do Time já cadastrado no Campeonato"
            ]);
        }
        
        try {

            $update = Time::findOrFail($id);

            $update->update($request->all());
        
            return response()->json([
                "status" => 200,
                "message" => "Atualizado com Sucesso"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => 500,
                "message" => "Erro ao Atualizar o Time",
                "error" => $e->getMessage()
            ]);
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

            $delete = Time::findOrFail($id);

            if ($delete){
                
                $delete->delete();
        
                return response()->json([
                    "status" => 200,
                    "message" => "Deletado com Sucesso"
                ]);
            }
            
           
        } catch (\Exception $e) {
            return response()->json([
                "status" => 500,
                "message" => "Erro ao Deletar o Time",
                "error" => $e->getMessage()
            ]);
        }
    }
}
