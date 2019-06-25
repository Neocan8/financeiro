<?php

namespace App\Model;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Model;

class Entrada extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

        'conta_id', 'categoria_id', 'nome', 'descricao', 'valor', 'parcela', 'id_referencia', 'confirmado', 'data',  
    ];

}
