<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\database\Eloquent\SoftDeletes;

class Categoria extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'nome', 'tipo'
    ];
    

}
