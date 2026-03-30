<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\QueryLog;

class LogQueries
{
    public function handle(Request $request, Closure $next)
    {
        DB::enableQueryLog();
        $response = $next($request);
        $queries = DB::getQueryLog();

        foreach ($queries as $query) {
            $this->guardarLog($query, $request);
        }

        return $response;
    }

    private function guardarLog($query, $request)
    {
        // Evitar recursión
        if (str_contains(strtolower($query['query']), '`logs`')) {
            return;
        }

        // Solo registrar cambios importantes
        $accion = $this->determinarAccion($query['query']);
        if (!in_array($accion, ['INSERT', 'UPDATE', 'DELETE'])) {
            return;
        }

        // ✅ Reconstruir query con valores reales
        $sqlCompleto = $this->reconstruirQuery($query['query'], $query['bindings']);

        try {
            QueryLog::create([
                'user_id'         => Auth::id(),
                'user_name'       => Auth::user()?->name,
                'accion'          => $accion,
                'tabla'           => $this->determinarTabla($query['query']),
                'query'           => $sqlCompleto, // ✅ antes era $query['query']
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