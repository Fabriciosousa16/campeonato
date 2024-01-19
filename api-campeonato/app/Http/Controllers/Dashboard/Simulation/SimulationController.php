<?php

namespace App\Http\Controllers\Dashboard\Simulation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
        $simulacao = Campeonato::whereIn('status_id', [1, 2])->get();

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

    public function destroy($id)
    {
        //
    }


    public function prizeDraw($id)
    {
        try {
            // Verificar se há exatamente 8 times cadastrados no campeonato
            $countTimes = Time::where('campeonato_id', $id)->count();
            
            $quantidadeResultadosFase1 = Resultado::where('campeonato_id', $id)
            ->where('fase_id', 1)
            ->count();

            if ($countTimes !== 8) {
                // Se não houver exatamente 8 times, retornar uma resposta indicando o problema
                return response()->json([
                    'status' => 403,
                    'message' => 'O sorteio só pode ser realizado se houver exatamente 8 times cadastrados no campeonato.'
                ]);
            }

            if ($quantidadeResultadosFase1 >= 4) {
                // Se a quantidade for maior ou igual a 4, o sorteio já foi realizado
                return response()->json([
                    'status' => 403,
                    'message' => 'O sorteio da fase 1 já foi realizado.',
                    'quantidade' => $quantidadeResultadosFase1,
                ]);
            }
            
            // Se houver exatamente 8 times, prosseguir com o sorteio
            $equipes = Time::join('campeonatos', 'times.campeonato_id', '=', 'campeonatos.id')
                ->where('times.campeonato_id', $id)
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
                        'campeonato_id' => $id,
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
            Campeonato::where('id', $id)->update(['status_id' => 2]);

            // Retornar uma resposta de sucesso com os valores gerados
            return response()->json([
                'status' => 'success',
                'message' => 'Sorteio realizado com sucesso!',
            ]);
        } catch (\Exception $e) {
            // Retornar uma resposta de erro em caso de exceção
            return response()->json([
                'status' => 500,
                'message' => 'Erro ao realizar sorteio das equipes.',
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

    public function simulateGame($id) 
    {
    // Encontrar o resultado pelo ID
        $resultado = Resultado::find($id);

        // Verificar se o resultado foi encontrado
        if (!$resultado) {
            return response()->json([
                'status' => 404,
                'message' => 'Resultado não encontrado.',
                'id'=>$id
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

        // Verificar se os gols são iguais
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
                        // Aqui você pode adicionar uma lógica específica para o caso de empate nos pênaltis
                        // Se necessário
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
                // O jogo está empatado, verificar na tabela "Penalty"
                $penalty = Penalty::where('resultado_id', $resultado->id)->first();
    
                if ($penalty) {
                    // Verificar qual equipe teve mais gols na tabela "Penalty"
                    if ($penalty->gols_equipe_a > $penalty->gols_equipe_b) {
                        $equipesTerceiroLugar[] = $resultado->equipe_b_id;
                    } elseif ($penalty->gols_equipe_a < $penalty->gols_equipe_b) {
                        $equipesTerceiroLugar[] = $resultado->equipe_a_id;
                    } else {
                        // Lógica adicional se ainda estiver empatado após o desempate por pênaltis
                        // Se necessário
                    }
                } else {
                    // Lógica adicional se não houver informações de pênaltis na tabela "Penalty"
                }
            }
        }
    
        // Verificar se há equipes suficientes para gerar a disputa de terceiro lugar
        if (count($equipesTerceiroLugar) == 2) {
            // Inserir o jogo da disputa de terceiro lugar
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
        // Obter as equipes que venceram nas semifinais
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
                // O jogo está empatado, verificar na tabela "Penalty"
                $penalty = Penalty::where('resultado_id', $resultado->id)->first();
    
                if ($penalty) {
                    // Verificar qual equipe teve mais gols na tabela "Penalty"
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
    
        // Verificar se há equipes suficientes para gerar a final
        if (count($equipesFinal) == 2) {
            // Inserir o jogo da final
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
        // Obter o resultado do jogo para acessar informações das equipes
        $resultado = Resultado::find($resultadoId);
        
        if (!$resultado) {
            // Lida com a situação em que o resultado não foi encontrado
            return response()->json([
                'status' => 404,
                'message' => 'Resultado não encontrado.',
                'resultado_id' => $resultadoId,
            ], 404);
        }
        
        // Simular a disputa de pênaltis
        $golsEquipeA = 0;
        $golsEquipeB = 0;
        
        $rodadasIniciais = 5; // Número inicial de rodadas regulares
        $cobrancasAlternadas = 0;
        
        // Converter 70% de chance para uma faixa de 0 a 6 (por exemplo)
        $intervaloGol = 6;
        
        // Simulação das rodadas regulares
        for ($i = 0; $i < $rodadasIniciais; $i++) {
            // Gere valores aleatórios para os gols de cada equipe (70% de chance de sucesso)
            $golAleatorioEquipeA = (random_int(0, $intervaloGol) < 5) ? 1 : 0; // 70% de chance de sucesso
            $golAleatorioEquipeB = (random_int(0, $intervaloGol) < 5) ? 1 : 0; // 70% de chance de sucesso
        
            // Adicione os gols gerados ao placar
            $golsEquipeA += $golAleatorioEquipeA;
            $golsEquipeB += $golAleatorioEquipeB;
        
            $cobrancasAlternadas++;
            
            // Verificar se uma equipe já venceu e a outra não pode mais alcançar
            if ($golsEquipeA > $golsEquipeB + (10 - $cobrancasAlternadas)) {
                break;
            } elseif ($golsEquipeB > $golsEquipeA + (10 - $cobrancasAlternadas)) {
                break;
            }
        }
        
        // Continuar a simulação de rodadas até que haja um vencedor
        while ($golsEquipeA === $golsEquipeB && $cobrancasAlternadas < 10) {
            // Gere valores aleatórios para os gols de cada equipe (70% de chance de sucesso)
            $golAleatorioEquipeA = (random_int(0, $intervaloGol) < 5) ? 1 : 0;
            $golAleatorioEquipeB = (random_int(0, $intervaloGol) < 5) ? 1 : 0;

            $golsEquipeA += $golAleatorioEquipeA;
            $golsEquipeB += $golAleatorioEquipeB;

            $cobrancasAlternadas++;
        }
        
        // Salvar os resultados na tabela 'penaltys'
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
