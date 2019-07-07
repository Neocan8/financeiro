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
    public function index()
    {
        $dataIni = $this->dataIni ? $this->dataIni :  date('Y-m-01');
        $dataFim = $this->dataFim ? $this->dataFim :  date('Y-m-t');
        $entradas = DB::table('entradas')
        ->whereBetween('data', [$dataIni,$dataFim])->get();
        $totalEntradas = $entradas->sum('valor');

        $saidas = DB::table('saidas')
        ->whereBetween('data', [$dataIni,$dataFim])->get();
        $totalSaidas = $saidas->sum('valor');

        $resultado = $totalEntradas - $totalSaidas;
        $classResult = $resultado > 0 ? 'bg-aqua' : 'bg-red';

        $dadosPagina = [
            'titulo'            => 'Balanço',
            'classResultado'    => $classResult,
            'subtituloEsquerda' => 'Entradas',
            'subtituloDireita'  => 'Saídas',
        ];

        return view('home', compact('entradas', 'totalEntradas', 'saidas', 'totalSaidas', 'resultado', 'dadosPagina'));
    }
}
