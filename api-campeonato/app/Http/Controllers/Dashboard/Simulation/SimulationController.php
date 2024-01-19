<?php

namespace App\Http\Controllers\Dashboard\Simulation;

use App\Http\Controllers\Controller;
use App\Models\Campeonato;
use App\Models\Time;
use App\Models\Resultado;
use App\Models\Status;
use App\Models\Torneio;
use App\Models\Fase;
use App\Models\Penalty;
use Illuminate\Support\Facades\Log;

class SimulationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $simulacao = Campeonato::whereIn('status_id', [1, 2,4])->get();

        return response()->json([
            'simulacao' => $simulacao->map(function ($resp) {
                $status = Status::find($resp->status_id);
                $torneio = Torneio::find($resp->torneio_id);

                return [
                    'id' => $resp->id,
                    'name' => $resp->name,
                    'torneio_id' => $torneio ? $torneio->name : null, // Nome do torneio
                    'status' => $status ? $status->name : null, // Nome do status
                    'created_at' => $resp->created_at->format("Y-m-d H:i:s"),
                    'updated_at' => $resp->updated_at->format("Y-m-d H:i:s"),
                ];
            })
        ]);
        
    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listForMatch($id)
    {
        try {
            
            $resultados = Resultado::where('campeonato_id', $id)
                ->whereNull('gols_equipe_a')
                ->whereNull('gols_equipe_b')
                ->get();

                return response()->json([
                    'resultado' => $resultados->map(function ($resp) {
                        $equipeA = Time::find($resp->equipe_a_id);
                        $equipeB = Time::find($resp->equipe_b_id);
                        $fase = Fase::find($resp->fase_id);       
                        return  [
                            'id' => $resp->id,
                            'fase' => $fase ? $fase->name : null,
                            'equipe_a' => $equipeA ? $equipeA->name : null,
                            'equipe_b' => $equipeB ? $equipeB->name : null,
                                ];
                    }),
                ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Erro ao obter resultados do campeonato.',
                'error' => $e->getMessage(),
            ]);
        }
        
    }

    public function listResultMatch($id)
    {
        try {
        
            $resultados = Resultado::where('campeonato_id', $id)->with('penalty')->get();

            return response()->json([
                'result' => $resultados->map(function ($resp) {
                    $equipeA = Time::find($resp->equipe_a_id);
                    $equipeB = Time::find($resp->equipe_b_id);
                    $fase = Fase::find($resp->fase_id);

                    // Verifica se há dados em penalts
                    $penaltyA = $resp->penalty ? $resp->penalty->gols_equipe_a : null;
                    $penaltyB = $resp->penalty ? $resp->penalty->gols_equipe_b : null;

                    return [
                        'id' => $resp->id,
                        'fase' => $fase ? $fase->name : null,
                        'equipe_a' => [
                            'nome' => $equipeA ? $equipeA->name : null,
                            'gols_marcados' => $resp->gols_equipe_a,
                            'gols_penaltis' => $penaltyA,
                        ],
                        'equipe_b' => [
                            'nome' => $equipeB ? $equipeB->name : null,
                            'gols_marcados' => $resp->gols_equipe_b,
                            'gols_penaltis' => $penaltyB,
                        ],
                    ];
                }),
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Erro ao obter resultados do campeonato.',
                'error' => $e->getMessage(),
            ]);
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function countResultsForChampionship($id) {
        try {
            
            $countTimes = Resultado::where('campeonato_id', $id)->count();

            if ($countTimes == 0) {
               
                return response()->json([
                    'status' => 200,
                    'message' => 'O Sorteio ainda não foi realizado.',
                    'quant' => $countTimes
                ]);
            }else{
                return response()->json([
                    'status' => 403,
                    'message' => 'Campeonato já foi simulado'
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Erro ao verificar a simulação do campeonato.',
                'error' => $e->getMessage(),
            ]);
        }
    }    

    public function prizeDraw($id)
    {
        try {
        
            $countTimes = Time::where('campeonato_id', $id)->count();

            $quantidadeResultadosFase1 = Resultado::where('campeonato_id', $id)
            ->where('fase_id', 1)
            ->count();

            if ($countTimes !== 8) {
        
                return response()->json([
                    'status' => 403,
                    'message' => 'O sorteio só pode ser realizado se houver exatamente 8 times cadastrados no campeonato.'
                ]);
            }

            if ($quantidadeResultadosFase1 >= 4) {
        
                return response()->json([
                    'status' => 403,
                    'message' => 'O sorteio da fase 1 já foi realizado.',
                    'quantidade' => $quantidadeResultadosFase1,
                ]);
            }
        
            $equipes = Time::join('campeonatos', 'times.campeonato_id', '=', 'campeonatos.id')
                ->where('times.campeonato_id', $id)
                ->where('campeonatos.status_id', 1)
                ->get(['times.id']);

            $equipesEmbaralhadas = $equipes->shuffle()->all();

            $paresDeEquipes = array_chunk($equipesEmbaralhadas, 2);

            $equipesEmparelhadas = [];

            foreach ($paresDeEquipes as $par) {
                $equipeAId = optional($par[0])['id'];
                $equipeBId = optional($par[1])['id'];

                if ($equipeAId && $equipeBId && !in_array($equipeAId, $equipesEmparelhadas) && !in_array($equipeBId, $equipesEmparelhadas)) {
                    $resultado = new Resultado([
                        'campeonato_id' => $id,
                        'fase_id' => 1,
                        'equipe_a_id' => $equipeAId,
                        'equipe_b_id' => $equipeBId,
                        'gols_equipe_a' => null,
                        'gols_equipe_b' => null,
                    ]);
                    $resultado->save();

                    $equipesEmparelhadas[] = $equipeAId;
                    $equipesEmparelhadas[] = $equipeBId;
                }
            }

            Campeonato::where('id', $id)->update(['status_id' => 2]);

            return response()->json([
                'status' => 'success',
                'message' => 'Sorteio realizado com sucesso!',
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 500,
                'message' => 'Erro ao realizar sorteio das equipes.',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function simulateGame($id) 
    {
        $resultado = Resultado::find($id);

        if (!$resultado) {
            return response()->json([
                'status' => 404,
                'message' => 'Resultado não encontrado.',
                'id'=>$id
            ], 404);
        }

        if ($resultado->gols_equipe_a !== null && $resultado->gols_equipe_b !== null) {
            return response()->json([
                'status' => 400,
                'message' => 'Este jogo já foi simulado anteriormente.',
            ], 400);
        }

        $golsEquipeA = random_int(0, 5); 
        $golsEquipeB = random_int(0, 5); 

        $resultado->update([
            'gols_equipe_a' => $golsEquipeA,
            'gols_equipe_b' => $golsEquipeB,
        ]);

        if ($golsEquipeA == $golsEquipeB) { 
            $this->disputaPenaltys($id);
        }

        $this->verifyFases($resultado->campeonato_id);

        return response()->json([
            'status' => 200,
            'message' => 'Simulação realizada com sucesso.',
            'gols_equipe_a' => $golsEquipeA,
            'gols_equipe_b' => $golsEquipeB,
        ]);
    }

    public function verifyFases($id)
    {
        try {
            $countTimes = Resultado::where('campeonato_id', $id)
                ->whereNotNull('gols_equipe_a')
                ->whereNotNull('gols_equipe_b')
                ->count();

            if ($countTimes == 0) {
                return response()->json([
                    'status' => 200,
                    'message' => 'O Sorteio ainda não foi realizado.',
                    'quant' => $countTimes
                ]);
            } elseif ($countTimes == 4) {
                $this->generateSemifinals($id);
                return response()->json([
                    'status' => 200,
                    'message' => 'Sortear Semi-Finais'
                ]);
            } elseif ($countTimes == 6) {
                $this->generateThirdPlaceMatch($id);
                $this->generateFinalMatch($id);
                return response()->json([
                    'status' => 200,
                    'message' => 'Gerar Disputa de 3º Lugar e final '
                ]);
            } elseif ($countTimes == 8) {
                
                Campeonato::where('id', $id)->update(['status_id' => 4]); 
                return response()->json([
                    'status' => 200,
                    'message' => 'Sortear Disputa de 3º Lugar e Final'
                ]);
            }  
            else {
                return response()->json([
                    'status' => 403,
                    'message' => 'Campeonato já foi simulado'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Erro ao verificar a simulação do campeonato.',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function generateSemifinals($id)
    {
        $quartasFinaisConcluidas = Resultado::where('fase_id', 1)
            ->where('campeonato_id', $id)
            ->whereNotNull('gols_equipe_a')
            ->whereNotNull('gols_equipe_b')
            ->count() == 4;
    
        if (!$quartasFinaisConcluidas) {
            return response()->json([
                'status' => 403,
                'message' => 'A fase anterior (quartas de finais) não foi concluída ainda para o campeonato ' . $id . '.',
            ]);
        }
    
        $resultadosQuartasFinais = Resultado::where('fase_id', 1)
            ->where('campeonato_id', $id)
            ->get(['id', 'equipe_a_id', 'equipe_b_id', 'gols_equipe_a', 'gols_equipe_b']);
    
        $vencedoresQuartasFinais = [];
    
        foreach ($resultadosQuartasFinais as $resultado) {
            if ($resultado->gols_equipe_a > $resultado->gols_equipe_b) {
                $vencedoresQuartasFinais[] = $resultado->equipe_a_id;
            } elseif ($resultado->gols_equipe_a < $resultado->gols_equipe_b) {
                $vencedoresQuartasFinais[] = $resultado->equipe_b_id;
            } else {
                // O jogo está empatado, verificar na tabela "Penalty"
                $penalty = Penalty::where('resultado_id', $resultado->id)->first();
    
                if ($penalty) {
                    // Verificar qual equipe teve mais gols na tabela "Penalty"
                    if ($penalty->gols_equipe_a > $penalty->gols_equipe_b) {
                        $vencedoresQuartasFinais[] = $resultado->equipe_a_id;
                    } elseif ($penalty->gols_equipe_a < $penalty->gols_equipe_b) {
                        $vencedoresQuartasFinais[] = $resultado->equipe_b_id;
                    } else {
                        // Lógica adicional se ainda estiver empatado após o desempate por pênaltis
                    }
                } else {
                    // Lógica adicional se não houver informações de pênaltis na tabela "Penalty"
                }
            }
        }
    
        if (count($vencedoresQuartasFinais) == 4) {
            $semifinais = array_chunk($vencedoresQuartasFinais, 2);
    
            foreach ($semifinais as $confronto) {
                $resultado = new Resultado([
                    'fase_id' => 2,
                    'campeonato_id' => $id,
                    'equipe_a_id' => $confronto[0],
                    'equipe_b_id' => $confronto[1],
                    'gols_equipe_a' => null,
                    'gols_equipe_b' => null,
                ]);
                $resultado->save();
            }
    
            return response()->json([
                'status' => 200,
                'message' => 'Semifinais geradas com sucesso para o campeonato ' . $id . '.',
                'semifinais' => $semifinais,
            ]);
        } else {
            return response()->json([
                'status' => 403,
                'message' => 'Não há equipes suficientes para gerar as semifinais para o campeonato ' . $id . '.',
            ]);
        }
    }

    public function generateThirdPlaceMatch($id)
    {
        $perdedoresSemifinais = Resultado::where('fase_id', 2)
            ->where('campeonato_id', $id)
            ->get(['id', 'equipe_a_id', 'equipe_b_id', 'gols_equipe_a', 'gols_equipe_b']);
    
        $equipesTerceiroLugar = [];
    
        foreach ($perdedoresSemifinais as $resultado) {
            if ($resultado->gols_equipe_a > $resultado->gols_equipe_b) {
                $equipesTerceiroLugar[] = $resultado->equipe_b_id;
            } elseif ($resultado->gols_equipe_a < $resultado->gols_equipe_b) {
                $equipesTerceiroLugar[] = $resultado->equipe_a_id;
            } else {
                $penalty = Penalty::where('resultado_id', $resultado->id)->first();
    
                if ($penalty) {

                    if ($penalty->gols_equipe_a > $penalty->gols_equipe_b) {
                        $equipesTerceiroLugar[] = $resultado->equipe_b_id;
                    } elseif ($penalty->gols_equipe_a < $penalty->gols_equipe_b) {
                        $equipesTerceiroLugar[] = $resultado->equipe_a_id;
                    } else {
                        // Lógica adicional se ainda estiver empatado após o desempate por pênaltis
                    }
                } else {
                    // Lógica adicional se não houver informações de pênaltis na tabela "Penalty"
                }
            }
        }
    
        if (count($equipesTerceiroLugar) == 2) {

            $resultado = new Resultado([
                'fase_id' => 3,
                'campeonato_id' => $id,
                'equipe_a_id' => $equipesTerceiroLugar[0],
                'equipe_b_id' => $equipesTerceiroLugar[1],
            ]);
            $resultado->save();
    
            return response()->json([
                'status' => 200,
                'message' => 'Disputa de terceiro lugar gerada com sucesso para o campeonato ' . $id . '.',
                'terceiro_lugar' => $equipesTerceiroLugar,
            ]);
        } else {
            return response()->json([
                'status' => 403,
                'message' => 'Não há equipes suficientes para gerar a disputa de terceiro lugar para o campeonato ' . $id . '.',
            ]);
        }
    }
    
    
    public function generateFinalMatch($id)
    {
        $vencedoresSemifinais = Resultado::where('fase_id', 2)
            ->where('campeonato_id', $id)
            ->get(['id', 'equipe_a_id', 'equipe_b_id', 'gols_equipe_a', 'gols_equipe_b']);
    
        $equipesFinal = [];
    
        foreach ($vencedoresSemifinais as $resultado) {
            if ($resultado->gols_equipe_a > $resultado->gols_equipe_b) {
                $equipesFinal[] = $resultado->equipe_a_id;
            } elseif ($resultado->gols_equipe_a < $resultado->gols_equipe_b) {
                $equipesFinal[] = $resultado->equipe_b_id;
            } else {
                $penalty = Penalty::where('resultado_id', $resultado->id)->first();
    
                if ($penalty) {
                    if ($penalty->gols_equipe_a > $penalty->gols_equipe_b) {
                        $equipesFinal[] = $resultado->equipe_a_id;
                    } elseif ($penalty->gols_equipe_a < $penalty->gols_equipe_b) {
                        $equipesFinal[] = $resultado->equipe_b_id;
                    } else {
                        // Lógica adicional se ainda estiver empatado após o desempate por pênaltis
                    }
                } else {
                    // Lógica adicional se não houver informações de pênaltis na tabela "Penalty"
                }
            }
        }
    
        if (count($equipesFinal) == 2) {
            $resultado = new Resultado([
                'fase_id' => 4,
                'campeonato_id' => $id,
                'equipe_a_id' => $equipesFinal[0],
                'equipe_b_id' => $equipesFinal[1],
                'gols_equipe_a' => null,
                'gols_equipe_b' => null,
            ]);
            $resultado->save();
    
            return response()->json([
                'status' => 200,
                'message' => 'Final gerada com sucesso para o campeonato ' . $id . '.',
                'final' => $equipesFinal,
            ]);
        } else {
            return response()->json([
                'status' => 403,
                'message' => 'Não há equipes suficientes para gerar a final para o campeonato ' . $id . '.',
            ]);
        }
    }
    
    
    public function disputaPenaltys($resultadoId)
    {
        $resultado = Resultado::find($resultadoId);
        
        if (!$resultado) {
            
            return response()->json([
                'status' => 404,
                'message' => 'Resultado não encontrado.',
                'resultado_id' => $resultadoId,
            ], 404);
        }
        
        $golsEquipeA = 0;
        $golsEquipeB = 0;
        
        $rodadasIniciais = 5; 
        $cobrancasAlternadas = 0;
        
        $intervaloGol = 6;
        
        for ($i = 0; $i < $rodadasIniciais; $i++) {

            $golAleatorioEquipeA = (random_int(0, $intervaloGol) < 5) ? 1 : 0; 
            $golAleatorioEquipeB = (random_int(0, $intervaloGol) < 5) ? 1 : 0;         
            
            $golsEquipeA += $golAleatorioEquipeA;
            $golsEquipeB += $golAleatorioEquipeB;
        
            $cobrancasAlternadas++;
            
            if ($golsEquipeA > $golsEquipeB + (10 - $cobrancasAlternadas)) {
                break;
            } elseif ($golsEquipeB > $golsEquipeA + (10 - $cobrancasAlternadas)) {
                break;
            }
        }
        
        while ($golsEquipeA === $golsEquipeB && $cobrancasAlternadas < 10) {

            $golAleatorioEquipeA = (random_int(0, $intervaloGol) < 5) ? 1 : 0;
            $golAleatorioEquipeB = (random_int(0, $intervaloGol) < 5) ? 1 : 0;

            $golsEquipeA += $golAleatorioEquipeA;
            $golsEquipeB += $golAleatorioEquipeB;

            $cobrancasAlternadas++;
        }
        
        $penalty = new Penalty([
            'resultado_id' => $resultadoId,
            'gols_equipe_a' => $golsEquipeA,
            'gols_equipe_b' => $golsEquipeB,
        ]);
        $penalty->save();
        
        return response()->json([
            'status' => 200,
            'message' => 'Disputa de pênaltis simulada com sucesso.',
            'gols_equipe_a' => $golsEquipeA,
            'gols_equipe_b' => $golsEquipeB,
            'cobrancas_alternadas' => $cobrancasAlternadas,
        ]);
    }
        
}
