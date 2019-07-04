<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Conta;

class Centrodecusto extends Model
{
    protected $fillable = [
        'nome', 'descricao'
    ];
    
    public function conta()
    {
        return $this->hasMany(Conta::class);
    }    
}
