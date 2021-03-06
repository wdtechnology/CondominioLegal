<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('unidades')->insert([
            'nome' => 'APT 100',
            'id_dono' => '1',
        ]);
        DB::table('unidades')->insert([
            'nome' => 'APT 101',
            'id_dono' => '2',
        ]);
        DB::table('unidades')->insert([
            'nome' => 'APT 102',
            'id_dono' => '3',
        ]);
        DB::table('unidades')->insert([
            'nome' => 'APT 103',
            'id_dono' => '4',
        ]);
        DB::table('unidades')->insert([
            'nome' => 'APT 104',
            'id_dono' => '5',
        ]);

        DB::table('areas')->insert([
            'liberado' => '1',
            'titulo' => 'Piscina',
            'capa' => 'piscina.jpg',
            'dia' => '1,2,3,4,5,6,7',
            'data_abertura' => '07:00:00',
            'data_fechamento' => '23:00:00'
        ]);

        DB::table('areas')->insert([
            'liberado' => '1',
            'titulo' => 'Churrasquira',
            'capa' => 'churrasqueira.jpg',
            'dia' => '3,4,5,6,7',
            'data_abertura' => '09:00:00',
            'data_fechamento' => '23:50:00'
        ]);

        DB::table('areas')->insert([
            'liberado' => '1',
            'titulo' => 'Salao de festa 1',
            'capa' => 'salao1.jpg',
            'dia' => '1,2,3,4,5,6,7',
            'data_abertura' => '07:00:00',
            'data_fechamento' => '23:50:00'
        ]);

        DB::table('areas')->insert([
            'liberado' => '1',
            'titulo' => 'Salao de festa 2',
            'capa' => 'salao2.jpg',
            'dia' => '1,2,3,4,5,6,7',
            'data_abertura' => '07:00:00',
            'data_fechamento' => '23:50:00'
        ]);

        DB::table('areas')->insert([
            'liberado' => '1',
            'titulo' => 'Sala de jogos',
            'capa' => 'sala_jogos.jpg',
            'dia' => '1,2,3,4,5,6,7',
            'data_abertura' => '08:00:00',
            'data_fechamento' => '22:00:00'
        ]);

        DB::table('mural')->insert([
            'titulo' => 'Manuten??ao do Jardin de entrada.',
            'descricao' => 'Ser?? realizada a manuten????o do jardin da entrada do condominio.',
            'data_criacao' => '2021-02-11 10:00:00'
        ]);

        DB::table('mural')->insert([
            'titulo' => 'Manuten????o do elevador de servi??o.',
            'descricao' => 'Ser?? realizda a manuten????o do elevador de serci??o, por essa raz??o o mesmo ficara desligado at?? o  final da manuten????o.',
            'data_criacao' => '2021-03-01 08:00:00'
        ]);
        
        DB::table('mural')->insert([
            'titulo' => 'Utilize mascara',
            'descricao' => 'Com o aumento do numero de casos de Covid19 na cidade, lembramos aos moradores que ?? exigido usar mascaras nas dependencias do condominio.',
            'data_criacao' => '2021-01-01 12:00:00'
        ]); 
        
    }
}
