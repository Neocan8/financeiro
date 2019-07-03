<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Saida extends Model
{
    protected $fillable = [

        'conta_id', 'categoria_id', 'nome', 'descricao', 'valor', 'parcela', 'id_referencia', 'confirmado', 'data',  
    ];
}
