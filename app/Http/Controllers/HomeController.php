<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    protected $dataIni;
    protected $dataFim;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
     
     public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function indexPeriodo(Request $request)
    {

        $this->dataIni = $request->input('dataIni');
        $this->dataFim = $request->input('dataFim');

        $this->index();

    }
    public function index(Request $request)
    {
        
        // SE TEM POST, COLOCA NA SESSION
        if ($request->input('dataIni')) {
            $request->session()->put('dataIni', $request->input('dataIni'));
        }
        if (!$request->session()->has('dataFim')) {
            $request->session()->put('dataFim', $request->input('dataFim'));
        }
        
        // SE AQUI NÃO TME SESSION NEM POST É O PRIMEIRO ACESSO
        // COLOCANDO DATA INICIAL E FINAL PADRAO
        if(!$request->session()->has('dataIni'))
        $request->session()->put('dataIni', date('Y-m-01'));
        
        if($request->session()->has('dataFim'))
        $request->session()->put('dataFim', date('Y-m-t'));
        
        $dataIni = $request->session()->get('dataIni');
        $dataFim = $request->session()->get('dataFim$dataFim');
        
        
        $entradas = DB::table('entradas')
        ->whereBetween('data', [$dataIni,$dataFim])->get();
        $totalEntradas = $entradas->sum('valor');
        
        $saidas = DB::table('saidas')
        ->whereBetween('data', [$dataIni,$dataFim])->get();
        $totalSaidas = $saidas->sum('valor');
        
        $resultado = $totalEntradas - $totalSaidas;
        $classResult = $resultado > 0 ? 'bg-aqua' : 'bg-red';

        $dadosPagina = [
            'titulo'            => 'Início - Balanço',
            'classResultado'    => $classResult,
            'subtituloEsquerda' => 'Entradas',
            'subtituloDireita'  => 'Saídas',
            'rota'              => 'home',
            'rotaPeriodo'       => 'home',
            'dataIni'           => $dataIni,
            'dataFim'           => $dataFim,
        ];
        
        return view('home', compact('entradas', 'totalEntradas', 'saidas', 'totalSaidas', 'resultado', 'dadosPagina'));
    }
}
