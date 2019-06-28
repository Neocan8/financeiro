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
    
    Route::get('transacao/{tipo}/{id}', 'ContaController@transacao')->name('contas.transacao');
    Route::post('transacao/{tipo}/{id}', 'ContaController@transacaoStore')->name('contas.transacaoStore');
    Route::get('transfer', 'ContaController@transfer')->name('contas.transfer');
    Route::get('withdraw', 'ContaController@withdraw')->name('contas.withdraw');
    
});

Route::resource('/entrada', 'EntradaController');

Route::post('/entrada/periodo', 'EntradaController@periodo')->name('entrada.periodo');

Route::get('/entrada/pagar/{id}', 'EntradaController@pagar')->name('pagar'); 
Route::get('/entrada/estornar/{id}', 'EntradaController@estornar')->name('estornar'); 
