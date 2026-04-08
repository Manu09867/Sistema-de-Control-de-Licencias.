<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\QueryLog;
use Carbon\Carbon;

class LogQueries
{
    public function handle(Request $request, Closure $next)
    {
        // ✅ Limpiar sesiones viejas (más de 2 horas sin actividad)
        $this->limpiarSesionesViejas();
        
        DB::enableQueryLog();
        $response = $next($request);
        $queries = DB::getQueryLog();

        foreach ($queries as $query) {
            $this->guardarLog($query, $request);
        }

        return $response;
    }

    /**
     * Limpiar sesiones que llevan más de X tiempo sin actividad
     */
    private function limpiarSesionesViejas()
    {
        try {
            // Eliminar sesiones con más de 2 horas (120 minutos) de inactividad
            $tiempoLimite = Carbon::now()->subMinutes(120)->timestamp;
            
            DB::table('sessions')
                ->where('last_activity', '<', $tiempoLimite)
                ->delete();
        } catch (\Exception $e) {
            // Silencioso - no queremos que esto afecte la aplicación
        }
    }

    private function guardarLog($query, $request)
    {
        // ✅ Tablas a ignorar (sistema Laravel)
        $tablasIgnoradas = [
            '`logs`',
            '`sessions`',
            '`cache`',
            '`jobs`',
            '`migrations`',
            '`password_resets`',
            '`failed_jobs`',
            '`personal_access_tokens`',
        ];
        foreach ($tablasIgnoradas as $tabla) {
            if (str_contains(strtolower($query['query']), $tabla)) {
                return;
            }
        }

        // Solo registrar cambios importantes
        $accion = $this->determinarAccion($query['query']);
        if (!in_array($accion, ['INSERT', 'UPDATE', 'DELETE'])) {
            return;
        }

        // Reconstruir query con valores reales
        $sqlCompleto = $this->reconstruirQuery($query['query'], $query['bindings']);

        try {
            QueryLog::create([
                'user_id'         => Auth::id(),
                'user_name'       => Auth::user()?->name,
                'accion'          => $accion,
                'tabla'           => $this->determinarTabla($query['query']),
                'query'           => $sqlCompleto,
                'resultado'       => 'OK',
                'filas_afectadas' => 0,
                'duracion'        => $query['time'] / 1000,
                'ip'              => $request->ip()
            ]);
        } catch (\Exception $e) {
            // Silencioso
        }
    }

    private function reconstruirQuery($sql, $bindings)
    {
        foreach ($bindings as $binding) {
            $valor = is_numeric($binding) ? $binding : "'{$binding}'";
            $sql = preg_replace('/\?/', $valor, $sql, 1);
        }
        return $sql;
    }

    private function determinarAccion($sql)
    {
        $sql = strtoupper(trim($sql));
        if (str_starts_with($sql, 'SELECT')) return 'SELECT';
        if (str_starts_with($sql, 'INSERT')) return 'INSERT';
        if (str_starts_with($sql, 'UPDATE')) return 'UPDATE';
        if (str_starts_with($sql, 'DELETE')) return 'DELETE';
        return 'OTHER';
    }

    private function determinarTabla($sql)
    {
        preg_match('/(?:FROM|INTO|UPDATE|JOIN)\s+([`"]?)(\w+)\1/i', $sql, $matches);
        return $matches[2] ?? null;
    }
}