<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticuloSwitch extends Model
{
    protected $table = 'articulo_switch';
    protected $primaryKey = 'idArticulo';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'idArticulo',
        'MACSw',
        'ObservacionSw',
        'IpaddressSw'
    ];

    public function articulo()
    {
        return $this->belongsTo(Articulo::class, 'idArticulo', 'idArticulo');
    }
}