<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use App\Models\AchadoPerdido;

class AchadoPerdidoController extends Controller
{
    public function buscarTodos(){
        $array = ['error' => ''];

        $perdidos = AchadoPerdido::where('status', 'perdido')->orderBy('data_criacao', 'DESC')->orderBy('id', 'DESC')->get();

        $recuperados = AchadoPerdido::where('status', 'recuperado')->orderBy('data_criacao', 'DESC')->orderBy('id', 'DESC')->get();

        foreach($perdidos as $chave => $valor){
            $perdidos[$chave]['data_criacao'] = date('d/m/Y', strtotime($valor['data_criacao']));
            $perdidos[$chave]['foto'] = asset('storage/'. $valor['foto']);
        }

        foreach($recuperados as $chave => $valor){
            $lost[$chave]['data_criacao'] = date('d/m/Y', strtotime($valor['data_criacao']));
            $lost[$chave]['foto'] = asset('storage/'. $valor['foto']);
        }
        $array['perdido'] = $perdidos;
        $array['recuperado'] = $recuperados;
        return $array;
    }

    public function inserir(Request $request){
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
            'descricao' => 'required',
            'local' => 'required',
            'foto' => 'required|file|mimes:jpg,png'
        ]);

        if(!$validator->fails()){
            $descricao = $request->input('descricao');
            $local = $request->input('local');
            $arquivo = $request->file('foto')->store('public');
            $arquvio = explode('public/', $arquivo);
            $foto = $arquvio[1];

            $novoPerido = new AchadoPerdido();
            $novoPerido->descricao = $descricao;
            $novoPerido->status = 'perdido';
            $novoPerido->foto = $foto;
            $novoPerido->local = $local;
            $novoPerido->data_criacao = date('Y-m-d');
            $novoPerido->save();
        }else{
            $array['error'] = $validator->errors()->first();
            return $array;
        }
        return $array;
    }

    public function atualizar($id, Request $request){
        $array = ['error' => ''];

        $status = $request->input('status');
        
        if($status && in_array($status, ['perdido', 'recuperado'])){
            $item = AchadoPerdido::find($id);
            if($item){
                $item->status = $status;
                $item->save();
            }else{
                $array['error'] = 'Não foi encontrado nenhum item';
                return $array;
            }
        }else{
            $array['error'] = 'Status não existe';
            return $array;
        }
        return $array;
    }


    
}
