<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Conta;
use App\Model\Centrodecusto;
use Illuminate\Support\Facades\Log;

class ContaController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$contas = Conta::all();
        $centrodecustos = Centrodecusto::all();
        $amount = 100;

        return view('contas.index', compact('centrodecustos','amount'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $centrodecustos = Centrodecusto::all();
        return view('contas.criar', compact('centrodecustos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Centrodecusto::find($request->input('centrodecusto_id'))) {
            Conta::create($request->all());
            return redirect( route('contas.index'));
        } else {
            echo "Desculpe, Centro de Custo não encontrado";
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function transacao($tipo, $id)
    {
        $conta = Conta::find($id);
        if ($tipo == 'depositar') {
            $config = [
                'titulo' => 'Depositar',
                'caminho' => 'depositar'
            ];
        } else {
            $config = [
                'titulo' => 'Sacar',
                'caminho' => 'sacar'
            ];
        }

        return view('contas.transacao', compact('conta', 'config'));
    }

    public function transacaoStore($tipo, $id, Request $request)
    {
        // Recebe o id, via get, e o post do formulário com o valor
        $conta = Conta::find($id);
        if ($conta) {
            
            if ($tipo == 'depositar') {
                $conta->saldo = $conta->saldo + $request->input('deposito');
                Log::info('Incrementado Saldo');

            } elseif ($tipo == 'sacar') {
                $conta->saldo = $conta->saldo - $request->input('deposito');
                Log::info('Decrementado saldo');

            } else {
                Log::alert('Erro ao efetuar transação tipo não identificado: ' . $tipo);
                return  redirect()->back();
            }

            $conta->save();
            return redirect('/contas');

        } else {
            return redirect()->back();
        }
    }

    
    public function edit($id)
    {
        //
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
        //
    }

    public function deposito(Request $request)
    {
        dd($request->all());
    }
    public function entradas()
    {
        # code...
    }

    public function saidas()
    {
        # code...
    }
}
