<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use App\Models\Unidade;
use App\Models\Dependente;
use App\Models\Veiculo;
use App\Models\Animal;

class UnidadeController extends Controller
{
    public function buscarInformacao($id){
        $array = ['error' => ''];

        $usuario = Auth::user();
        $unidade = Unidade::find($id);

        if(!$unidade){
            $array['error'] = 'Propriedade nao encontrada';
            return $array;
        }
        if($unidade['id_dono'] === $usuario['id']){

            $dependente = Dependente::where('id_unidade', $id)->get();
            $veiculo = Veiculo::where('id_unidade', $id)->get();
            $animal = Animal::where('id_unidade', $id)->get();
            foreach($dependente as $chave => $valor){
                $dependente[$chave]['aniversario'] = date('d/m/Y', strtotime($valor['aniversario']));
            }
            $array['dependente'] = $dependente;
            $array['veiculo'] = $veiculo;
            $array['animal'] = $animal;
        }else{
            $array['error'] = 'Entre com os dados da sua propriedade';
            return $array;
        }
        return $array;
    }

    public function adicionarDependente($id, Request $request){
        $array = ['error' => ''];
        $validator = Validator::make($request->all(), [
            'nome' => 'required',
            'aniversario' => 'required|date'
        ]);
        if(!$validator->fails()){
            $nome = $request->input('nome');
            $aniversario = $request->input('aniversario');

            $usuario = Auth::user();
            $unidadeDono = Unidade::where('id', $id)->where('id_dono', $usuario['id'])->count();

            if($unidadeDono === 0){
                $array['error'] = 'Voce so pode cadastrar dependentes na sua unidade';
                return $array;
            }

            $novoDependente = new Dependente();
            $novoDependente->id_unidade = $id;
            $novoDependente->nome = $nome;
            $novoDependente->aniversario = $aniversario;
            $novoDependente->save();
        }else{
            $array['error'] = $validator->errors()->first();
        }
        return $array;
    }

    public function adicionarVeiculo($id, Request $request){
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
            'titulo' => 'required',
            'cor' => 'required',
            'placa' => 'required'
        ]);
        if(!$validator->fails()){
            $titulo = $request->input('titulo');
            $cor = $request->input('cor');
            $placa = $request->input('placa');

            $usuario = Auth::user();
            $unidadeDono = Unidade::where('id', $id)->where('id_dono', $usuario['id'])->count();

            if($unidadeDono === 0){
                $array['error'] = 'Voce so pode cadastrar Veiculos na sua unidade';
                return $array;
            }

            $p = Veiculo::where('placa', $placa)->count();
            if($p > 0){
                $array['error'] = 'Ja existe um carro cadastrado com  a placa ' . $placa;
                return $array;
            }

            $novoVeiculo = new Veiculo();
            $novoVeiculo->id_unidade = $id;
            $novoVeiculo->titulo = $titulo;
            $novoVeiculo->cor = $cor;
            $novoVeiculo->placa = $placa;
            $novoVeiculo->save();
        }else{
            $array['error'] = $validator->errors()->first();
        }
        return $array;
    }

    public function adicionarAnimal($id, Request $request){
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
            'nome' => 'required',
            'raca' => 'required',
        ]);
        if(!$validator->fails()){
            $nome = $request->input('nome');
            $raca = $request->input('raca');

            $usuario = Auth::user();
            $unidadeDono = Unidade::where('id', $id)->where('id_dono', $usuario['id'])->count();

            if($unidadeDono === 0){
                $array['error'] = 'Voce so pode cadastrar Animais na sua unidade';
                return $array;
            }
           
            $novoAnimal = new Animal();
            $novoAnimal->id_unidade = $id;
            $novoAnimal->nome = $nome;
            $novoAnimal->raca = $raca;
            $novoAnimal->save();
        }else{
            $array['error'] = $validator->errors()->first();
        }
        return $array;
    }

    public function removerDependente($id, Request $request){
        $array = ['error' => ''];
        $idPessoa = $request->input('id');

        $usuario = Auth::user();
        $unidadeDono = Unidade::where('id', $id)->where('id_dono', $usuario['id'])->count();

        if($unidadeDono === 0){
            $array['error'] = 'Voce so pode remover dependentes da sua unidade';
            return $array;
        }

        if($idPessoa){
            Dependente::where('id', $idPessoa)->where('id_unidade', $id)->delete();
        }else{
            $array['error'] = 'ID inexistente';
            return $array;
        }
        return $array;
    }

    public function removerVeiculo($id, Request $request){
        $array = ['error' => ''];
        $idVeiculo= $request->input('id');

        $usuario = Auth::user();
        $unidadeDono = Unidade::where('id', $id)->where('id_dono', $usuario['id'])->count();

        if($unidadeDono === 0){
            $array['error'] = 'Voce so pode remover veiculos da sua unidade';
            return $array;
        }

        if($idVeiculo){
            Veiculo::where('id', $idVeiculo)->where('id_unidade', $id)->delete();
        }else{
            $array['error'] = 'ID inexistente';
            return $array;
        }
        return $array;
    }

    public function removerAnimal($id, Request $request){
        $array = ['error' => ''];
        $idPet= $request->input('id');

        $usuario = Auth::user();
        $unidadeDono = Unidade::where('id', $id)->where('id_dono', $usuario['id'])->count();

        if($unidadeDono === 0){
            $array['error'] = 'Voce so pode remover animais da sua unidade';
            return $array;
        }

        if($idPet){
            Animal::where('id', $idPet)->where('id_unidade', $id)->delete();
        }else{
            $array['error'] = 'ID inexistente';
            return $array;
        } 
        return $array;
    }




}
