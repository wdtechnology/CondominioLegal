<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Models\Reclamacao;
use App\Models\Unidade;


class ReclamacaoController extends Controller
{
    public function minhasReclamacoes(Request $request){
        $array = ['error' => ''];

        $propriedade = $request->input('propriedade');
        if($propriedade){
            $usuario = Auth::user();
            $unidade = Unidade::where('id', $propriedade)->where('id_dono', $usuario['id'])->count();

            if($unidade > 0){
               $reclamacoes = Reclamacao::where('id_unidade', $propriedade)->orderBy('data_criacao', 'DESC')->orderBy('id', 'DESC')->get();

               foreach($reclamacoes as $chave => $valor){
                    $reclamacoes[$chave]['data_criacao'] = date('d/m/Y', strtotime($valor['data_criacao']));
                    $listaFotos = [];
                    $fotos = explode(',', $valor['fotos']);
                    foreach($fotos as $f){
                        if(!empty($f)){
                            $listaFotos[] = asset('storage/'.$f);
                        }
                    } 
                    $reclamacoes[$chave]['fotos']= $listaFotos;
               }
               $array['lista'] = $reclamacoes;
            }else{
                $array['error'] = 'Esta unidade nao e sua.';
            }
        }else{
            $array['error'] = 'Necessario informar a propriedade';
        }
        return $array;
    }

    public function adicionarArquivo(Request $request){
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
            'fotos' => 'required|file|mimes:jpg,png'
        ]);

        if(!$validator->fails()){
            $arquivo = $request->file('fotos')->store('public');

            $array['fotos'] = asset(Storage::url($arquivo));
        }else{
            $array['error'] = $validator->errors()->first();
            return $array;
        }
        return $array;
    }

    public function fazerReclamacao(Request $request){
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
            'titulo' => 'required',
            'propriedade' => 'required'
        ]);

        if($validator->fails()){
            $array['error'] = $validator->errors()->first();
            return $array; 
        }

        $titulo = $request->input('titulo');
        $propriedade = $request->input('propriedade');
        $lista = $request->input('lista');

        $usuario = Auth::user();
        $unidade = Unidade::where('id', $propriedade)->where('id_dono', $usuario['id'])->count();

        if($unidade > 0){
            $novaReclamacao = new Reclamacao();
            $novaReclamacao->id_unidade = $propriedade;
            $novaReclamacao->titulo = $titulo;
            $novaReclamacao->status = 'in_review';
            $novaReclamacao->data_criacao = date('Y-m-d');
    
            if($lista && is_array($lista)){
                $listaFotos = [];
                foreach($lista as $item){
                    $url = explode('/', $item);
                    $listaFotos[] = end($url);
                }
                $novaReclamacao->fotos = implode(',', $listaFotos);
            }else{
                $novaReclamacao->fotos = '';
            }
            $novaReclamacao->save();
        }else{
            $array['error'] = 'Informe o numero da sua unidade para geristrar a ocorrencia.';
        }
        return $array;
    }



}
