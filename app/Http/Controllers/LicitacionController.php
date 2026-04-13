<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LicitacionController extends Controller
{
    public function index()
    {
        $licitaciones = DB::select("
            SELECT 
                l.idLicitacion,
                l.folio,
                l.DescripcionL AS descripcion,
                l.FechaI AS fecha_inicio,
                l.FechaF AS fecha_fin,
                l.estadoL AS estado,
                l.Total,
                l.Recurso,
                p.Nombre AS proveedor_nombre,
                (
                    SELECT COUNT(*) 
                    FROM detalle_licitacion dl 
                    WHERE dl.idLicitacion = l.idLicitacion
                    AND (
                        (dl.TipoItem = 'HARDWARE' AND EXISTS (
                            SELECT 1 FROM articulo a WHERE a.idDetalle_Licitacion = dl.idDetalle_Licitacion
                        ))
                        OR
                        (dl.TipoItem = 'SOFTWARE' AND EXISTS (
                            SELECT 1 FROM licencia lc WHERE lc.idDetalle_Licitacion = dl.idDetalle_Licitacion
                        ))
                    )
                ) AS total_detalles
            FROM licitacion l
            LEFT JOIN proveedor p ON l.idProveedor = p.idProveedor
            ORDER BY l.idLicitacion DESC
            LIMIT 50
        ");

        return view('licitaciones.index', compact('licitaciones'));
    }

    public function show($id)
    {
        $licitacion = DB::select("
        SELECT 
            l.idLicitacion,
            l.folio,
            l.DescripcionL AS descripcion,
            l.FechaI AS fecha_inicio,
            l.FechaF AS fecha_fin,
            l.estadoL AS estado,
            l.Total,
            l.Recurso AS recurso,
            l.idProveedor,
            p.Nombre AS proveedor_nombre
        FROM licitacion l
        LEFT JOIN proveedor p ON l.idProveedor = p.idProveedor
        WHERE l.idLicitacion = ?
    ", [$id]);

        if (empty($licitacion)) {
            abort(404, 'Licitación no encontrada');
        }

        $licitacion = $licitacion[0];

        // 🔥 NUEVA CONSULTA: Mostrar CADA ARTÍCULO individualmente
        $detalles = DB::select("
        SELECT 
            'HARDWARE' AS TipoItem,
            a.idArticulo,
            a.serie,
            a.RP,
            a.estado,
            p.NombreP AS item_nombre,
            p.Marca,
            p.Modelo,
            ar.NombreArea AS area,
            dl.Cantidad AS cantidad_detalle,
            dl.PrecioU,
            dl.Subtotal
        FROM articulo a
        INNER JOIN detalle_licitacion dl ON a.idDetalle_Licitacion = dl.idDetalle_Licitacion
        INNER JOIN producto p ON a.idProducto = p.idProducto
        LEFT JOIN area ar ON a.idArea = ar.idArea
        WHERE dl.idLicitacion = ?
        
        UNION ALL
        
        SELECT 
            'SOFTWARE' AS TipoItem,
            l.idLicencia AS idArticulo,
            NULL AS serie,
            l.Clave AS RP,
            l.estadoLic AS estado,
            s.Nombre AS item_nombre,
            NULL AS Marca,
            NULL AS Modelo,
            NULL AS area,
            dl.Cantidad AS cantidad_detalle,
            dl.PrecioU,
            dl.Subtotal
        FROM licencia l
        INNER JOIN detalle_licitacion dl ON l.idDetalle_Licitacion = dl.idDetalle_Licitacion
        INNER JOIN software s ON l.idSoftware = s.idSoftware
        WHERE dl.idLicitacion = ?
    ", [$id, $id]);

        $proveedores = DB::table('proveedor')->orderBy('Nombre')->get();

        return view('licitaciones.show', compact('licitacion', 'detalles', 'proveedores'));
    }

    /**
     * 🗑️ ELIMINAR LICITACIÓN COMPLETA (SOLO ADMIN CON VERIFICACIÓN DE CONTRASEÑA)
     */
    public function destroy(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos para realizar esta acción'
            ], 403);
        }

        try {
            DB::beginTransaction();

            if (!Hash::check($request->password, auth()->user()->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Contraseña incorrecta'
                ], 401);
            }

            $licitacion = DB::table('licitacion')->where('idLicitacion', $id)->first();

            if (!$licitacion) {
                return response()->json([
                    'success' => false,
                    'message' => 'Licitación no encontrada'
                ], 404);
            }

            $detalles = DB::table('detalle_licitacion')->where('idLicitacion', $id)->get();

            foreach ($detalles as $detalle) {
                if ($detalle->TipoItem === 'SOFTWARE') {
                    $licencias = DB::table('licencia')->where('idDetalle_Licitacion', $detalle->idDetalle_Licitacion)->get();

                    foreach ($licencias as $licencia) {
                        DB::table('asignacion_licencia')->where('idLicencia', $licencia->idLicencia)->delete();
                        DB::table('licencia')->where('idLicencia', $licencia->idLicencia)->delete();
                    }
                } elseif ($detalle->TipoItem === 'HARDWARE') {
                    $articulos = DB::table('articulo')->where('idDetalle_Licitacion', $detalle->idDetalle_Licitacion)->get();

                    foreach ($articulos as $articulo) {
                        DB::table('asignacion_licencia')->where('idArticulo', $articulo->idArticulo)->delete();
                        DB::table('grupo_articulo')->where('idArticulo', $articulo->idArticulo)->delete();
                        DB::table('articulo_router')->where('idArticulo', $articulo->idArticulo)->delete();
                        DB::table('articulo_switch')->where('idArticulo', $articulo->idArticulo)->delete();
                        DB::table('articulo')->where('idArticulo', $articulo->idArticulo)->delete();
                    }
                }

                DB::table('detalle_licitacion')->where('idDetalle_Licitacion', $detalle->idDetalle_Licitacion)->delete();
            }

            DB::table('licitacion')->where('idLicitacion', $id)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => '✅ Licitación y todos sus elementos relacionados han sido eliminados correctamente'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al eliminar licitación: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 🔄 ACTUALIZAR LICITACIÓN (SOLO ADMIN)
     */
    public function update(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos para realizar esta acción'
            ], 403);
        }

        try {
            $validated = $request->validate([
                'folio' => 'required|string|max:50',
                'idProveedor' => 'nullable|exists:proveedor,idProveedor',
                'estado' => 'required|string|in:Abierta,En proceso,Adjudicada,Cerrada,Cancelada',
                'recurso' => 'nullable|string|max:45',
                'fecha_inicio' => 'nullable|date',
                'fecha_fin' => 'nullable|date',
                'descripcion' => 'nullable|string|max:255',
            ]);

            DB::beginTransaction();

            DB::table('licitacion')
                ->where('idLicitacion', $id)
                ->update([
                    'folio' => $validated['folio'],
                    'idProveedor' => $validated['idProveedor'],
                    'estadoL' => $validated['estado'],
                    'Recurso' => $validated['recurso'],
                    'FechaI' => $validated['fecha_inicio'],
                    'FechaF' => $validated['fecha_fin'],
                    'DescripcionL' => $validated['descripcion']
                ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => '✅ Licitación actualizada correctamente'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación: ' . implode(', ', array_map(function ($errors) {
                    return implode(', ', $errors);
                }, $e->errors()))
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al actualizar licitación: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar: ' . $e->getMessage()
            ], 500);
        }
    }
}