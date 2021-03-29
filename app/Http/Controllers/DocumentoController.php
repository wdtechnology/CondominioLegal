<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\Documento;

class DocumentoController extends Controller
{
    public function buscarTodos(){
        $array = ['error' => ''];

        $documento = Documento::all();

        foreach($documento as $chave => $valor){
            $documento[$chave]['url'] = asset('storage/'.$valor['url']);
        }

        $array['lista'] = $documento;

        return $array;
    }
}
