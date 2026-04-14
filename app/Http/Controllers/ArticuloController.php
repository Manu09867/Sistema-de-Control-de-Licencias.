<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash; 

class ArticuloController extends Controller
{
    /**
     * Muestra el detalle de un artículo (accesible para todos)
     * Ahora recibe serie en lugar de RP
     */
    public function show($serie)
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
            FROM articulo a
            INNER JOIN producto p ON a.idProducto = p.idProducto
            INNER JOIN tipo_producto tp ON p.idTipo_Producto = tp.idTipo_Producto
            INNER JOIN detalle_licitacion dl ON a.idDetalle_Licitacion = dl.idDetalle_Licitacion
            INNER JOIN licitacion l ON dl.idLicitacion = l.idLicitacion
            INNER JOIN proveedor pr ON l.idProveedor = pr.idProveedor
            LEFT JOIN area ar ON a.idArea = ar.idArea
            LEFT JOIN grupo_articulo ga ON a.idArticulo = ga.idArticulo
            LEFT JOIN grupo g ON ga.idGrupo = g.idGrupo
            WHERE a.serie = ?
        ", [$serie]);

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
            FROM licencia lic
            INNER JOIN asignacion_licencia al ON lic.idLicencia = al.idLicencia
            INNER JOIN software s ON lic.idSoftware = s.idSoftware
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
        $router = DB::select("SELECT * FROM articulo_router WHERE idArticulo = ?", [$articulo->idArticulo]);
        $switch = DB::select("SELECT * FROM articulo_switch WHERE idArticulo = ?", [$articulo->idArticulo]);

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
                $infoRed = (object) [
                    'MACR' => null,
                    'IpaddressR' => null,
                    'ObservacionR' => null
                ];
            } else {
                $infoRed = (object) [
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
        $tiposProducto = DB::table('tipo_producto')
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
                'articulos.*.serie' => 'required|string|max:45|unique:articulo,serie',
                'articulos.*.rp' => 'required|string|max:45',
                'articulos.*.estado' => 'required|string|max:45',
            ]);

            if ($validator->fails()) {
                throw new \Exception('Error de validación: ' . json_encode($validator->errors()));
            }
            // ===== FIN VALIDACIÓN =====

            // ===== VERIFICAR SERIES DUPLICADAS =====
            $seriesNuevas = array_column($datos['articulos'], 'serie');

            $seriesExistentes = DB::table('articulo')
                ->whereIn('serie', $seriesNuevas)
                ->pluck('serie')
                ->toArray();

            if (!empty($seriesExistentes)) {
                return response()->json([
                    'success' => false,
                    'message' => '❌ Ya existen artículos con los siguientes números de serie: ' . implode(', ', $seriesExistentes) . '. No se guardó ningún artículo.'
                ], 422);
            }
            // ===== FIN VERIFICACIÓN SERIES =====

            // Determinar el total a guardar (con o sin IVA)
            $totalAGuardar = $datos['detalle']['aplicar_iva'] ? $datos['detalle']['total'] : $datos['detalle']['subtotal'];
            
            // Calcular precio con IVA para guardar en el detalle
            $precioConIVA = $datos['detalle']['aplicar_iva'] ? $datos['detalle']['precio_unitario'] * 1.16 : $datos['detalle']['precio_unitario'];

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
                    'Total' => $totalAGuardar,
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
                'PrecioU' => $precioConIVA,
                'Subtotal' => $totalAGuardar
            ]);

            // Si NO es nueva licitación (es existente), sumar al total
            if (!$esNuevaLicitacion) {
                DB::table('licitacion')
                    ->where('idLicitacion', $licitacionId)
                    ->increment('Total', $totalAGuardar);
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
            return response()->json([
                'success' => true,
                'message' => '✅ Artículo(s) creado(s) correctamente. Total: ' . count($datos['articulos']) . ' unidades.',
                'redirect' => route('dashboard.admin')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear artículo: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => '❌ Error al crear el artículo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualiza los datos de un artículo (SOLO ADMIN)
     * Ahora recibe serie en lugar de id
     */
    public function actualizar(Request $request, $serie)
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

            // OBTENER ARTÍCULO POR SERIE (único)
            $articuloActual = DB::table('articulo')
                ->where('serie', $serie)
                ->first();

            if (!$articuloActual) {
                throw new \Exception('Artículo no encontrado');
            }

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

            // 1. Actualizar artículo (usando serie como identificador)
            DB::table('articulo')
                ->where('serie', $serie)
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

            // 3. Manejar GRUPO (usando idArticulo)
            if (array_key_exists('nombreGrupo', $validated)) {
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
                            ['idArticulo' => $articuloActual->idArticulo],
                            ['idGrupo' => $idGrupo]
                        );
                } else {
                    DB::table('grupo_articulo')->where('idArticulo', $articuloActual->idArticulo)->delete();
                }
            }

            // 4. Manejar INFORMACIÓN DE RED (usando idArticulo)
            if (!empty($validated['tipoRed'])) {
                $tablaRed = $validated['tipoRed'] === 'router' ? 'articulo_router' : 'articulo_switch';
                $campoMac = $validated['tipoRed'] === 'router' ? 'MACR' : 'MACSw';
                $campoIp = $validated['tipoRed'] === 'router' ? 'IpaddressR' : 'IpaddressSw';
                $campoObs = $validated['tipoRed'] === 'router' ? 'ObservacionR' : 'ObservacionSw';

                $existe = DB::table($tablaRed)->where('idArticulo', $articuloActual->idArticulo)->exists();

                $dataRed = [
                    $campoMac => $validated['mac'] ?? null,
                    $campoIp => $validated['ip'] ?? null,
                    $campoObs => $validated['observacion'] ?? null,
                ];

                if ($existe) {
                    DB::table($tablaRed)->where('idArticulo', $articuloActual->idArticulo)->update($dataRed);
                } else {
                    $dataRed['idArticulo'] = $articuloActual->idArticulo;
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
     * Ahora recibe serie en lugar de id
     */
    public function destroy(Request $request, $serie)
    {
        // VERIFICAR QUE SEA ADMIN
        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => '⛔ No tienes permisos para realizar esta acción. Solo administradores.'
            ], 403);
        }

        // VERIFICAR CONTRASEÑA
        if (!Hash::check($request->password, auth()->user()->password)) {
            return response()->json([
                'success' => false,
                'message' => '❌ Contraseña incorrecta'
            ], 401);
        }

        try {
            DB::beginTransaction();

            // OBTENER ARTÍCULO POR SERIE
            $articulo = DB::table('articulo')
                ->where('serie', $serie)
                ->first();

            if (!$articulo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Artículo no encontrado'
                ], 404);
            }

            $detalleId = $articulo->idDetalle_Licitacion;
            
            // Obtener el detalle ANTES de modificar nada
            $detalle = DB::table('detalle_licitacion')
                ->where('idDetalle_Licitacion', $detalleId)
                ->first();
            
            if (!$detalle) {
                return response()->json([
                    'success' => false,
                    'message' => 'Detalle de licitación no encontrado'
                ], 404);
            }
            
            $licitacionId = $detalle->idLicitacion;
            
            // CONTAR cuántos artículos quedan en este detalle (incluyendo el actual)
            $articulosRestantes = DB::table('articulo')
                ->where('idDetalle_Licitacion', $detalleId)
                ->count();

            // PRIMERO: Eliminar relaciones del artículo
            DB::table('grupo_articulo')->where('idArticulo', $articulo->idArticulo)->delete();
            DB::table('articulo_router')->where('idArticulo', $articulo->idArticulo)->delete();
            DB::table('articulo_switch')->where('idArticulo', $articulo->idArticulo)->delete();
            DB::table('asignacion_licencia')->where('idArticulo', $articulo->idArticulo)->delete();

            // SEGUNDO: Eliminar el artículo
            DB::table('articulo')->where('serie', $serie)->delete();

            // TERCERO: Actualizar o eliminar el detalle
            if ($articulosRestantes <= 1) {
                // Era el último artículo, eliminar el detalle
                DB::table('detalle_licitacion')
                    ->where('idDetalle_Licitacion', $detalleId)
                    ->delete();
            } else {
                // Quedan más artículos, actualizar cantidad y subtotal
                $nuevaCantidad = $articulosRestantes - 1;
                $nuevoSubtotal = $nuevaCantidad * $detalle->PrecioU;
                
                DB::table('detalle_licitacion')
                    ->where('idDetalle_Licitacion', $detalleId)
                    ->update([
                        'Cantidad' => $nuevaCantidad,
                        'Subtotal' => $nuevoSubtotal
                    ]);
            }

            // CUARTO: Recalcular el total de la licitación
            $nuevoTotal = DB::table('detalle_licitacion')
                ->where('idLicitacion', $licitacionId)
                ->sum('Subtotal');

            DB::table('licitacion')
                ->where('idLicitacion', $licitacionId)
                ->update(['Total' => $nuevoTotal]);

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