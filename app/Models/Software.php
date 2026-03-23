<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Software extends Model
{
    protected $table = 'software';
    protected $primaryKey = 'idSoftware';
    public $timestamps = false;

    protected $fillable = [
        'Nombre',
        'Tipo'
    ];

    public function licencias()
    {
        return $this->hasMany(Licencia::class, 'idSoftware', 'idSoftware');
    }

    public function detallesLicitacion()
    {
        return $this->hasMany(DetalleLicitacion::class, 'idSoftware', 'idSoftware');
    }
}