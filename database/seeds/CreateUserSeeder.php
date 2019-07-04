<?php

use Illuminate\Database\Seeder;

class CreateUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Administrador Padrão',
            'email' => 'adminstrador@adm.com',
            'password' => bcrypt('sistema'),
        ]);

        DB::table('users')->insert([
            'name' => 'Felipe',
            'email' => 'felipe@costacandido.com.br',
            'password' => bcrypt('080887'),
        ]);
    }
}
