<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Conta;
use App\Model\Historico;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransferenciaController extends Controller
{
    use SoftDeletes;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         // Mostra os somatórios nos rodapés titulos etc.
         $dadosPagina = [
            'titulo' => 'Nova Transferência',
            'caminho' => 'Transferências',
            'caminhoUrl' => route('transferencia.index'),
            'data' => date('Y-m-d'),
            'rota' => 'transferencia.'
        ];
        
        $contas = Conta::all();
        $transferencias = Historico::where('tipo','T')->paginate(10);
        return view('transacoes.transferencia', compact('contas','dadosPagina','transferencias'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->flash();
        $input = $request->all();

        if($input['conta_id_origem'] == $input['conta_id_destino'] ){
            Conta::mensagem('danger', 'A conta de Origem deve ser diferente da conta de Destino');
            return redirect()->back( );
        }

        $contaOrigem = Conta::find($input['conta_id_origem']);
        $contaDestino = Conta::find($input['conta_id_destino']);


        // ajustando o novo saldo das contas
        Conta::saque($contaOrigem->id, $input['valor']);
        Conta::deposito($contaDestino->id, $input['valor']);

        // registrando na conta de origem a saida da transferencia
        Historico::create([
            'descricao' =>  $input['obs'] .' - ' . $contaDestino->nome,
            'tipo'      =>  'T',
            'valor'     =>  $input['valor'] *-1,
            'conta_id'  =>  $contaOrigem->id 
        ]);

        // registrando na conta de destino a entrada da transferencia
        Historico::create([
            'descricao' =>  $input['obs'] .' - ' . $contaOrigem->nome,
            'tipo'      =>  'T',
            'valor'     =>  $input['valor'],
            'conta_id'  =>  $contaDestino->id
        ]);
        
        Conta::mensagem('success', 'Transferência realizada com sucesso');
        return redirect(route("transferencia.index"));
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
}