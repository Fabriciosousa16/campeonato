<?php

namespace App\Http\Controllers\Dashboard\Championship;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Campeonato;
use App\Models\Status;


class ChampionshipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $campeonato = Campeonato::all();
        
        return response()->json([
            'campeonato' => $campeonato->map(function ($resp) {
                $status = Status::find($resp->status_id);
        
                return [
                    'id' => $resp->id,
                    'name' => $resp->name,
                    'torneio_id' => $resp->torneio_id,
                    'status_id' => $status ? $status->name : null, // Exibe o nome do status se encontrado
                    'created_at' => $resp->created_at->format("d/m/Y H:i:s"), // Formato brasileiro
                    'updated_at' => $resp->updated_at->format("d/m/Y H:i:s"), // Formato brasileiro
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
        $verifyName = campeonato::where('name', $request->name)->first();

        if ($verifyName) {
            return response()->json([
                "status" => 403,
                "message" => "Nome do Campeonato já cadastrado"
            ]);
        }
        
        try {
            $campeonato = campeonato::create([
                'name' => $request->name,
                'torneio_id' => $request->torneio_id,
                'status_id' => $request->status_id,
            ]);

            if($campeonato){
                return response()->json([
                    "status" => 200,
                    "message" => "Adicionado com Sucesso"
                ]);
            }
        

        } catch (\Exception $e) {
            return response()->json([
                "status" => 500,
                "message" => "Erro ao adicionar o Campeonato",
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
        $search = campeonato::findOrFail($id);

        return response()->json([
            'id' => $search->id,
            'name' => $search->name,
            'torneio_id' => $search->torneio_id,
            'status_id' => $search->status_id,
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
        $verifyName = campeonato::where('name', $request->name)->first();

        if ($verifyName) {
            return response()->json([
                "status" => 403,
                "message" => "Nome do Campeonato já cadastrado"
            ]);
        }
        
        try {

            $update = campeonato::findOrFail($id);

            $update->update($request->all());
        
            return response()->json([
                "status" => 200,
                "message" => "Atualizado com Sucesso"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => 500,
                "message" => "Erro ao Atualizar o Campeonato",
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
            $delete = Campeonato::findOrFail($id);
            $delete->delete();
        
            return response()->json([
                "status" => 200,
                "message" => "Campeonato deletado com sucesso."
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                "status" => 404,
                "message" => "Campeonato não encontrado."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => 500,
                "message" => "Erro ao deletar o campeonato.",
                "error" => $e->getMessage()
            ]);
        }
        
    }
}
