<?php

use App\Model\Centrodecusto;
use App\Model\Conta;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => 'contas'], function () {
    
    Route::resource('', 'ContaController', ['names' => [
        'create' => 'contas.create',
        'index' => 'contas.index'
        ]]);
    Route::get('entradas', 'ContaController@entradas', ['name' => 'contas.entradas']);
    Route::get('saidas', 'ContaController@saidas')->name('contas.saidas');
    
    Route::get('transacao/{tipo}/{id}', 'ContaController@transacao')->name('contas.transacao');
    Route::post('transacao/{tipo}/{id}', 'ContaController@transacaoStore')->name('contas.transacaoStore');
    Route::get('transfer', 'ContaController@transfer')->name('contas.transfer');
    Route::get('withdraw', 'ContaController@withdraw')->name('contas.withdraw');
    
});



// Route::get('centrodecustos', function () {
//     $centrodecusto = Centrodecusto::all();

//     foreach ($centrodecusto as $c) {
//         echo $c->nome;
//         echo "<br>";
//         foreach ($c->conta as $conta) {
//             echo $conta->nome . "<br>";
//         }
//         echo "<br>";
//     }
// });