<?php

use Illuminate\Database\Seeder;

class CentrodecustosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('centrodecustos')->insert([
            'id' => 1,
            'nome' => 'Banco Itau',
            'descricao' => 'Conta padrão do sistema',
        ]);

        DB::table('centrodecustos')->insert([
            'id' => 2,
            'nome' => 'Nu Bank',
            'descricao' => 'Conta padrão do sistema',
        ]);
    }
}
