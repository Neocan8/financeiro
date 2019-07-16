<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Model\Entrada;
use App\Model\Conta;
use App\Model\Categoria;

class EntradaController extends Controller
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


        $contasPagas = DB::table('entradas')
        ->where([
            ['confirmado', true],
            ['data', '>=', $dataIni],
            ['data', '<=', $dataFim],
            ])->get();
            
            $contasAPagar = DB::table('entradas')
            ->where([
            ['confirmado', false],
            ['data', '>=', $dataIni],
            ['data', '<=', $dataFim],
            ])->get();

            //dd($contasAPagar);
            //dd($contasAPagar->sum('valor'));

        // Mostra os somatórios nos rodapés titulos etc.
        $dadosPagina = [
            'titulo'            => 'Entradas',
            'subtituloEsquerda' => 'Entradas a Receber',
            'subtituloDireita'  => 'Entradas Recebidas',
            'caminho'           => 'Entradas',
            'totalAPagar'       => $contasAPagar->sum('valor'),
            'totalPagas'        => $contasPagas->sum('valor'),
            'restaPagar'        => $contasAPagar->sum('valor') + $contasPagas->sum('valor') ,
            'dataIni'           => $dataIni,
            'dataFim'           => $dataFim,
            'resta'             => ' receber',
            'alert-rodape'      => 'alert-success',
            'rota'              => 'entrada.',
            'rotaPeriodo'       => 'entrada.periodo'
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
            'titulo' => 'Nova Entrada',
            'caminho' => 'Entradas',
            'caminhoUrl' => route('entrada.index'),
            'subtituloEsquerda' => 'Entradas a Receber',
            'subtituloDireita' => 'Entradas Recebidas',
            'data' => date('Y-m-d'),
            'rota' => 'entrada.'
        ];
        
        $contas = Conta::all();
        $categorias = Categoria::where('tipo','E')->get();
        return view('transacoes.io-create', compact('dadosPagina','contas','categorias'));
    }
    
    public function rapida()
    {
        // Mostra os somatórios nos rodapés titulos etc.
        $dadosPagina = [
            'titulo'            => 'Nova Entrada',
            'caminho'           => 'Entradas',
            'caminhoUrl'        => route('entrada.index'),
            'subtituloEsquerda' => 'Entradas a Receber',
            'subtituloDireita'  => 'Entradas Recebidas',
            'data'              => date('Y-m-d'),
            'rota'              => 'entrada.'
        ];
        
        $contas = Conta::all();
        $categorias = Categoria::where('tipo','E')->get();
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
        
        //dd($input);
        $input['id_referencia'] = Conta::idReferencia();
        $novaEntrada = Entrada::create($input);

        
        if($novaEntrada) {
            Conta::mensagem('success', 'Entrada Criada!');
            // SE A ENTRADA JÁ FOR CONFIRMADA JÁ SOMA NO SALDO
            if($novaEntrada->confirmado == true){
                Conta::deposito($novaEntrada->conta_id,$novaEntrada->valor);
            }
        } else {
            Conta::mensagem('danger', 'Houve um erro ao salvar entrada');
        }

        return redirect('/entrada');
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
        $dados = Entrada::findOrfail($id);
        $outrasParcelas =  Entrada::where('id_referencia', $dados->id_referencia)->get(); 
        // Mostra os somatórios nos rodapés titulos etc.
        $dadosPagina = [
            'titulo'            => 'Editar Entrada',
            'caminho'           => 'Entrada',
            'caminhoUrl'        => route('entrada.index'),
            'rota'              => 'entrada.'
        ];

        $contas = Conta::withTrashed()->get();
        $categorias = Categoria::where('tipo','E')->get();

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
        $entrada = Entrada::find($id);
        if(!$entrada){
            Conta::mensagem('danger', 'Entrada não encontrada!');
            return redirect( route('entrada.edit', $id));
        }

        //VERIFICANDO SE A CONTA EXISTE OU ESTÁ DESATIVADA

        if(!Conta::find($request->input('conta_id'))){
            Conta::mensagem('danger', 'A conta selecionada foi Excluída, por favor escolha outra conta para essa entrada.');
            return redirect( route('entrada.edit', $id));
        }
        
        $dados = $request->all();
        // NÃO PERMITE UPDATE EM UMA ENTRADA JÁ CONFIRMADA
        // SEM ESSA TRAVA O USÁRIO PODE MUDAR O VALRO DA CONTA
        // E BANGUNÇAR O SALDO.

        if($entrada->confirmado == true){
            Conta::mensagem('danger', 'Para Editar uma entrada é necessário primeiro extorná-la!');
            return redirect( route('entrada.edit', $id));
        }
        $entrada->update($dados);
        // ATUALIZANDO O SALDO DA CONTA
        if($entrada->confirmado == true){
            Conta::deposito($entrada->conta_id,$entrada->valor);
        }

        Conta::mensagem('success', 'Entrada Atualizada!');
        return redirect( route('entrada.edit', $id));
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
        $entrada = Entrada::find($id);
        
        // VERIFICANDO SE A CONTA ESTÁ ATIVA
        if(!Conta::contaAtiva($entrada->conta_id))
            return redirect()->back()->withInput();

        if($entrada){
            $entrada->confirmado = true;
            $entrada->save();
            // atualiza o saldo da conta
            Conta::deposito($entrada->conta_id,$entrada->valor);
            Conta::mensagem('success', 'Entrada recebida com sucesso!');
        } else {
            Conta::mensagem('danger', 'Entrada não encontrada!');
        }
        return redirect()->back();
        
    }

    // VERIFICANDO SE A CONTA ESTÁ ATIVA
    function estornar($id) {
        $entrada = Entrada::find($id);

        if(!Conta::contaAtiva($entrada->conta_id))
            return redirect()->back()->withInput();

        if($entrada){
            $entrada->confirmado = false;
            $entrada->save();
            // atualiza o saldo da conta
            Conta::saque($entrada->conta_id,$entrada->valor);
           Conta::mensagem('success', 'Entrada estornada!');
        } else {
            Conta::mensagem('danger', 'Entrada não encontrada!');
        }
        return redirect()->back();
        
    }
  
  
}