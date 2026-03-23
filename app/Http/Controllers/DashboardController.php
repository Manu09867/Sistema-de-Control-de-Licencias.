<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Articulo;
use App\Models\Producto;
use App\Models\Area;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function admin()
    {
        // Verificar que el usuario es admin
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        // Consulta para admin - Últimos 20 artículos
        $articulos = Articulo::with(['producto.tipoProducto', 'area'])
            ->where('estado', '!=', 'Baja')
            ->orderBy('idArticulo', 'desc')
            ->limit(20)
            ->get();
        
        // Preparar artículos para JSON (evitar objetos complejos)
        $articulosParaJs = $articulos->map(function($articulo) {
            return [
                'tipo' => 'articulo',
                'serie' => $articulo->serie,
                'estado' => $articulo->estado,
                'RP' => $articulo->RP,
                'producto' => $articulo->producto->NombreP ?? null,
                'marca' => $articulo->producto->Marca ?? null,
                'tipo_producto' => $articulo->producto->tipoProducto->NombreTP ?? null,
                'area' => $articulo->area->NombreArea ?? null,
            ];
        })->values();
        
        // 🔥 CORREGIDO: Cargar las últimas 10 licencias con CONTADOR DE EQUIPOS ASIGNADOS
        $licenciasIniciales = DB::select("
            SELECT 
                'licencia' AS tipo,
                l.idLicencia,
                NULL AS serie,
                l.estadoLic AS estado,
                NULL AS RP,
                s.Nombre AS producto,
                NULL AS marca,
                NULL AS tipo_producto,
                NULL AS area,
                l.Clave AS licencia_clave,
                s.Nombre AS software_nombre,
                l.estadoLic AS licencia_estado,
                l.Fechavencimiento AS licencia_vencimiento,
                COALESCE((
                    SELECT COUNT(*) 
                    FROM Asignacion_Licencia al2 
                    WHERE al2.idLicencia = l.idLicencia
                ), 0) AS total_articulos_asignados
            FROM Licencia l
            LEFT JOIN Software s ON l.idSoftware = s.idSoftware
            ORDER BY l.idLicencia DESC
            LIMIT 10
        ");
        
        // Estadísticas para el admin
        $stats = [
            'totalArticulos' => Articulo::count(),
            'totalProductos' => Producto::count(),
            'totalAreas' => Area::count(),
        ];
        
        // Obtener datos para los filtros
        $tiposProducto = DB::table('Tipo_Producto')
            ->orderBy('NombreTP')
            ->get();
            
        $areas = Area::orderBy('NombreArea')->get();
        
        return view('dashboard-admin', compact('articulos', 'articulosParaJs', 'licenciasIniciales', 'stats', 'tiposProducto', 'areas'));
    }

    public function user()
    {
        // Verificar que el usuario es user
        if (auth()->user()->role !== 'user') {
            abort(403);
        }

        // Para usuario normal - últimos 20 artículos
        $articulos = Articulo::with(['producto.tipoProducto', 'area'])
            ->where('estado', '!=', 'Baja')
            ->orderBy('idArticulo', 'desc')
            ->limit(20)
            ->get();
        
        // Preparar artículos para JSON
        $articulosParaJs = $articulos->map(function($articulo) {
            return [
                'tipo' => 'articulo',
                'serie' => $articulo->serie,
                'estado' => $articulo->estado,
                'RP' => $articulo->RP,
                'producto' => $articulo->producto->NombreP ?? null,
                'marca' => $articulo->producto->Marca ?? null,
                'tipo_producto' => $articulo->producto->tipoProducto->NombreTP ?? null,
                'area' => $articulo->area->NombreArea ?? null,
            ];
        })->values();
        
        // 🔥 CORREGIDO: Cargar las últimas 10 licencias con CONTADOR DE EQUIPOS ASIGNADOS
        $licenciasIniciales = DB::select("
            SELECT 
                'licencia' AS tipo,
                l.idLicencia,
                NULL AS serie,
                l.estadoLic AS estado,
                NULL AS RP,
                s.Nombre AS producto,
                NULL AS marca,
                NULL AS tipo_producto,
                NULL AS area,
                l.Clave AS licencia_clave,
                s.Nombre AS software_nombre,
                l.estadoLic AS licencia_estado,
                l.Fechavencimiento AS licencia_vencimiento,
                COALESCE((
                    SELECT COUNT(*) 
                    FROM Asignacion_Licencia al2 
                    WHERE al2.idLicencia = l.idLicencia
                ), 0) AS total_articulos_asignados
            FROM Licencia l
            LEFT JOIN Software s ON l.idSoftware = s.idSoftware
            ORDER BY l.idLicencia DESC
            LIMIT 10
        ");
        
        // Obtener datos para los filtros
        $tiposProducto = DB::table('Tipo_Producto')
            ->orderBy('NombreTP')
            ->get();
            
        $areas = Area::orderBy('NombreArea')->get();
        
        return view('dashboard-user', compact('articulos', 'articulosParaJs', 'licenciasIniciales', 'tiposProducto', 'areas'));
    }
}