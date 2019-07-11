<?php
use Illuminate\Support\Facades\Mail;
use App\Model\Centrodecusto;
use App\Mail\EnviaAvisoDev;
use App\Mail\SendMailUser;
use App\Model\Conta;

Auth::routes();

Route::get('/', function () {
    return redirect('/login');
    //return view('welcome');
});


Route::get('/home', 'HomeController@index')->name('home');

Route::resource('/centrodecusto', 'CentrodecustoController');

Route::group(['prefix' => 'conta'], function () {
    Route::get('transacao', 'ContaController@transacao')->name('conta.transacao');
    Route::any('transacao/store', 'ContaController@transacaoStore')->name('conta.transacaoStore');
});

Route::resource('/conta', 'ContaController');

//  ENTRADA
Route::resource('/entrada', 'EntradaController');
Route::post('/entrada/periodo', 'EntradaController@periodo')->name('entrada.periodo');
Route::get('/entrada/pagar/{id}', 'EntradaController@pagar')->name('entrada.pagar'); 
Route::get('/entrada/estornar/{id}', 'EntradaController@estornar')->name('entrada.estornar'); 
Route::get('/entrada/rapida', 'EntradaController@rapida')->name('entrada.rapida'); 

//SAIDA 

Route::resource('/saida', 'SaidaController', ['names' => [
    'index' => 'saida.index'
]]);

Route::post('/saida/periodo', 'saidaController@periodo')->name('saida.periodo');
Route::get('/saida/rapida', 'saidaController@rapida')->name('saida.rapida'); 
Route::get('/saida/pagar/{id}', 'saidaController@pagar')->name('saida.pagar'); 
Route::get('/saida/estornar/{id}', 'saidaController@estornar')->name('saida.estornar'); 

//TRANSFERENCIA

Route::get('/transferencia', 'TransferenciaController@index')->name('transferencia.index');
Route::post('/transferencia', 'TransferenciaController@store')->name('transferencia.store');

//USUARIOS
Route::resource('user', 'UserController');
Route::post('user/delete', 'UserController@destroy')->name('user.deletar');

//CATEGORIAS

Route::resource('categoria', 'CategoriaController');


Route::get('/mail', function () {
    
    Mail::to('felipe.candido8@gmail.com')->send(new EnviaAvisoDev('Testando o passe de variável'));
    
});