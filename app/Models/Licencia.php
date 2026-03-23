<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Licencia extends Model
{
    protected $table = 'licencia';
    protected $primaryKey = 'idLicencia';
    public $timestamps = false;

    protected $fillable = [
        'Clave',
        'DescripcionLicencia',
        'CapacidadLicencia',
        'Fechacompra',
        'Fechavencimiento',
        'estadoLic',
        'idSoftware',
        'idDetalle_Licitacion'
    ];

    protected $casts = [
        'Fechacompra' => 'date',
        'Fechavencimiento' => 'date',
        'CapacidadLicencia' => 'integer'
    ];

    // Relaciones
    public function software()
    {
        return $this->belongsTo(Software::class, 'idSoftware', 'idSoftware');
    }

    public function detalleLicitacion()
    {
        return $this->belongsTo(DetalleLicitacion::class, 'idDetalle_Licitacion', 'idDetalle_Licitacion');
    }

    public function articulos()
    {
        return $this->belongsToMany(
            Articulo::class,
            'asignacion_licencia',
            'idLicencia',
            'idArticulo'
        )->withPivot('ObservacionAL');
    }

    // Accesor para mostrar capacidad de forma legible
    public function getCapacidadTextoAttribute()
    {
        if ($this->CapacidadLicencia) {
            return $this->CapacidadLicencia . ' equipos';
        }
        return 'Ilimitada';
    }

    // Accesor para saber si está por vencer
    public function getDiasRestantesAttribute()
    {
        if (!$this->Fechavencimiento) {
            return null;
        }
        $dias = (strtotime($this->Fechavencimiento) - time()) / 86400;
        return round($dias);
    }

    // Accesor para estado con color
    public function getEstadoBadgeAttribute()
    {
        return match($this->estadoLic) {
            'Activa' => '<span class="badge badge-activo">✅ Activa</span>',
            'Inactiva' => '<span class="badge badge-inactivo">⛔ Inactiva</span>',
            'Vencida' => '<span class="badge badge-vencida">📅 Vencida</span>',
            'Por vencer' => '<span class="badge badge-pronto">⚠️ Por vencer</span>',
            default => '<span class="badge">' . $this->estadoLic . '</span>'
        };
    }
}