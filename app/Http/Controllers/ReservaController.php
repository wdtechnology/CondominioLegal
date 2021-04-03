<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Models\Area;
use App\Models\AreaDiaManutencao;
use App\Models\Reserva;
use App\Models\Unidade;


class ReservaController extends Controller
{
    public function buscarReservas(){
        $array = ['error' => '', 'lista' => []];
        $diasDaSemana = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'];

        $areas = Area::where('liberado', 1)->get();

        foreach($areas as $area){
            $listaDias = explode(',', $area['dia']);
            $grupoDias = [];

            //Adicionando o primeiro dia
            $ultimoDia = intval(current($listaDias));
            $grupoDias[] = $diasDaSemana[$ultimoDia];
            array_shift($listaDias);

            //Adicionando dias relevantes
            foreach($listaDias as $dia){
                if(intval($dia) != $ultimoDia +1){
                    $grupoDias[] = $diasDaSemana[$ultimoDia];
                    $grupoDias[] = $diasDaSemana[$dia];
                }
                $ultimoDia = intval($dia);
            }

            //Adicionando o ultimo dia
            $grupoDias[] = $diasDaSemana[end($listaDias)];

            //Juntando as datas
            $datas = '';
            $c = 0;
            foreach($grupoDias as $grupo){
                if($c  === 0){
                    $datas .= $grupo;
                }else{
                    $datas .= '-'.$grupo.',';
                }
                $c  = 1 - $c ;
            }

            $datas = explode(',', $datas);
            array_pop($datas);

            //Adicionando a Hora
            $comeco = date('H:i', strtotime($area['data_abertura']));
            $fim = date('H:i', strtotime($area['data_fechamento']));

            foreach($datas as $chave => $value){
                if($datas[$chave] == "Dom-Dom"){
                    $dates[$chave] = "Dom";
                }
                $datas[$chave] .= ' '.$comeco.' as '.$fim;
            }

            $array['lista'][] = [
                'id' => $area['id'],
                'capa' => asset('storage/'.$area['capa']),
                'titulo' => $area['titulo'],
                'datas' => $datas
            ];
        }
        return $array;
    }

    public function buscarDatasFechado($id){
        $array = ['error' => '', 'lista' => []];

        $area = Area::find($id);
        if($area){
            //Dias desabled padrao
            $diasDesabilitados = AreaDiaManutencao::where('id_area', $id)->get();
            foreach($diasDesabilitados  as $diaDesabilitado){
                $array['lista'][] = $diaDesabilitado['dia']; 
            }
            //Dias disabled atraves do allowed
            $diasPermitidos = explode(',', $area['dia']);
            $diasNaoPermitidos = [];
            for($q=0; $q<7; $q++){
                if(!in_array($q, $diasPermitidos)){
                    $diasNaoPermitidos[] = $q;
                }
            }
            //Listar os dias proibidos tres dias para frente
            $inicio = time();
            $fim = strtotime('+3 months');
            $atual = $inicio;
            $busca = true;
            while($busca){
                if($atual < $fim){
                    $wd = date('w', $atual);
                    if(in_array($wd, $diasNaoPermitidos)){
                        $array['lista'][] = date('Y-m-d', $atual);
                    }
                    $atual = strtotime('+1 day', $atual);
                }else{
                    $busca = false;
                }
            }
        }else{
            $array['error'] = 'Area inexistente';
            return $array;
        }
        return $array;
    }

    public function buscarHoraReservadas($id, Request $request){
        $array = ['error' => '' , 'lista' => []];

        $validator = Validator::make($request->all(), [
            'data' => 'required|date_format:Y-m-d'
        ]);
        if(!$validator->fails()){
            $data = $request->input('data');
            $area = Area::find($id);

            if($area){
                $valido = true;

                //Verificar de é dia disabled
                $diasDeAreaFechado = AreaDiaManutencao::where('id_area', $id)->where('dia' , $data)->count();
                if($diasDeAreaFechado > 0){
                    $valido = false;
                }
                //Verificar se e dia permitido
                $diasPermitidos = explode(',', $area['dia']);
                $diaDaSemana = date('w', strtotime($data));
                if(!in_array($diaDaSemana, $diasPermitidos)){
                    $valido = false;
                }

                if($valido){
                    $inicio = strtotime($area['data_abertura']);
                    $fim = strtotime($area['data_fechamento']);
                    $times = [];
                    for ($lastTime = $inicio; $lastTime < $fim; $lastTime = strtotime('+1 hour', $lastTime)) { 
                        $times[] = $lastTime;
                    }

                    $timeLista = [];

                    foreach($times as $time){
                        $timeLista[] = [
                            'id' => date('H:i:s', $time),
                            'titulo' => date('H:i', $time).' - '.date('H:i', strtotime('+1 hour', $time))
                        ];
                    }

                    //Removendo as reservas
                    $reservas = Reserva::where('id_area', $id)->whereBetween('hora_reserva', [
                        $data.' 00:00:00',
                        $data.' 23:59:59'
                    ])->get();

                    $paraRemover = [];
                    foreach($reservas as $reserva){
                        $hora = date('H:i:s', strtotime($reserva['hora_reserva']));
                        $paraRemover[] = $hora;
                    }

                    foreach($timeLista as $timeItem){
                        if(!in_array($timeItem['id'], $paraRemover)){
                            $array['list'][] = $timeItem;
                        }
                    }
                }else{
                    $array['error'] = 'Nao esta permitido reservas para esse dia.';
                    return $array;
                }
            }else{
                $array['error'] = 'Area inexistente';
                return $array;
            }
        }else{
            $array['error'] = $validator->errors()->first();
            return $array;
        }
        return $array;
    }

    public function fazerReserva($id, Request $request){
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
            'data' => 'required|date_format:Y-m-d',
            'hora' => 'required|date_format:H:i:s',
            'propriedade' => 'required'
        ]);

        if(!$validator->fails()){
            $data = $request->input('data');
            $hora = $request->input('hora');
            $propriedade = $request->input('propriedade');

            $unidade = Unidade::find($propriedade);
            $area = Area::find($id);

            if($unidade && $area){
                $valido = true;
                $diaDaSemana = date('w', strtotime($data));

                //Verificar se esta dentro da disponibilidade padrao
                $diasPermitidos = explode(',', $area['dia']);
                if(!in_array($diaDaSemana, $diasPermitidos)){
                    $valido = false;
                }else{
                    $inicio = strtotime($area['data_abertura']);
                    $fim = strtotime('-1 hour', strtotime($area['data_fechamento']));
                    $reservaHora = strtotime($hora);
                    if($reservaHora < $inicio || $reservaHora > $fim){
                        $valido = false;
                    }
                }

                //verificar se esta fora dos disabledays
                $diasDeManutencao = AreaDiaManutencao::where('id_area', $id)->where('dia', $data)->count();
                if($diasDeManutencao > 0){
                    $valido = false;
                } 

                //Verificar se nao existe outra reserva no mesmo dia/hora.
                $horaLista = explode(":", $hora);
                $novaHora = $horaLista[0];
                $horasReservadas = Reserva::where('id_area', $id)->where('data_reserva', $data)->where('hora_reserva', 'LIKE' , $novaHora.'%')->count();

                if($horasReservadas){
                    $valido = false;
                }

                if($valido){
                    $novo = new Reserva();
                    $novo->id_unidade = $propriedade;
                    $novo->id_area = $id;
                    $novo->data_reserva = $data;
                    $novo->hora_reserva = $hora;
                    $novo->save();
                }else{
                    $array['error'] = 'Reserva nao permitida neste dia/horario';
                    return $array;
                }

            }else{
                $array['error'] = 'Dados incorretos';
                return $array;
            }

        }else{
            $array['error'] = $validator->errors()->first();
            return $array;
        }
        return $array;
    }

    public function minhasReservas(Request $request){
        $array = ['error' => '', 'lista' => []];

        $propriedade = $request->input('propriedade');
        if($propriedade){
            $unidade = Unidade::find($propriedade);

            if($unidade){
                $reservas = Reserva::where('id_unidade', $propriedade)->orderBy('data_reserva', 'DESC')->get();

                foreach($reservas as $reserva){
                    $area = Area::find($reserva['id_area']);
                    $datareserva = date('d/m/Y', strtotime($reserva['data_reserva']));
                    $horareserva = date('H:i', strtotime($reserva['hora_reserva']));
                    $horaAdiantada = date('H:i', strtotime('+1 hour', strtotime($reserva['hora_reserva'])));
                    $mostarData = $datareserva . ' ' . $horareserva . ' a ' . $horaAdiantada;

                    $array['lista'][] = [
                        'id' => $reserva['id'],
                        'id_area' => $reserva['id_area'],
                        'titulo' => $area['titulo'],
                        'capa' => asset('storage/'.$area['capa']),
                        'data_reserva' => $mostarData
                    ];
                }

            }else{
                $array['error'] = 'Numero da propriedade invalido';
                return $array;
            }

        }else{
            $array['error'] = 'Necessario informar sua propriedade';
            return $array;
        }
        return $array;
    }

    public function deletarReserva($id){
        $array = ['error' => ''];

        $user = Auth::user();
        $reserva = Reserva::find($id);
        if($reserva){
            $unidade = Unidade::where('id', $reserva['id_unidade'])->where('id_dono', $user['id'])->count();

            if($unidade > 0){
                Reserva::find($id)->delete();
            }else{
                $array['error'] = 'Esta reserva nao e sua';
                return $array;
            }

        }else{
            $array['error'] = 'Reserva inexistente';
            return $array;
        }
        return $array;
    }


}
