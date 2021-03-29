<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

use App\Models\Boleto;
use App\Models\Unidade;

class BoletoController extends Controller
{
    public function buscarTodos(Request $request){
        $array = ['error' => ''];

        $propriedade = $request->input('propriedade');
        if($propriedade){
            $usuario = Auth::user();
            $unidade = Unidade::where('id', $propriedade)->where('id_dono', $usuario['id'])->count();

            if($unidade > 0){
                $boleto = Boleto::where('id_unidade', $propriedade)->get();

                foreach($boleto as $chave => $valor){
                    $boleto[$chave]['url'] = asset('storage/'. $valor['url']);
                }
    
                $array['lista'] = $boleto;
            }else{
                $array['error'] = 'Esta unidade nao e sua.';
            }
        }else{
            $array['error'] = 'A propriedade e necessaria';
        }

        return $array;
    }
}
