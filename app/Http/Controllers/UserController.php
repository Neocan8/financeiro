<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Model\Conta;
use Log;
//use Illuminate\Database\Eloquent\SoftDeletes;

class UserController extends Controller
{
    //use SoftDeletes;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lista = User::all();
        $user = [
            'name'  => '',
            'email'  => '',
        ];

        $dadosPagina = [
            'titulo'    => 'Usuários',
            'rota'      => 'user.',
        ];
        return view('usuarios.index', compact('lista','dadosPagina','user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:4', 'confirmed'],
            ]);
            
        $request = $request->all();

        $criado =  User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);

        if($criado){
            Conta::mensagem('success', 'Usuário Criado!');
        } else {
            Conta::mensagem('danger', 'Erro ao Criar usuário!');
        }

        return  redirect(route('user.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        echo "Metodo show";
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('usuarios.update');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($id == 1) {
            Conta::mensagem('danger','Desculpe o usuário de ID 1, não pode ser removido, Você pode renomeá-lo e mudar sua senha.');
            return redirect()->back();
        }
        $usuario = User::findOrFail($id);
        $usuario->delete();
        Conta::mensagem("success",'Usuário Excluído');
        return redirect()->back();
    }
}
