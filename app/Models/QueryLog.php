<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QueryLog extends Model
{
    protected $table = 'logs';
    
    protected $fillable = [
        'user_id',
        'user_name',
        'accion',
        'tabla',
        'query',
        'resultado',
        'filas_afectadas',
        'duracion',
        'ip'
    ];
    
    protected $casts = [
        'duracion' => 'float',
        'filas_afectadas' => 'integer',
        'created_at' => 'datetime'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}