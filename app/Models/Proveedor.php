<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedor';
    protected $primaryKey = 'idProveedor';
    public $timestamps = false;

    protected $fillable = [
        'Nombre',
        'RFC',
        'Telefono',
        'Direccion',
        'correo'
    ];

    public function licitaciones()
    {
        return $this->hasMany(Licitacion::class, 'idProveedor', 'idProveedor');
    }
}