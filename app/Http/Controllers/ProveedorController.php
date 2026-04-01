<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProveedorController extends Controller
{
    public function index()
    {
        $proveedores = DB::select("
            SELECT 
                p.idProveedor,
                p.Nombre,
                p.RFC,
                p.Telefono,
                p.Direccion,
                p.correo,
                (SELECT COUNT(*) FROM licitacion WHERE idProveedor = p.idProveedor) AS total_licitaciones
            FROM proveedor p
            ORDER BY p.idProveedor DESC
            LIMIT 50
        ");

        return view('proveedores.index', compact('proveedores'));
    }

    public function show($id)
    {
        $proveedor = DB::select("
            SELECT 
                p.idProveedor,
                p.Nombre,
                p.RFC,
                p.Telefono,
                p.Direccion,
                p.correo
            FROM proveedor p
            WHERE p.idProveedor = ?
        ", [$id]);

        if (empty($proveedor)) {
            abort(404, 'Proveedor no encontrado');
        }

        $proveedor = $proveedor[0];

        return view('proveedores.show', compact('proveedor'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos'
            ], 403);
        }

        try {
            $validated = $request->validate([
                'nombre' => 'required|string|max:45',
                'rfc' => 'nullable|string|max:20',
                'telefono' => 'nullable|string|max:20',
                'direccion' => 'nullable|string|max:900',
                'correo' => 'nullable|email|max:50'
            ]);

            $existe = DB::table('proveedor')
                ->whereRaw('LOWER(Nombre) = ?', [strtolower($validated['nombre'])])
                ->exists();

            if ($existe) {
                return response()->json([
                    'success' => false,
                    'message' => '❌ Este proveedor ya existe'
                ], 400);
            }

            $id = DB::table('proveedor')->insertGetId([
                'Nombre' => $validated['nombre'],
                'RFC' => $validated['rfc'] ?? null,
                'Telefono' => $validated['telefono'] ?? null,
                'Direccion' => $validated['direccion'] ?? null,
                'correo' => $validated['correo'] ?? null
            ]);

            return response()->json([
                'success' => true,
                'id' => $id,
                'message' => '✅ Proveedor agregado correctamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al agregar: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos'
            ], 403);
        }

        try {
            $validated = $request->validate([
                'nombre' => 'required|string|max:45',
                'rfc' => 'nullable|string|max:20',
                'telefono' => 'nullable|string|max:20',
                'direccion' => 'nullable|string|max:900',
                'correo' => 'nullable|email|max:50'
            ]);

            DB::table('proveedor')
                ->where('idProveedor', $id)
                ->update([
                    'Nombre' => $validated['nombre'],
                    'RFC' => $validated['rfc'] ?? null,
                    'Telefono' => $validated['telefono'] ?? null,
                    'Direccion' => $validated['direccion'] ?? null,
                    'correo' => $validated['correo'] ?? null
                ]);

            return response()->json([
                'success' => true,
                'message' => '✅ Proveedor actualizado correctamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request, $id)
{
    if (auth()->user()->role !== 'admin') {
        return response()->json([
            'success' => false,
            'message' => 'No tienes permisos'
        ], 403);
    }

    try {
        if (!Hash::check($request->password, auth()->user()->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Contraseña incorrecta'
            ], 401);
        }

        // Verificar si tiene licitaciones asociadas
        $licitacionesAsociadas = DB::table('licitacion')
            ->where('idProveedor', $id)
            ->count();

        if ($licitacionesAsociadas > 0) {
            return response()->json([
                'success' => false,
                'message' => "❌ No se puede eliminar el proveedor porque tiene {$licitacionesAsociadas} licitación(es) asociada(s)."
            ], 400);
        }

        // Verificar si tiene productos asociados (a través de licitaciones con hardware)
        $productosAsociados = DB::table('detalle_licitacion as dl')
            ->join('licitacion as l', 'dl.idLicitacion', '=', 'l.idLicitacion')
            ->where('l.idProveedor', $id)
            ->where('dl.TipoItem', 'HARDWARE')
            ->count();

        if ($productosAsociados > 0) {
            return response()->json([
                'success' => false,
                'message' => "❌ No se puede eliminar el proveedor porque tiene {$productosAsociados} producto(s) asociado(s) a través de licitaciones."
            ], 400);
        }

        // Verificar si tiene software asociado
        $softwareAsociado = DB::table('detalle_licitacion as dl')
            ->join('licitacion as l', 'dl.idLicitacion', '=', 'l.idLicitacion')
            ->where('l.idProveedor', $id)
            ->where('dl.TipoItem', 'SOFTWARE')
            ->count();

        if ($softwareAsociado > 0) {
            return response()->json([
                'success' => false,
                'message' => "❌ No se puede eliminar el proveedor porque tiene {$softwareAsociado} software(s) asociado(s) a través de licitaciones."
            ], 400);
        }

        DB::table('proveedor')->where('idProveedor', $id)->delete();

        return response()->json([
            'success' => true,
            'message' => '✅ Proveedor eliminado correctamente'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al eliminar: ' . $e->getMessage()
        ], 500);
    }
}
}