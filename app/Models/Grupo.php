<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    protected $table = 'grupo';
    protected $primaryKey = 'idGrupo';
    public $timestamps = false;

    protected $fillable = [
        'NombreGrupo',
        'TipoGrupo'
    ];

    public function articulos()
    {
        return $this->belongsToMany(
            Articulo::class,
            'grupo_articulo',
            'idGrupo',
            'idArticulo'
        );
    }
}