<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'producto';
    protected $primaryKey = 'idProducto';
    public $timestamps = false;

    protected $fillable = [
        'NombreP',
        'Marca',
        'Modelo',
        'idTipo_Producto'
    ];

    public function tipoProducto()
    {
        return $this->belongsTo(TipoProducto::class, 'idTipo_Producto', 'idTipo_Producto');
    }

    public function articulos()
    {
        return $this->hasMany(Articulo::class, 'idProducto', 'idProducto');
    }

    public function detallesLicitacion()
    {
        return $this->hasMany(DetalleLicitacion::class, 'idProducto', 'idProducto');
    }
}