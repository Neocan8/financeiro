<?php

namespace App\Model;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use App\Model\Centrodecusto;
use Log;

class Conta extends Model
{
    use Notifiable;

    protected $fillable = [
        'centrodecusto_id', 'nome', 'descricao', 'saldo',
    ];
    
    public function centrodecusto()
    {
        return $this->belongsTo(Centrodecusto::class);
    }

    public function entrada()
    {
        return $this->hasMany('App\Model\Entrada');
    }

    public function saida()
    {
        return $this->hasMany('App\Model\saida');
    }

    public function historico()
    {
        return $this->hasMany('App\Model\historico');
    }

    public static function deposito($id,$valor)
    {
        $conta = Conta::find($id);
        //dd($conta);
        if(!$conta){
            Conta::mensagem('danger','Conta Inexistente');
            return redirect()->back();
        }

        $conta->saldo = $conta->saldo + $valor;
        //dd($conta);
        $conta->save();
        return true;
    }
    
    public static function saque($id,$valor)
    {
        $conta = Conta::find($id);
        if(!$conta){
            Conta::mensagem('danger','Conta Inexistente');
            return redirect()->back();
        }
        $conta->saldo = $conta->saldo - $valor;
        $conta->save();
        return true;
    }

  

    public static function idReferencia()
    {
        // retorna um valor aleatório para fazer a ligação entre as parcelas de cada conta
        // seja entrada ou saída
        return date('Ymdhis');
    }

    public static function incrementaMes($data) :string
    {
        $partes = explode("-", $data);
        $ano = $partes[0];
        $mes = $partes[1];
        $dia = $partes[2];

        $mes = $mes == '12' ? $mes = '1' : ++$mes; // garante que não há mes 13
        $ano = $mes == '01' ? ++$ano : $ano; // com ingremento no mes 12  o ano aumenta
        $mes = $mes < 10 ? '0' . $mes : $mes; // adiciona o zero para meses menores que 10

        if($dia == 30 || $dia == 31 && $mes == '02') {
            $dia = 01;
            $mes = 03;
        }

        if($dia ==31 && $mes == '02' || $mes == '04' || $mes == '06' || $mes == '09' || $mes == '11'){
            $dia = '01';
            $mes = $mes +1;

            $mes = $mes == '12' ? $mes = '1' : ++$mes; // garante que não há mes 13
            $ano = $mes == '01' ? ++$ano : $ano; // com ingremento no mes 12  o ano aumenta
            $mes = $mes < 10 ? '0' . $mes : $mes; // adiciona o zero para meses menores que 10

        }

        return $ano . "-" . $mes . "-" . $dia;
    }

    public static function mensagem($tipo,$texto)
    {
        session()->flash('alert', ['type' => $tipo, 'message' => $texto]);
    }

    public static function registra($acao,$tabela,$dadosAntigos,$dadosNovos = null)
    {
        $mensagem = "
        Usuário Logado: " . auth()->user()->id ."-". auth()->user()->name . "  | Ação: $acao | Tabela: $tabela | Dados Antigos:"
         . json_encode($dadosAntigos) . " | Dados Novos: " . json_encode($dadosNovos);
        
        Log::info($mensagem);
    }
}
