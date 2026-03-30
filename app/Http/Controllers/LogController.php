<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QueryLog;
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{
    public function index(Request $request)
    {
        // 🔥 PRIMERO: Obtener los últimos 1000 IDs (más recientes)
        $ultimosIds = QueryLog::orderBy('id', 'desc')->take(1000)->pluck('id');
        
        $query = QueryLog::with('user')->whereIn('id', $ultimosIds);
        
        // 🔥 APLICAR FILTROS DESDE LA URL (SOLO SOBRE LOS ÚLTIMOS 1000)
        if ($request->usuario) {
            $query->where('user_name', 'like', '%' . $request->usuario . '%');
        }
        if ($request->accion) {
            $query->where('accion', $request->accion);
        }
        if ($request->tabla) {
            $query->where('tabla', $request->tabla);
        }
        if ($request->fecha_inicio) {
            $query->whereDate('created_at', '=', $request->fecha_inicio);
        }
        
        // 🔥 ORDENAR POR ID DESCENDENTE (más reciente primero) con paginación de 50
        $logs = $query->orderBy('id', 'desc')->paginate(50);
        
        $totalConsultas   = QueryLog::count();
        $totalEliminaciones = QueryLog::where('accion', 'DELETE')->count();
        $totalCreaciones  = QueryLog::where('accion', 'INSERT')->count();
        
        return view('logs.index', compact('logs', 'totalConsultas', 'totalEliminaciones', 'totalCreaciones'));
    }
    
    public function filtrar(Request $request)
    {
        // 🔥 PRIMERO: Obtener los últimos 1000 IDs
        $ultimosIds = QueryLog::orderBy('id', 'desc')->take(1000)->pluck('id');
        
        $query = QueryLog::with('user')->whereIn('id', $ultimosIds);
        
        if ($request->accion) {
            $query->where('accion', $request->accion);
        }
        if ($request->tabla) {
            $query->where('tabla', $request->tabla);
        }
        if ($request->usuario) {
            $query->where('user_name', 'like', '%' . $request->usuario . '%');
        }
        if ($request->fecha_inicio) {
            $query->whereDate('created_at', '=', $request->fecha_inicio);
        }
        if ($request->fecha_fin) {
            $query->whereDate('created_at', '<=', $request->fecha_fin);
        }
        
        $logs = $query->orderBy('id', 'desc')->paginate(50);
        
        if ($request->ajax()) {
            return view('logs.partials.table', compact('logs'))->render();
        }
        
        return view('logs.index', compact('logs'));
    }

    /**
     * 🗑️ ELIMINAR UN REGISTRO POR ID
     */
    public function eliminarUnico($id)
    {
        try {
            $log = QueryLog::findOrFail($id);
            $log->delete();
            
            return redirect()->route('logs.index')
                ->with('success', '✅ Registro ID ' . $id . ' eliminado correctamente');
        } catch (\Exception $e) {
            return redirect()->route('logs.index')
                ->with('error', '❌ Error al eliminar el registro: ' . $e->getMessage());
        }
    }

    /**
     * 🗑️ ELIMINAR REGISTROS POR RANGO DE IDs
     */
    public function eliminarRango(Request $request)
    {
        try {
            $desde = $request->desde;
            $hasta = $request->hasta;
            
            if (!$desde || !$hasta) {
                return redirect()->route('logs.index')
                    ->with('error', '❌ Debes especificar un rango válido');
            }
            
            if ($desde > $hasta) {
                return redirect()->route('logs.index')
                    ->with('error', '❌ El ID "desde" debe ser menor que el ID "hasta"');
            }
            
            $cantidad = QueryLog::whereBetween('id', [$desde, $hasta])->count();
            
            if ($cantidad == 0) {
                return redirect()->route('logs.index')
                    ->with('error', '❌ No se encontraron registros en el rango especificado');
            }
            
            QueryLog::whereBetween('id', [$desde, $hasta])->delete();
            
            return redirect()->route('logs.index')
                ->with('success', "✅ {$cantidad} registro(s) eliminados (IDs {$desde} → {$hasta})");
                
        } catch (\Exception $e) {
            return redirect()->route('logs.index')
                ->with('error', '❌ Error al eliminar los registros: ' . $e->getMessage());
        }
    }

    /**
     * 🗑️ ELIMINAR TODOS LOS REGISTROS DE LOGS
     */
    public function eliminarTodos()
    {
        try {
            $cantidad = QueryLog::count();
            
            if ($cantidad == 0) {
                return redirect()->route('logs.index')
                    ->with('error', '❌ No hay registros para eliminar');
            }
            
            QueryLog::truncate();
            
            return redirect()->route('logs.index')
                ->with('success', "✅ Todos los registros ({$cantidad}) han sido eliminados");
                
        } catch (\Exception $e) {
            return redirect()->route('logs.index')
                ->with('error', '❌ Error al eliminar los registros: ' . $e->getMessage());
        }
    }
}