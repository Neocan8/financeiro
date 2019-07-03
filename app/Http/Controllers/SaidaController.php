<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Model\Saida;
use App\Model\Conta;
use App\Model\Categoria;
use Log;

class SaidaController extends Controller
{
    protected $dataIni;
    protected $dataFim;

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function periodo(Request $request)
    {
        if ($request->input('dataIni')) {
         $this->dataIni =  $request->input('dataIni');
         $this->dataFim =  $request->input('dataFim');
        }
        return $this->index();

    }

    public function index()
    {
      
        
        $dataIni = $this->dataIni ? $this->dataIni :  date('Y-m-01');
        $dataFim = $this->dataFim ? $this->dataFim :  date('Y-m-t');


        $contasPagas = DB::table('saidas')
        ->where([
            ['confirmado', true],
            ['data', '>=', $dataIni],
            ['data', '<=', $dataFim],
            ])->get();
            
            $contasAPagar = DB::table('saidas')
            ->where([
            ['confirmado', false],
            ['data', '>=', $dataIni],
            ['data', '<=', $dataFim],
            ])->get();

            //dd($contasAPagar);
            //dd($contasAPagar->sum('valor'));

        // Mostra os somatórios nos rodapés titulos etc.
        $dadosPagina = [
            'titulo' => 'Saídas',
            'subtituloEsquerda' => 'Saídas a Pagar',
            'subtituloDireita' => 'Saídas Pagas',
            'caminho' => 'saídas',
            'totalAPagar' => $contasAPagar->sum('valor'),
            'totalPagas' => $contasPagas->sum('valor'),
            'restaPagar' => $contasAPagar->sum('valor') + $contasPagas->sum('valor') ,
            'dataIni' =>$dataIni,
            'dataFim' =>$dataFim,
            'resta' => 'pagar',
            'alert-rodape' => 'alert-danger',
            'rota' => 'saida.'
        ];
        
        
        return view("transacoes.io", compact('contasAPagar', 'contasPagas','dadosPagina'));
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Mostra os somatórios nos rodapés titulos etc.
        $dadosPagina = [
            'titulo' => 'Nova Saída',
            'subtituloEsquerda' => 'saídas a Receber',
            'subtituloDireita' => 'saídas Recebidas',
            'caminho' => 'saídas',
            'caminhoUrl' => route('saida.index'),
            'rota' => 'saida.',
            'data' => date('Y-m-d')
        ];
        
            $contas = Conta::all();
            $categorias = Categoria::where('tipo','S')->get();
        return view('transacoes.io-create', compact('dadosPagina','contas','categorias'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        
        
        
        $input['id_referencia'] = Conta::idReferencia();
        $totalParcelas = $input['parcela'];

        for ($i=0; $i < $totalParcelas ; $i++) { 
            
            $input['parcela'] = $i + 1;
            $novaSaida = Saida::create($input);
            if($novaSaida) {
                Conta::mensagem('success', 'Saída Criada!');

                // CASO A SAIDA JÁ TENHA SIDO PAGA, BAIXA NO SALDO DA CONTA
                if($novaSaida->confirmado == true){
                    Conta::saque($novaSaida->conta_id,$novaSaida->valor);
                }
                
            } else {
                Conta::mensagem('danger', 'Houve um erro ao salvar saída');
            }

            
            //incrementando a data
            $input['data'] = Conta::incrementaMes($input['data']);
        }
            
        return redirect(route('saida.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      die('Entrou no Método Show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $dados = Saida::findOrfail($id);
        $outrasParcelas =  Saida::where('id_referencia', $dados->id_referencia)->get(); 
        // Mostra os somatórios nos rodapés titulos etc.
        $dadosPagina = [
            'titulo' => 'Editar Saída',
            'caminho' => 'Saída',
            'caminhoUrl' => route('saida.index'),
            'rota'      => 'saida.'
        ];

        $contas = Conta::all();
        $categorias = Categoria::where('tipo','S')->get();

       return view('transacoes.io-update', compact('dados','dadosPagina','contas','categorias', 'outrasParcelas'));
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
        $saida = Saida::find($id);
        if(!$saida){
            Conta::mensagem('danger', 'Saída não encontrada!');
            return redirect( route('saida.edit', $id));
        }
        
        $dados = $request->all();
        // $dados['confirmado'] == true;

        // NÃO PERMITE UPDATE EM UMA SAIDA JÁ CONFIRMADA
        // SEM ESSA TRAVA O USÁRIO PODE MUDAR O VALRO DA CONTA
        // E BANGUNÇAR O SALDO.

        // if($dados['confirmado'] == true){
        //     Conta::mensagem('danger', 'Para Editar uma saida é necessário primeiro extorná-la!');
        //     return redirect( route('saida.edit', $id));
        // }
        
        $saida->update($dados);
        
        // ATUALIZANDO O SALDO DA CONTA
        if($saida->confirmado == true){
            Conta::saque($saida->conta_id,$saida->valor);
        }

        Conta::mensagem('success', 'Saída Atualizada!');
        return redirect( route('saida.edit', $id));
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

    function pagar($id) {
        $saida = Saida::find($id);
        if($saida){
            $saida->confirmado = true;
            $saida->save();
            Conta::saque($saida->conta_id,$saida->valor);
            Conta::mensagem('success', 'Saída recebida com sucesso!');
        } else {
            Conta::mensagem('danger', 'Saída não encontrada!');
        }
        return redirect()->back();
        
    }
    
    function estornar($id) {
        $saida = Saida::find($id);
        if($saida){
            $saida->confirmado = false;
            $saida->save();
            Conta::deposito($saida->conta_id,$saida->valor);
            Conta::mensagem('success', 'Saída estornada!');
        } else {
            Conta::mensagem('danger', 'Saída não encontrada!');
        }
        return redirect()->back();
        
    }
    public function mensagem($tipo,$texto)
    {
        session()->flash('alert', ['type' => $tipo, 'message' => $texto]);
    }
  
}