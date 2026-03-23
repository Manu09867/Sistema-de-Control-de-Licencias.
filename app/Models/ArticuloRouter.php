<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticuloRouter extends Model
{
    protected $table = 'articulo_router';
    protected $primaryKey = 'idArticulo';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'idArticulo',
        'MACR',
        'ObservacionR',
        'IpaddressR'
    ];

    public function articulo()
    {
        return $this->belongsTo(Articulo::class, 'idArticulo', 'idArticulo');
    }
}