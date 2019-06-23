<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Conta;

class Centrodecusto extends Model
{
    public function conta()
    {
        return $this->hasMany(Conta::class);
    }    
}
