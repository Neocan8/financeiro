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
}
