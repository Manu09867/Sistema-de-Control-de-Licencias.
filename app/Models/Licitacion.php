<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Licitacion extends Model
{
    protected $table = 'licitacion';
    protected $primaryKey = 'idLicitacion';
    public $timestamps = false;

    protected $fillable = [
        'folio',
        'DescripcionL',
        'FechaI',
        'FechaF',
        'estadoL',
        'idProveedor',
        'Total',
        'Recurso'
    ];

    protected $casts = [
        'FechaI' => 'date',
        'FechaF' => 'date',
        'Total' => 'decimal:2'
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'idProveedor', 'idProveedor');
    }

    public function detallesLicitacion()
    {
        return $this->hasMany(DetalleLicitacion::class, 'idLicitacion', 'idLicitacion');
    }
}