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
            Conta::mensagem('success', 'Nova Conta Criada!'); 
            return redirect( route('conta.index'));
        } else {
            Conta::mensagem('danger', 'Houve um erro ao salvar a conta o Centro de Custo não foi encontrado!'); 
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
        dd('Metodo show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function transacao(Request $request)
    {
        $tipo = $request->input('tipo');
        $id = $request->input('id');

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

    public function transacaoStore(Request $request)
    {

        $tipo = $request->input('tipo');
        $id = $request->input('id');

        

        // Recebe o id, via get, e o post do formulário com o valor
        $conta = Conta::find($id);
        //dd($conta);
        if ($conta) {
            
            if ($tipo == 'depositar') {
               Conta::deposito($conta->id, $request->input('valor')) ;
                Log::info('Incrementado Saldo');

            } elseif ($tipo == 'sacar') {
                Conta::saque($conta->id, $request->input('valor')) ;
                Log::info('Decrementado saldo');

            } else {
                Log::alert('Erro ao efetuar transação tipo não identificado: ' . $tipo);
                return  redirect()->back();
            }

            $conta->save();
            return redirect(route('conta.index'));

        } else {
            return redirect()->back();
        }
    }

    
    public function edit($id)
    {
        $conta = Conta::find($id);
        $centrodecustos = Centrodecusto::all();
        return view('contas.editar', compact('conta','centrodecustos'));

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
        $conta = Conta::find($id);
        if($conta){
            $input = $request->all();
            $conta->update($input);
            Log::debug('Conta Atualizada Usuario Autenticado: ' . auth()->user()->name . ' - ' . json_encode($input));
            Conta::mensagem('success', 'Conta atualizada com sucesso');
            return redirect()->back();
        }

        Log::debug('Erro ao Atualizar conta Usuario Autenticado: ' . auth()->user()->name . ' - ' . json_encode($input));
        Conta::mensagem('danger', 'Erro ao atualizar conta');
        return redirect()->back();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $conta = Conta::find($id);
        if(!$conta){
            Conta::mensagem('danger','Conta não encontrada');
            return redirect()->back();
        }


        if($conta->delete()) {
            Conta::mensagem('success', 'Conta excluída com sucesso, as entradas e saídas vinculadas a ela deveram ser migradas pelo usuário no momento do pagamento ou recebimento!');
            return redirect(route('conta.index'));
        }
       
    }
}
