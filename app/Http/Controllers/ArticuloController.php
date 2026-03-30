<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ArticuloController extends Controller
{
    /**
     * Muestra el detalle de un artículo (accesible para todos)
     */
    public function show($rp)
    {
        $articulo = DB::select("
            SELECT 
                a.idArticulo,
                a.idArea,
                a.RP,
                a.serie,
                a.estado,
                p.NombreP AS producto,
                p.Marca,
                p.Modelo,
                tp.NombreTP AS tipo_producto,
                ar.NombreArea,
                l.folio AS folio_licitacion,
                l.DescripcionL AS descripcion_licitacion,
                pr.Nombre AS proveedor,
                g.NombreGrupo,
                g.TipoGrupo,
                ga.idGrupo,
                dl.idDetalle_Licitacion,
                dl.Cantidad,
                dl.PrecioU,
                dl.Subtotal
            FROM Articulo a
            INNER JOIN Producto p ON a.idProducto = p.idProducto
            INNER JOIN Tipo_Producto tp ON p.idTipo_Producto = tp.idTipo_Producto
            INNER JOIN Detalle_Licitacion dl ON a.idDetalle_Licitacion = dl.idDetalle_Licitacion
            INNER JOIN Licitacion l ON dl.idLicitacion = l.idLicitacion
            INNER JOIN Proveedor pr ON l.idProveedor = pr.idProveedor
            LEFT JOIN Area ar ON a.idArea = ar.idArea
            LEFT JOIN Grupo_Articulo ga ON a.idArticulo = ga.idArticulo
            LEFT JOIN Grupo g ON ga.idGrupo = g.idGrupo
            WHERE a.RP = ?
        ", [$rp]);

        if (empty($articulo)) {
            abort(404, 'Artículo no encontrado');
        }

        $articulo = $articulo[0];

        $licencias = DB::select("
            SELECT 
                lic.idLicencia,
                lic.Clave,
                lic.Fechacompra,
                lic.Fechavencimiento,
                lic.estadoLic,
                s.Nombre AS software
            FROM Licencia lic
            INNER JOIN Asignacion_Licencia al ON lic.idLicencia = al.idLicencia
            INNER JOIN Software s ON lic.idSoftware = s.idSoftware
            WHERE al.idArticulo = ?
        ", [$articulo->idArticulo]);

        // DETECTAR TIPO DE EQUIPO POR EL NOMBRE DEL PRODUCTO
        $productoLower = strtolower($articulo->producto ?? '');
        $tipoDetectado = null;
        
        if (strpos($productoLower, 'router') !== false) {
            $tipoDetectado = 'router';
        } elseif (strpos($productoLower, 'switch') !== false) {
            $tipoDetectado = 'switch';
        }

        // Obtener información de red (router o switch)
        $router = DB::select("SELECT * FROM Articulo_Router WHERE idArticulo = ?", [$articulo->idArticulo]);
        $switch = DB::select("SELECT * FROM Articulo_Switch WHERE idArticulo = ?", [$articulo->idArticulo]);
        
        $infoRed = null;
        $tipoRed = null;
        $tieneRegistroRed = false;

        // Si hay datos en router
        if (!empty($router)) {
            $infoRed = $router[0];
            $tipoRed = 'router';
            $tieneRegistroRed = true;
        } 
        // Si hay datos en switch
        elseif (!empty($switch)) {
            $infoRed = $switch[0];
            $tipoRed = 'switch';
            $tieneRegistroRed = true;
        } 
        // Si no hay datos pero el producto sugiere que es router/switch
        elseif ($tipoDetectado) {
            $tipoRed = $tipoDetectado;
            $tieneRegistroRed = true;
            // Crear objeto vacío para mostrar campos N/A
            if ($tipoRed === 'router') {
                $infoRed = (object)[
                    'MACR' => null,
                    'IpaddressR' => null,
                    'ObservacionR' => null
                ];
            } else {
                $infoRed = (object)[
                    'MACSw' => null,
                    'IpaddressSw' => null,
                    'ObservacionSw' => null
                ];
            }
        }

        // Obtener áreas para el select
        $areas = DB::table('area')
            ->orderBy('NombreArea')
            ->get();

        // Obtener todos los grupos
        $grupos = DB::table('grupo')
            ->orderBy('NombreGrupo')
            ->get();

        // Obtener tipos de grupo únicos para el select
        $tiposGrupo = DB::table('grupo')
            ->select('TipoGrupo')
            ->distinct()
            ->whereNotNull('TipoGrupo')
            ->pluck('TipoGrupo');

        return view('articulo-detalle', compact(
            'articulo', 
            'licencias', 
            'areas', 
            'grupos', 
            'tiposGrupo',
            'infoRed',
            'tipoRed',
            'tieneRegistroRed'
        ));
    }

    /**
     * Muestra el formulario para crear un nuevo artículo
     */
    public function create()
    {
        $tiposProducto = DB::table('Tipo_Producto')
            ->orderBy('NombreTP')
            ->get();
            
        $proveedores = DB::table('proveedor')
            ->orderBy('Nombre')
            ->get();
            
        $licitaciones = DB::table('licitacion as l')
            ->leftJoin('proveedor as p', 'l.idProveedor', '=', 'p.idProveedor')
            ->select('l.idLicitacion', 'l.folio', 'l.DescripcionL as descripcion', 'p.Nombre as proveedor_nombre')
            ->orderBy('l.folio')
            ->get();
            
        return view('articulos.crear', compact('tiposProducto', 'proveedores', 'licitaciones'));
    }

    /**
     * Guarda un nuevo artículo en la base de datos
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Decodificar los datos del formulario
            $datos = json_decode($request->datos_completos, true);
            
            if (!$datos) {
                throw new \Exception('No se recibieron datos válidos');
            }

            Log::info('Datos recibidos en store:', $datos);

            // ===== VALIDACIÓN DE CAMPOS REQUERIDOS =====
            $validator = Validator::make($datos, [
                'tipo_producto.id' => 'required',
                'tipo_producto.nombre' => 'required|string',
                'producto.nombre' => 'required|string|max:45',
                'producto.marca' => 'nullable|string|max:45',
                'producto.modelo' => 'nullable|string|max:45',
                'tipo_licitacion' => 'required|in:existente,nueva',
                'proveedor.id' => 'required_if:tipo_licitacion,nueva',
                'proveedor.nombre' => 'required_if:tipo_licitacion,nueva|string',
                'licitacion.folio' => 'required_if:tipo_licitacion,nueva|string|max:50',
                'licitacion.id' => 'required_if:tipo_licitacion,existente',
                'detalle.cantidad' => 'required|integer|min:1',
                'detalle.precio_unitario' => 'required|numeric|min:0',
                'articulos' => 'required|array|min:1',
                'articulos.*.serie' => 'required|string|max:45',
                'articulos.*.rp' => 'required|string|max:45',
                'articulos.*.estado' => 'required|string|max:45',
            ]);

            if ($validator->fails()) {
                throw new \Exception('Error de validación: ' . json_encode($validator->errors()));
            }
            // ===== FIN VALIDACIÓN =====

            // ===== 1. TIPO DE PRODUCTO =====
            $tipoProductoId = $datos['tipo_producto']['id'];

            // ===== 2. PRODUCTO =====
            $productoId = DB::table('producto')->insertGetId([
                'NombreP' => $datos['producto']['nombre'],
                'Marca' => $datos['producto']['marca'] ?? null,
                'Modelo' => $datos['producto']['modelo'] ?? null,
                'idTipo_Producto' => $tipoProductoId
            ]);

            // ===== 3. PROVEEDOR (si existe) =====
            $proveedorId = null;
            if (isset($datos['proveedor']['id'])) {
                $proveedorId = $datos['proveedor']['id'];
            }

            // ===== 4. LICITACIÓN =====
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

            // ===== 5. DETALLE DE LICITACIÓN =====
            $detalleId = DB::table('detalle_licitacion')->insertGetId([
                'TipoItem' => 'HARDWARE',
                'idLicitacion' => $licitacionId,
                'idSoftware' => null,
                'idProducto' => $productoId,
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

            // ===== 6. ARTÍCULOS (varios por cantidad) =====
            foreach ($datos['articulos'] as $articulo) {
                DB::table('articulo')->insert([
                    'serie' => $articulo['serie'],
                    'estado' => $articulo['estado'],
                    'RP' => $articulo['rp'],
                    'idProducto' => $productoId,
                    'idDetalle_Licitacion' => $detalleId,
                    'idArea' => null
                ]);
            }

            DB::commit();
            
            return redirect()->route('dashboard.admin')
                ->with('success', '✅ Artículo(s) creado(s) correctamente. Total: ' . count($datos['articulos']) . ' unidades.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear artículo: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return back()
                ->withInput()
                ->with('error', '❌ Error al crear el artículo: ' . $e->getMessage());
        }
    }

    /**
     * Actualiza los datos de un artículo (SOLO ADMIN)
     */
    public function actualizar(Request $request, $id)
    {
        // VERIFICAR QUE SEA ADMIN
        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => '⛔ No tienes permisos para realizar esta acción. Solo administradores.'
            ], 403);
        }

        try {
            $validated = $request->validate([
                'serie' => 'required|string|max:45',
                'estado' => 'required|string|max:45',
                'idArea' => 'nullable|exists:area,idArea',
                'nombreGrupo' => 'nullable|string|max:100',
                'tipoGrupo' => 'nullable|string|max:45',
                'tipoRed' => 'nullable|string|in:router,switch',
                'mac' => 'nullable|string|max:90',
                'ip' => 'nullable|ip|max:45',
                'observacion' => 'nullable|string|max:90',
                'cantidad' => 'nullable|integer|min:1',
                'precio_unitario' => 'nullable|numeric|min:0',
            ]);

            DB::beginTransaction();

            // OBTENER DATOS ACTUALES DEL ARTÍCULO Y SU DETALLE
            $articuloActual = DB::table('articulo')
                ->where('idArticulo', $id)
                ->first();
            
            $detalleActual = DB::table('detalle_licitacion')
                ->where('idDetalle_Licitacion', $articuloActual->idDetalle_Licitacion)
                ->first();

            // Calcular nuevo subtotal si se cambiaron cantidad o precio
            $nuevoSubtotal = null;
            if (isset($validated['cantidad']) || isset($validated['precio_unitario'])) {
                $cantidad = $validated['cantidad'] ?? $detalleActual->Cantidad;
                $precio = $validated['precio_unitario'] ?? $detalleActual->PrecioU;
                $nuevoSubtotal = $cantidad * $precio;
            }

            // 1. Actualizar artículo
            DB::table('articulo')
                ->where('idArticulo', $id)
                ->update([
                    'serie' => $validated['serie'],
                    'estado' => $validated['estado'],
                    'idArea' => $validated['idArea'],
                ]);

            // 2. Actualizar detalle de licitación si se cambiaron cantidad/precio
            if ($nuevoSubtotal !== null && $nuevoSubtotal != $detalleActual->Subtotal) {
                // Restar el subtotal viejo del total de la licitación
                DB::table('licitacion')
                    ->where('idLicitacion', $detalleActual->idLicitacion)
                    ->decrement('Total', $detalleActual->Subtotal);
                
                // Actualizar detalle
                DB::table('detalle_licitacion')
                    ->where('idDetalle_Licitacion', $detalleActual->idDetalle_Licitacion)
                    ->update([
                        'Cantidad' => $validated['cantidad'] ?? $detalleActual->Cantidad,
                        'PrecioU' => $validated['precio_unitario'] ?? $detalleActual->PrecioU,
                        'Subtotal' => $nuevoSubtotal
                    ]);
                
                // Sumar el nuevo subtotal al total de la licitación
                DB::table('licitacion')
                    ->where('idLicitacion', $detalleActual->idLicitacion)
                    ->increment('Total', $nuevoSubtotal);
            }

            // 3. Manejar GRUPO (VERSIÓN DEFINITIVA - NO ELIMINA NUNCA A MENOS QUE SE BORRE EXPLÍCITAMENTE)
            // Verificar si el campo nombreGrupo fue enviado en la petición
            if (array_key_exists('nombreGrupo', $validated)) {
                // Si el usuario envió un nombre de grupo NO VACÍO
                if (!empty($validated['nombreGrupo'])) {
                    $grupoExistente = DB::table('grupo')
                        ->where('NombreGrupo', $validated['nombreGrupo'])
                        ->first();

                    if ($grupoExistente) {
                        $idGrupo = $grupoExistente->idGrupo;
                        if (!empty($validated['tipoGrupo'])) {
                            DB::table('grupo')
                                ->where('idGrupo', $idGrupo)
                                ->update(['TipoGrupo' => $validated['tipoGrupo']]);
                        }
                    } else {
                        $idGrupo = DB::table('grupo')->insertGetId([
                            'NombreGrupo' => $validated['nombreGrupo'],
                            'TipoGrupo' => $validated['tipoGrupo'] ?? 'EQUIPO_COMPUTO'
                        ]);
                    }

                    DB::table('grupo_articulo')
                        ->updateOrInsert(
                            ['idArticulo' => $id],
                            ['idGrupo' => $idGrupo]
                        );
                } else {
                    // El usuario envió el campo vacío EXPLÍCITAMENTE - eliminar grupo
                    DB::table('grupo_articulo')
                        ->where('idArticulo', $id)
                        ->delete();
                }
            }
            // Si el campo NO fue enviado en la petición, NO hacemos nada (preservamos el grupo actual)

            // 4. Manejar INFORMACIÓN DE RED
            if (!empty($validated['tipoRed'])) {
                $tablaRed = $validated['tipoRed'] === 'router' ? 'Articulo_Router' : 'Articulo_Switch';
                $campoMac = $validated['tipoRed'] === 'router' ? 'MACR' : 'MACSw';
                $campoIp = $validated['tipoRed'] === 'router' ? 'IpaddressR' : 'IpaddressSw';
                $campoObs = $validated['tipoRed'] === 'router' ? 'ObservacionR' : 'ObservacionSw';

                $existe = DB::table($tablaRed)
                    ->where('idArticulo', $id)
                    ->exists();

                $dataRed = [
                    $campoMac => $validated['mac'] ?? null,
                    $campoIp => $validated['ip'] ?? null,
                    $campoObs => $validated['observacion'] ?? null,
                ];

                if ($existe) {
                    DB::table($tablaRed)
                        ->where('idArticulo', $id)
                        ->update($dataRed);
                } else {
                    $dataRed['idArticulo'] = $id;
                    DB::table($tablaRed)->insert($dataRed);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => '✅ Artículo actualizado correctamente'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación: ' . json_encode($e->errors())
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Elimina un artículo (SOLO ADMIN)
     */
    public function destroy($id)
    {
        // VERIFICAR QUE SEA ADMIN
        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => '⛔ No tienes permisos para realizar esta acción. Solo administradores.'
            ], 403);
        }

        try {
            DB::beginTransaction();

            $articulo = DB::table('articulo')
                ->where('idArticulo', $id)
                ->first();

            if (!$articulo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Artículo no encontrado'
                ], 404);
            }

            $detalle = DB::table('detalle_licitacion')
                ->where('idDetalle_Licitacion', $articulo->idDetalle_Licitacion)
                ->first();

            // Restar el subtotal del total de la licitación
            DB::table('licitacion')
                ->where('idLicitacion', $detalle->idLicitacion)
                ->decrement('Total', $detalle->Subtotal);

            // Eliminar relaciones
            DB::table('grupo_articulo')->where('idArticulo', $id)->delete();
            DB::table('Articulo_Router')->where('idArticulo', $id)->delete();
            DB::table('Articulo_Switch')->where('idArticulo', $id)->delete();
            
            // Eliminar artículo
            DB::table('articulo')->where('idArticulo', $id)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => '✅ Artículo eliminado correctamente'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar: ' . $e->getMessage()
            ], 500);
        }
    }
}