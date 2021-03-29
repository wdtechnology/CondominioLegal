<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Mural;
use App\Models\MuralLikes;

class MuralController extends Controller
{
    public function buscarTodos(){
        $array = ['error' => '', 'lista' => []];

        $usuario = Auth::user();
        $mural = Mural::all();

        foreach($mural as $chave => $valor){
            $mural[$chave]['likes'] = 0;
            $mural[$chave]['liked'] = false;

            $likes = MuralLikes::where('id_mural', $valor['id'])->count();
            $mural[$chave]['likes'] = $likes;

            $meuLike = MuralLikes::where('id_mural', $valor['id'])->where('id_user', $usuario['id'])->count();

            if($meuLike > 0){
                $mural[$chave]['liked'] = true;
            }
        }
        $array['lista'] = $mural;
        return $array;
    }

    public function like($id){
        $array = ['error' => ''];

        $usuario = Auth::user();

        $meuLike = MuralLikes::where('id_mural', $id)->where('id_user', $usuario['id'])->count();

        if($meuLike > 0){
            MuralLikes::where('id_mural', $id)->where('id_user', $usuario['id'])->delete();
            $array['liked'] = false;
        }else{
            $novoLike = new MuralLikes();
            $novoLike->id_mural = $id;
            $novoLike->id_user = $usuario['id'];
            $novoLike->save();
            $array['liked'] = true;
        }
        $array['likes'] = MuralLikes::where('id_mural', $id)->count();
        return $array;
    }

}
