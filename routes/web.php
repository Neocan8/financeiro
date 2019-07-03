<?php

use App\Model\Centrodecusto;
use App\Model\Conta;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => 'conta'], function () {
    
    Route::resource('', 'ContaController', ['names' => [
        'create' => 'conta.create',
        'index' => 'conta.index'
        ]]);
    
    Route::get('transacao', 'ContaController@transacao')->name('conta.transacao');
    Route::any('transacao/store', 'ContaController@transacaoStore')->name('conta.transacaoStore');
    Route::get('transferir', 'ContaController@transferir')->name('conta.transferir');
    Route::get('withdraw', 'ContaController@withdraw')->name('conta.withdraw');
    
});
//  ENTRADA
Route::resource('/entrada', 'EntradaController');
Route::post('/entrada/periodo', 'EntradaController@periodo')->name('entrada.periodo');
Route::get('/entrada/pagar/{id}', 'EntradaController@pagar')->name('entrada.pagar'); 
Route::get('/entrada/estornar/{id}', 'EntradaController@estornar')->name('entrada.estornar'); 

//SAIDA 

Route::resource('/saida', 'saidaController', ['names' => [
    'index' => 'saida.index'
]]);

Route::post('/saida/periodo', 'saidaController@periodo')->name('saida.periodo');
Route::get('/saida/pagar/{id}', 'saidaController@pagar')->name('saida.pagar'); 
Route::get('/saida/estornar/{id}', 'saidaController@estornar')->name('saida.estornar'); 

//TRANSFERENCIA

Route::get('/transferencia', 'TransferenciaController@index')->name('transferencia.index');
Route::post('/transferencia', 'TransferenciaController@store')->name('transferencia.store');