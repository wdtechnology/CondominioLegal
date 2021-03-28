<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Unidade;

class AuthController extends Controller
{
    public function unauthorized(){
        return response()->json([
            'error' => 'Não Autorizado!'
        ],401);
    }

    public function registrar(Request $request){
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
            'nome' => 'required',
            'email' => 'required|email|unique:users,email',
            'cpf' => 'required|digits:11|unique:users,cpf',
            'password' => 'required',
            'confirma_password' => 'required|same:password'
        ]);

        if(!$validator->fails()){
            $nome = $request->input('nome');
            $email = $request->input('email');
            $cpf = $request->input('cpf');
            $password = $request->input('password');
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $newUser = new User();
            $newUser->nome = $nome;
            $newUser->email = $email;
            $newUser->cpf = $cpf;
            $newUser->password = $hash;
            $newUser->save();

            $token = Auth::attempt(['cpf' => $cpf, 'password' => $password]);

            if(!$token){
                $array['error'] = 'Ocorreu um erro';
                return $array;
            }

            $array['token'] = $token;
            $usuario = Auth::user();
            $array['usuario'] = $usuario;

            $propriedades = Unidade::select(['id', 'nome'])->where('id_dono', $usuario['id'])->get();
            $array['usuario']['propriedades'] = $propriedades;

            
        }else{
            $array['error'] = $validator->errors()->first();
            return $array;
        }
        return $array;
    }

    public function login(Request $request){
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
            'cpf' => 'required|digits:11',
            'password' => 'required'
        ]);

        if(!$validator->fails()){
            $cpf = $request->input('cpf');
            $password = $request->input('password');

            $token = Auth::attempt(['cpf' => $cpf, 'password' => $password]);

            if(!$token){
                $array['error'] = 'CPF ou Senha estao errados.';
                return $array;
            }

            $array['token'] = $token;
            $usuario = Auth::user();
            $array['usuario'] = $usuario;

            $propriedades = Unidade::select(['id', 'nome'])->where('id_dono', $usuario['id'])->get();
            $array['user']['propriedades'] = $propriedades;

        }else{
            $array['error'] = $validator->errors()->first();
            return $array;
        }
        return $array;
    }

    public function validateToken(){
        $array = ['error' => ''];

        $usuario = Auth::user();
        $array['usuario'] = $usuario;

        $propriedades = Unidade::select(['id', 'nome'])->where('id_dono', $usuario['id'])->get();
        $array['usuario']['propriedades'] = $propriedades;
        return $array;
    }

    public function logout(){
        $array = ['error' => ''];
        Auth::logout();
        return $array;
    }


}
