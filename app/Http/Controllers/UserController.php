<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Unidade;

class UserController extends Controller
{
    public function buscarPerfil(){
        $array = ['error' => '', 'dados' => [], "unidades" => []];
        

        $user = Auth::user();
        $unit = Unidade::where('id_dono', $user['id'])->get();
      
        if(!$user){
            $array['error'] = 'Erro ao buscar dados, realize o login novamente!';
        }else{
            $array['dados'] = [
                "id_usuario" => $user['id'],
                "nome" => $user['nome'],
                "cpf" => $user['cpf'],
                "email" => $user['email']
            ];
            $array['unidades'] =  $unit;
        }
        return $array;
    }

    public function alterarEmail($id, Request $request){
        $array = ['error' => '', 'valida' => ''];

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email'
        ]);

        if(!$validator->fails()){
            $user = Auth::user();
            if($user['id'] != $id){
                $array['error'] = 'Ocorreu um problema, relogue no sistema.';
                return $array;
            }
            $email = $request->input('email');
            User::where('id', $id)->update(['email'=> $email]);
            $array['valida'] = 'Os dados foram alterados com sucesso';
        }else{
            $array['error'] = $validator->errors()->first();
            return $array;
        }
        return $array; 
    }

    public function alterarSenha($id, Request $request){
        $array = ['error' => '', 'valida' => ''];

        $validator = Validator::make($request->all(), [
            'nova_senha' => 'required',
            'confirma_senha' => 'required|same:nova_senha'
        ]);

        if(!$validator->fails()){
            $user = Auth::user();
            if($user['id'] != $id){
                $array['error'] = 'Ocorreu um problema, relogue no sistema.';
                return $array;
            }
            $senha = $request->input('nova_senha');
            $hash = password_hash($senha, PASSWORD_DEFAULT);
            User::where('id', $id)->update(['password'=> $hash]);
            $array['valida'] = 'Os dados foram alterados com sucesso';
        }else{
            $array['error'] = $validator->errors()->first();
            return $array;
        }
        return $array; 
    }
}
