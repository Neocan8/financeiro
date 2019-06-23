<?php

use Illuminate\Database\Seeder;

class contasseeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('contas')->insert([
            'centrodecusto_id' => 1, // conta corrente
            'nome' => 'Conta Corrente',
            'saldo' => 0
        ]);
        DB::table('contas')->insert([
            'centrodecusto_id' => 1, // conta corrente
            'nome' => 'Conta Poupança',
            'saldo' => 0
        ]);
    }
}
