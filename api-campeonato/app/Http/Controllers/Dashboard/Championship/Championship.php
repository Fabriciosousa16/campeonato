<?php

namespace App\Http\Controllers\Dashboard\Championship;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Championship extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $championship = Championship::all();

        return response()->json([
            'championship' => $championship->map(function($resp){
                return [
                    'id' => $resp->id,
                    'name' => $resp->name,
                    'type' => $resp->type,
                    'created_at' =>$resp->created_at->format("y=m=d h:i:s"),
                    'update_at' => $resp->update_at
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
        $verifyName = Championship::where('name', $request->name)->first();

        if ($verifyName) {
            return response()->json([
                "status" => 403,
                "message" => "Nome do Campeonato já cadastrado"
            ]);
        }
        
        try {
            $championship = Championship::create([
                'name' => $request->name,
                'type' => $request->type,
            ]);
        
            return response()->json([
                "status" => 200,
                "message" => "Adicionado com Sucesso"
            ]);
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
        $verifyName = Championship::where('name', $request->name)->first();

        if ($verifyName) {
            return response()->json([
                "status" => 403,
                "message" => "Nome do Campeonato já cadastrado"
            ]);
        }
        
        try {

            $update = Championship::findOrFail($id);

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

            $delete = Championship::findOrFail($id);

            $delete->delete();
        
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
}
