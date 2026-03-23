<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    protected $table = 'articulo';
    protected $primaryKey = 'idArticulo';
    public $timestamps = false;

    protected $fillable = [
        'serie',
        'estado',
        'RP',
        'idProducto',
        'idDetalle_Licitacion',
        'idArea'
    ];

    public function area()
    {
        return $this->belongsTo(Area::class, 'idArea', 'idArea');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'idProducto', 'idProducto');
    }

    public function detalleLicitacion()
    {
        return $this->belongsTo(DetalleLicitacion::class, 'idDetalle_Licitacion', 'idDetalle_Licitacion');
    }

    public function licencias()
    {
        return $this->belongsToMany(
            Licencia::class,
            'asignacion_licencia',
            'idArticulo',
            'idLicencia'
        )->withPivot('ObservacionAL');
    }

    public function router()
    {
        return $this->hasOne(ArticuloRouter::class, 'idArticulo', 'idArticulo');
    }

    public function switch()
    {
        return $this->hasOne(ArticuloSwitch::class, 'idArticulo', 'idArticulo');
    }

    public function grupos()
    {
        return $this->belongsToMany(
            Grupo::class,
            'grupo_articulo',
            'idArticulo',
            'idGrupo'
        );
    }

    public function getEsRouterAttribute()
    {
        return $this->router()->exists();
    }

    public function getEsSwitchAttribute()
    {
        return $this->switch()->exists();
    }
}