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
            ['confirmado', '1'],
            ['data', '>=', $dataIni],
            ['data', '<=', $dataFim],
            ])->get();
            
            $contasAPagar = DB::table('entradas')
            ->where([
            ['confirmado', false],
            ['data', '>=', $dataIni],
            ['data', '<=', $dataFim],
            ])->get();

        // Mostra os somatórios nos rodapés titulos etc.
        $dadosPagina = [
            'titulo' => 'Entradas',
            'subtituloEsquerda' => 'Entradas a Receber',
            'subtituloDireita' => 'Entradas Recebidas',
            'caminho' => 'Entradas',
            'totalAPagar' => $contasAPagar->sum('valor'),
            'totalPagas' => $contasPagas->sum('valor'),
            'restaPagar' => $contasAPagar->sum('valor') - $contasPagas->sum('valor') ,
            'dataIni' =>$dataIni,
            'dataFim' =>$dataFim,
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
                'subtituloEsquerda' => 'Entradas a Receber',
                'subtituloDireita' => 'Entradas Recebidas',
                'data' => date('Y-m-d')
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
        $input['id_referencia'] = Conta::idReferencia();
        if(Entrada::create($input)) {
            $this->mensagem('success', 'Entrada Criada!');
        } else {
            $this->mensagem('danger', 'Houve um erro ao salvar entrada');
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

    function pagar($id) {
        $entrada = Entrada::find($id);
        if($entrada){
            $entrada->confirmado = true;
            $entrada->save();
           $this->mensagem('success', 'Entrada paga com sucesso!');
        } else {
            $this->mensagem('danger', 'Entrada não encontrada!');
        }
        return redirect()->back();
        
    }
    function estornar($id) {
        $entrada = Entrada::find($id);
        if($entrada){
            $entrada->confirmado = false;
            $entrada->save();
           $this->mensagem('success', 'Entrada estornada!');
        } else {
            $this->mensagem('danger', 'Entrada não encontrada!');
        }
        return redirect()->back();
        
    }
    public function mensagem($tipo,$texto)
    {
        session()->flash('alert', ['type' => $tipo, 'message' => $texto]);
    }
}