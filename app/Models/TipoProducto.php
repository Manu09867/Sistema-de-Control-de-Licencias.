<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoProducto extends Model
{
    protected $table = 'tipo_producto';
    protected $primaryKey = 'idTipo_Producto';
    public $timestamps = false;

    protected $fillable = [
        'NombreTP'
    ];

    public function productos()
    {
        return $this->hasMany(Producto::class, 'idTipo_Producto', 'idTipo_Producto');
    }
}