<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsignacionLicencia extends Model
{
    protected $table = 'asignacion_licencia';
    protected $primaryKey = 'idAsignacion_Licencia';
    public $timestamps = false;

    protected $fillable = [
        'idLicencia',
        'idArticulo',
        'ObservacionAL'
    ];

    public function licencia()
    {
        return $this->belongsTo(Licencia::class, 'idLicencia', 'idLicencia');
    }

    public function articulo()
    {
        return $this->belongsTo(Articulo::class, 'idArticulo', 'idArticulo');
    }
}