<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $table = 'area';
    protected $primaryKey = 'idArea';
    public $timestamps = false;

    protected $fillable = ['NombreArea'];

    public function articulos()
    {
        return $this->hasMany(Articulo::class, 'idArea', 'idArea');
    }
}