<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BusquedaController extends Controller
{
    public function buscar(Request $request)
    {
        $term = $request->get('q', '');
        $tipo = $request->get('tipo');
        $area = $request->get('area');
        $soloLicencias = $request->get('solo_licencias');
        
        $likeTerm = "%$term%";
        
        if ($soloLicencias) {
            // 🔥 SOLO MOSTRAR LICENCIAS CON CONTADOR DE EQUIPOS
            $licencias = DB::select("
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
                WHERE 1=1
                    AND (l.Clave LIKE ? OR s.Nombre LIKE ? OR ? = '')
                ORDER BY l.idLicencia DESC
            ", [$likeTerm, $likeTerm, $term]);
            
            return response()->json($licencias);
        }
        
        // 1. BUSCAR ARTÍCULOS CON FILTROS (INCLUYE GRUPOS)
        $articulos = DB::select("
            SELECT 
                'articulo' AS tipo,
                a.idArticulo,
                a.serie,
                a.estado,
                a.RP,
                p.NombreP AS producto,
                p.Marca AS marca,
                tp.NombreTP AS tipo_producto,
                ar.NombreArea AS area,
                NULL AS licencia_clave,
                NULL AS software_nombre,
                NULL AS licencia_estado,
                NULL AS licencia_vencimiento,
                NULL AS total_articulos_asignados
            FROM Articulo a
            LEFT JOIN Producto p ON a.idProducto = p.idProducto
            LEFT JOIN Tipo_Producto tp ON p.idTipo_Producto = tp.idTipo_Producto
            LEFT JOIN Area ar ON a.idArea = ar.idArea
            LEFT JOIN Grupo_Articulo ga ON a.idArticulo = ga.idArticulo
            LEFT JOIN Grupo g ON ga.idGrupo = g.idGrupo
            WHERE 1=1
                AND (a.serie LIKE ? 
                    OR a.RP LIKE ? 
                    OR p.NombreP LIKE ? 
                    OR g.NombreGrupo LIKE ?
                    OR ? = '')
                AND (? IS NULL OR ? = '' OR p.idTipo_Producto = ?)
                AND (? IS NULL OR ? = '' OR a.idArea = ?)
        ", [
            $likeTerm, $likeTerm, $likeTerm, $likeTerm, $term,
            $tipo, $tipo, $tipo,
            $area, $area, $area
        ]);
        
        // 2. BUSCAR LICENCIAS - SOLO SI NO HAY FILTROS DE TIPO/ÁREA ACTIVOS
        $mostrarLicencias = empty($tipo) && empty($area);
        
        if ($mostrarLicencias) {
            $licencias = DB::select("
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
                WHERE 1=1
                    AND (l.Clave LIKE ? OR s.Nombre LIKE ? OR ? = '')
                ORDER BY l.idLicencia DESC
            ", [$likeTerm, $likeTerm, $term]);
        } else {
            $licencias = [];
        }
        
        // Combinar resultados
        $resultados = array_merge($articulos, $licencias);
        
        return response()->json($resultados);
    }
}