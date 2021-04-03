<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AchadoPerdidoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BoletoController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\MuralController;
use App\Http\Controllers\ReclamacaoController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\UnidadeController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/testando', function () {
    return "Mais uma tentativa";
});

Route::get('/401', [AuthController::class, 'unauthorized'])->name('login');

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/registrar', [AuthController::class, 'registrar']);

Route::middleware('auth:api')->group(function () {
    Route::post('/auth/validate', [AuthController::class, 'validateToken']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    //Mural
    Route::get('/mural', [MuralController::class, 'buscarTodos']);
    Route::get('/mural/{id}/like', [MuralController::class, 'like']);

    //Documentos
    Route::get('/documentos', [DocumentoController::class, 'buscarTodos']);

    //Reclamações
    Route::get('/reclamacoes', [ReclamacaoController::class, 'minhasReclamacoes'] );
    Route::post('/reclamacao', [ReclamacaoController::class, 'fazerReclamacao']);
    Route::post('/reclamacao/arquivo', [ReclamacaoController::class, 'adicionarArquivo']);

    //Boletos
    Route::get('/boletos', [BoletoController::class, 'buscarTodos']);

    //Achados e Perdidos
    Route::get('/achadosEperdidos', [AchadoPerdidoController::class, 'buscarTodos'] );
    Route::post('/achadosEperdidos', [AchadoPerdidoController::class, 'inserir'] );
    Route::put('/achadosEperdidos/{id}', [AchadoPerdidoController::class, 'atualizar']);

    //Unidade
    Route::get('/unidade/{id}', [UnidadeController::class, 'buscarInformacao']);
    Route::post('/unidade/{id}/adicionardependente', [UnidadeController::class, 'adicionarDependente']);
    Route::post('/unidade/{id}/adicionarveiculo', [UnidadeController::class, 'adicionarVeiculo']);
    Route::post('/unidade/{id}/adicionaranimal', [UnidadeController::class, 'adicionarAnimal']);
    Route::post('/unidade/{id}/removerdependente', [UnidadeController::class, 'removerDependente']);
    Route::post('/unidade/{id}/removerveiculo', [UnidadeController::class, 'removerVeiculo']);
    Route::post('/unidade/{id}/removeranimal', [UnidadeController::class, 'removerAnimal']);

    //Reservas
    Route::get('/reservas', [ReservaController::class, 'buscarReservas']);
    Route::post('/reserva/{id}', [ReservaController::class, 'fazerReserva']);
    Route::get('/reserva/{id}/diasfechado', [ReservaController::class, 'buscarDatasFechado']);
    Route::get('/reserva/{id}/horas', [ReservaController::class, 'buscarHoraReservadas']);
    Route::get('/minhasreservas', [ReservaController::class, 'minhasReservas']);
    Route::delete('/minhasreservas/{id}', [ReservaController::class, 'deletarReserva']);
   
});
