<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleLicitacion extends Model
{
    protected $table = 'detalle_licitacion';
    protected $primaryKey = 'idDetalle_Licitacion';
    public $timestamps = false;

    protected $fillable = [
        'TipoItem',
        'idLicitacion',
        'idSoftware',
        'idProducto',
        'Cantidad',
        'PrecioU',
        'Subtotal'
    ];

    protected $casts = [
        'PrecioU' => 'decimal:2',
        'Subtotal' => 'decimal:2'
    ];

    public function licitacion()
    {
        return $this->belongsTo(Licitacion::class, 'idLicitacion', 'idLicitacion');
    }

    public function software()
    {
        return $this->belongsTo(Software::class, 'idSoftware', 'idSoftware');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'idProducto', 'idProducto');
    }

    public function articulos()
    {
        return $this->hasMany(Articulo::class, 'idDetalle_Licitacion', 'idDetalle_Licitacion');
    }

    public function licencias()
    {
        return $this->hasMany(Licencia::class, 'idDetalle_Licitacion', 'idDetalle_Licitacion');
    }
}