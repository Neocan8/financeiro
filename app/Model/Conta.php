<?php

namespace App\Model;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use App\Model\Centrodecusto;

class Conta extends Model
{
    use Notifiable;

    protected $fillable = [
        'centrodecusto_id', 'nome', 'descricao', 'saldo',
    ];

    public function Centrodecusto()
    {
        return $this->belongsTo(Centrodecusto::class);
    }

    public static function idReferencia()
    {
        // retorna um valor aleatório para fazer a ligação entre as parcelas de cada conta
        // seja entrada ou saída
        return date('Ymdhis');
    }
}
