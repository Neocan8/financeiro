<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Historico extends Model
{
    protected $fillable = [

        'conta_id', 'descricao', 'valor', 'tipo',  
    ];
}
