<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrupoArticulo extends Model
{
    protected $table = 'grupo_articulo';
    protected $primaryKey = 'idArticulo';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'idArticulo',
        'idGrupo'
    ];

    public function articulo()
    {
        return $this->belongsTo(Articulo::class, 'idArticulo', 'idArticulo');
    }

    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'idGrupo', 'idGrupo');
    }
}