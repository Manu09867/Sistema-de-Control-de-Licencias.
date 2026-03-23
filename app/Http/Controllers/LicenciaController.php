<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LicenciaController extends Controller
{
    public function show($clave)
    {
        $licencia = DB::select("
            SELECT 
                l.idLicencia,
                l.Clave,
                l.DescripcionLicencia,
                l.CapacidadLicencia,
                l.Fechacompra,
                l.Fechavencimiento,
                l.estadoLic,
                l.idSoftware,
                s.Nombre AS software_nombre,
                lic.folio AS folio_licitacion,
                lic.DescripcionL AS descripcion_licitacion,
                pr.Nombre AS proveedor,
                (SELECT COUNT(*) FROM Asignacion_Licencia WHERE idLicencia = l.idLicencia) AS total_articulos
            FROM Licencia l
            LEFT JOIN Software s ON l.idSoftware = s.idSoftware
            LEFT JOIN Detalle_Licitacion dl ON l.idDetalle_Licitacion = dl.idDetalle_Licitacion
            LEFT JOIN Licitacion lic ON dl.idLicitacion = lic.idLicitacion
            LEFT JOIN Proveedor pr ON lic.idProveedor = pr.idProveedor
            WHERE l.Clave = ?
        ", [$clave]);

        if (empty($licencia)) {
            abort(404, 'Licencia no encontrada');
        }

        $licencia = $licencia[0];

        $articulos = DB::select("
            SELECT 
                a.serie,
                a.RP,
                a.estado,
                p.NombreP AS producto,
                p.Marca AS marca,
                p.Modelo AS modelo,
                ar.NombreArea AS area
            FROM Articulo a
            INNER JOIN Asignacion_Licencia al ON a.idArticulo = al.idArticulo
            LEFT JOIN Producto p ON a.idProducto = p.idProducto
            LEFT JOIN Area ar ON a.idArea = ar.idArea
            WHERE al.idLicencia = ?
        ", [$licencia->idLicencia]);

        // Cargar softwares para el select en modo edición
        $softwares = DB::table('software')->orderBy('Nombre')->get();

        return view('licencia-detalle', compact('licencia', 'articulos', 'softwares'));
    }

    public function create()
    {
        $softwares = DB::table('software')->orderBy('Nombre')->get();
        $proveedores = DB::table('proveedor')->orderBy('Nombre')->get();
        $licitaciones = DB::table('licitacion as l')
            ->leftJoin('proveedor as p', 'l.idProveedor', '=', 'p.idProveedor')
            ->select('l.idLicitacion', 'l.folio', 'p.Nombre as proveedor_nombre')
            ->orderBy('l.folio')
            ->get();
            
        return view('licencias.crear', compact('softwares', 'proveedores', 'licitaciones'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Decodificar los datos del formulario
            $datos = json_decode($request->datos_completos, true);
            
            if (!$datos) {
                throw new \Exception('No se recibieron datos válidos');
            }

            Log::info('Datos recibidos en store licencia:', $datos);

            // ===== VALIDACIÓN DE CAMPOS REQUERIDOS =====
            $validator = Validator::make($datos, [
                'software.id' => 'required',
                'software.nombre' => 'required|string',
                'tipo_licitacion' => 'required|in:existente,nueva',
                'proveedor.id' => 'required_if:tipo_licitacion,nueva',
                'proveedor.nombre' => 'required_if:tipo_licitacion,nueva|string',
                'licitacion.id' => 'required_if:tipo_licitacion,existente',
                'licitacion.folio' => 'required_if:tipo_licitacion,nueva|string|max:50',
                'detalle.cantidad' => 'required|integer|min:1',
                'detalle.precio_unitario' => 'required|numeric|min:0',
                'licencia.clave' => 'required|string|max:45',
                'licencia.descripcion' => 'nullable|string|max:255',
                'licencia.capacidad' => 'nullable|integer|min:1',
                'licencia.fecha_compra' => 'required|date',
                'licencia.fecha_vencimiento' => 'required|date',
                'licencia.estado' => 'required|in:Activa,Inactiva,Vencida,Por vencer',
            ]);

            if ($validator->fails()) {
                throw new \Exception('Error de validación: ' . json_encode($validator->errors()));
            }

            // ===== 1. TIPO DE SOFTWARE =====
            $softwareId = $datos['software']['id'];

            // ===== 2. PROVEEDOR (si es licitación nueva) =====
            $proveedorId = null;
            if (isset($datos['proveedor']['id'])) {
                $proveedorId = $datos['proveedor']['id'];
            }

            // ===== 3. LICITACIÓN =====
            $licitacionId = null;
            $esNuevaLicitacion = false;

            if ($datos['tipo_licitacion'] === 'existente') {
                // Usar licitación existente
                $licitacionId = $datos['licitacion']['id'];
            } else {
                // Crear nueva licitación
                $esNuevaLicitacion = true;
                $licitacionId = DB::table('licitacion')->insertGetId([
                    'folio' => $datos['licitacion']['folio'],
                    'DescripcionL' => $datos['licitacion']['descripcion'] ?? null,
                    'FechaI' => $datos['licitacion']['fecha_inicio'] ?? null,
                    'FechaF' => $datos['licitacion']['fecha_fin'] ?? null,
                    'estadoL' => $datos['licitacion']['estado'] ?? 'Abierta',
                    'idProveedor' => $proveedorId,
                    'Total' => $datos['detalle']['subtotal'],
                    'Recurso' => $datos['licitacion']['recurso'] ?? null
                ]);
            }

            // ===== 4. DETALLE DE LICITACIÓN =====
            $detalleId = DB::table('detalle_licitacion')->insertGetId([
                'TipoItem' => 'SOFTWARE',
                'idLicitacion' => $licitacionId,
                'idSoftware' => $softwareId,
                'idProducto' => null,
                'Cantidad' => $datos['detalle']['cantidad'],
                'PrecioU' => $datos['detalle']['precio_unitario'],
                'Subtotal' => $datos['detalle']['subtotal']
            ]);

            // Si NO es nueva licitación (es existente), sumar al total
            if (!$esNuevaLicitacion) {
                DB::table('licitacion')
                    ->where('idLicitacion', $licitacionId)
                    ->increment('Total', $datos['detalle']['subtotal']);
            }

            // ===== 5. LICENCIA =====
            DB::table('licencia')->insert([
                'Clave' => $datos['licencia']['clave'],
                'DescripcionLicencia' => $datos['licencia']['descripcion'] ?? null,
                'CapacidadLicencia' => $datos['licencia']['capacidad'] ?? null,
                'Fechacompra' => $datos['licencia']['fecha_compra'],
                'Fechavencimiento' => $datos['licencia']['fecha_vencimiento'],
                'estadoLic' => $datos['licencia']['estado'],
                'idSoftware' => $softwareId,
                'idDetalle_Licitacion' => $detalleId
            ]);

            DB::commit();
            
            return redirect()->route('dashboard.admin')
                ->with('success', '✅ Licencia creada correctamente');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear licencia: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return back()
                ->withInput()
                ->with('error', '❌ Error al crear la licencia: ' . $e->getMessage());
        }
    }

    /**
     * Actualiza los datos de una licencia (SOLO ADMIN)
     */
    public function actualizar(Request $request, $id)
{
    // Verificar que sea ADMIN
    if (auth()->user()->role !== 'admin') {
        return response()->json([
            'success' => false,
            'message' => '⛔ No tienes permisos para realizar esta acción. Solo administradores.'
        ], 403);
    }

    try {
        $validated = $request->validate([
            'clave' => 'required|string|max:45',
            'estado' => 'required|string|in:Activa,Inactiva,Vencida,Por vencer',
            'idSoftware' => 'required|exists:software,idSoftware',
            'capacidad' => 'nullable|integer|min:1',
            'descripcion' => 'nullable|string|max:255',
            'fecha_activacion' => 'required|date',
            'fecha_vencimiento' => 'required|date',
        ]);

        DB::beginTransaction();

        // Verificar que la licencia existe
        $licencia = DB::table('licencia')
            ->where('idLicencia', $id)
            ->first();

        if (!$licencia) {
            throw new \Exception('Licencia no encontrada');
        }

        // Actualizar la licencia
        DB::table('licencia')
            ->where('idLicencia', $id)
            ->update([
                'Clave' => $validated['clave'],
                'estadoLic' => $validated['estado'],
                'idSoftware' => $validated['idSoftware'],
                'CapacidadLicencia' => $validated['capacidad'],
                'DescripcionLicencia' => $validated['descripcion'],
                'Fechacompra' => $validated['fecha_activacion'],
                'Fechavencimiento' => $validated['fecha_vencimiento']
            ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => '✅ Licencia actualizada correctamente'
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error de validación: ' . json_encode($e->errors())
        ], 422);
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error al actualizar licencia ID ' . $id . ': ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Error al actualizar: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * Elimina una licencia (SOLO ADMIN)
     */
    public function destroy($id)
    {
        // Verificar que sea ADMIN
        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => '⛔ No tienes permisos para realizar esta acción. Solo administradores.'
            ], 403);
        }

        try {
            DB::beginTransaction();

            $licencia = DB::table('licencia')
                ->where('idLicencia', $id)
                ->first();

            if (!$licencia) {
                return response()->json([
                    'success' => false,
                    'message' => 'Licencia no encontrada'
                ], 404);
            }

            // Verificar si tiene asignaciones
            $asignaciones = DB::table('asignacion_licencia')
                ->where('idLicencia', $id)
                ->count();

            if ($asignaciones > 0) {
                return response()->json([
                    'success' => false,
                    'message' => '❌ No se puede eliminar la licencia porque tiene ' . $asignaciones . ' artículo(s) asignado(s).'
                ], 400);
            }

            // Eliminar la licencia
            DB::table('licencia')
                ->where('idLicencia', $id)
                ->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => '✅ Licencia eliminada correctamente'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al eliminar licencia ID ' . $id . ': ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar: ' . $e->getMessage()
            ], 500);
        }
    }
}