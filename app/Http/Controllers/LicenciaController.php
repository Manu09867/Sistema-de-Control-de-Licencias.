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
                (SELECT COUNT(*) FROM asignacion_licencia WHERE idLicencia = l.idLicencia) AS total_articulos
            FROM licencia l
            LEFT JOIN software s ON l.idSoftware = s.idSoftware
            LEFT JOIN detalle_licitacion dl ON l.idDetalle_Licitacion = dl.idDetalle_Licitacion
            LEFT JOIN licitacion lic ON dl.idLicitacion = lic.idLicitacion
            LEFT JOIN proveedor pr ON lic.idProveedor = pr.idProveedor
            WHERE l.Clave = ?
        ", [$clave]);

        if (empty($licencia)) {
            abort(404, 'Licencia no encontrada');
        }

        $licencia = $licencia[0];

        $articulos = DB::select("
            SELECT 
                a.idArticulo,
                a.serie,
                a.RP,
                a.estado,
                p.NombreP AS producto,
                p.Marca AS marca,
                p.Modelo AS modelo,
                ar.NombreArea AS area,
                al.idAsignacion_Licencia AS idAsignacion,
                al.ObservacionAL AS observacion
            FROM articulo a
            INNER JOIN asignacion_licencia al ON a.idArticulo = al.idArticulo
            LEFT JOIN producto p ON a.idProducto = p.idProducto
            LEFT JOIN area ar ON a.idArea = ar.idArea
            WHERE al.idLicencia = ?
            ORDER BY a.idArticulo DESC
            LIMIT 200
        ", [$licencia->idLicencia]);

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

            $datos = json_decode($request->datos_completos, true);
            
            if (!$datos) {
                throw new \Exception('No se recibieron datos válidos');
            }

            Log::info('Datos recibidos en store licencia:', $datos);

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

            $softwareId = $datos['software']['id'];

            $proveedorId = null;
            if (isset($datos['proveedor']['id'])) {
                $proveedorId = $datos['proveedor']['id'];
            }

            $licitacionId = null;
            $esNuevaLicitacion = false;

            if ($datos['tipo_licitacion'] === 'existente') {
                $licitacionId = $datos['licitacion']['id'];
            } else {
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

            $detalleId = DB::table('detalle_licitacion')->insertGetId([
                'TipoItem' => 'SOFTWARE',
                'idLicitacion' => $licitacionId,
                'idSoftware' => $softwareId,
                'idProducto' => null,
                'Cantidad' => $datos['detalle']['cantidad'],
                'PrecioU' => $datos['detalle']['precio_unitario'],
                'Subtotal' => $datos['detalle']['subtotal']
            ]);

            if (!$esNuevaLicitacion) {
                DB::table('licitacion')
                    ->where('idLicitacion', $licitacionId)
                    ->increment('Total', $datos['detalle']['subtotal']);
            }

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

    public function actualizar(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => '⛔ Solo administradores'
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

            $licencia = DB::table('licencia')
                ->where('idLicencia', $id)
                ->first();

            if (!$licencia) {
                throw new \Exception('Licencia no encontrada');
            }

            $totalAsignados = DB::table('asignacion_licencia')
                ->where('idLicencia', $id)
                ->count();

            if (isset($validated['capacidad']) && $validated['capacidad'] !== null) {
                if ($validated['capacidad'] < $totalAsignados) {
                    throw new \Exception("La capacidad no puede ser menor a los artículos ya asignados ({$totalAsignados}). Actualmente hay {$totalAsignados} artículo(s) asignado(s).");
                }
            }

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

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function listarArticulos(Request $request)
    {
        $query = $request->q;
        $licenciaId = $request->licencia_id;
        
        $articulosAsignados = DB::table('asignacion_licencia')
            ->where('idLicencia', $licenciaId)
            ->pluck('idArticulo')
            ->toArray();

        $articulos = DB::table('articulo as a')
            ->leftJoin('producto as p', 'a.idProducto', '=', 'p.idProducto')
            ->select(
                'a.idArticulo',
                'a.serie',
                'a.RP',
                'p.NombreP as producto',
                'p.Marca as marca',
                'a.estado'
            )
            ->where('a.estado', '!=', 'Baja')
            ->when($query, function ($q) use ($query) {
                $q->where(function($sub) use ($query) {
                    $sub->where('a.serie', 'like', "%$query%")
                        ->orWhere('a.RP', 'like', "%$query%")
                        ->orWhere('p.NombreP', 'like', "%$query%")
                        ->orWhere('p.Marca', 'like', "%$query%");
                });
            })
            ->limit(100)
            ->get();
        
        foreach ($articulos as $articulo) {
            $articulo->ya_asignado = in_array($articulo->idArticulo, $articulosAsignados);
        }

        return response()->json($articulos);
    }

    public function asignarArticulo(Request $request)
    {
        try {
            $request->validate([
                'idLicencia' => 'required|exists:licencia,idLicencia',
                'idArticulo' => 'required|exists:articulo,idArticulo',
                'observacion' => 'nullable|string|max:80',
            ]);

            $existe = DB::table('asignacion_licencia')
                ->where('idLicencia', $request->idLicencia)
                ->where('idArticulo', $request->idArticulo)
                ->exists();

            if ($existe) {
                return response()->json([
                    'success' => false,
                    'message' => '⚠️ Este artículo ya tiene esta licencia asignada'
                ]);
            }

            $licencia = DB::table('licencia')
                ->where('idLicencia', $request->idLicencia)
                ->first();

            $totalAsignados = DB::table('asignacion_licencia')
                ->where('idLicencia', $request->idLicencia)
                ->count();

            if ($licencia->CapacidadLicencia && $totalAsignados >= $licencia->CapacidadLicencia) {
                return response()->json([
                    'success' => false,
                    'message' => '⚠️ Capacidad máxima alcanzada. Esta licencia solo permite ' . $licencia->CapacidadLicencia . ' equipo(s).'
                ]);
            }

            DB::table('asignacion_licencia')->insert([
                'idLicencia' => $request->idLicencia,
                'idArticulo' => $request->idArticulo,
                'ObservacionAL' => $request->observacion
            ]);

            return response()->json([
                'success' => true,
                'message' => '✅ Licencia asignada correctamente'
            ]);

        } catch (\Exception $e) {
            Log::error('Error asignando licencia: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al asignar licencia: ' . $e->getMessage()
            ], 500);
        }
    }

    public function eliminarAsignacion(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => '⛔ Solo administradores pueden eliminar asignaciones.'
            ], 403);
        }

        try {
            DB::beginTransaction();

            $asignacion = DB::table('asignacion_licencia')
                ->where('idAsignacion_Licencia', $id)
                ->first();

            if (!$asignacion) {
                return response()->json([
                    'success' => false,
                    'message' => 'Asignación no encontrada'
                ], 404);
            }

            DB::table('asignacion_licencia')
                ->where('idAsignacion_Licencia', $id)
                ->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => '✅ Asignación eliminada correctamente'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al eliminar asignación ID ' . $id . ': ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => '⛔ Solo administradores'
            ], 403);
        }

        try {
            DB::beginTransaction();

            $asignaciones = DB::table('asignacion_licencia')
                ->where('idLicencia', $id)
                ->count();

            if ($asignaciones > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar. Tiene ' . $asignaciones . ' artículo(s) asignado(s).'
                ]);
            }

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
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar'
            ], 500);
        }
    }
}