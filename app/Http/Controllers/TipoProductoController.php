<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TipoProductoController extends Controller
{
    /**
     * Obtener todos los tipos de producto
     */
    public function index()
    {
        $tipos = DB::table('tipo_producto')->orderBy('NombreTP')->get();
        return response()->json($tipos);
    }

    /**
     * Guardar un nuevo tipo de producto
     */
    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos'
            ], 403);
        }

        try {
            $validator = Validator::make($request->all(), [
                'nombre' => 'required|string|max:45|unique:tipo_producto,NombreTP'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $id = DB::table('tipo_producto')->insertGetId([
                'NombreTP' => $request->nombre
            ]);

            return response()->json([
                'success' => true,
                'message' => '✅ Tipo de producto agregado correctamente',
                'id' => $id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ Error al agregar el tipo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar un tipo de producto
     */
    public function update(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos'
            ], 403);
        }

        try {
            $validator = Validator::make($request->all(), [
                'nombre' => 'required|string|max:45|unique:tipo_producto,NombreTP,' . $id . ',idTipo_Producto'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            DB::table('tipo_producto')
                ->where('idTipo_Producto', $id)
                ->update(['NombreTP' => $request->nombre]);

            return response()->json([
                'success' => true,
                'message' => '✅ Tipo de producto actualizado correctamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ Error al actualizar el tipo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar un tipo de producto
     */
    public function destroy($id)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos'
            ], 403);
        }

        try {
            $productosAsociados = DB::table('producto')->where('idTipo_Producto', $id)->count();
            
            if ($productosAsociados > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "❌ No se puede eliminar el tipo porque tiene {$productosAsociados} producto(s) asociado(s)."
                ], 400);
            }
            
            DB::table('tipo_producto')->where('idTipo_Producto', $id)->delete();
            return response()->json([
                'success' => true,
                'message' => '✅ Tipo eliminado correctamente'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ Error al eliminar: ' . $e->getMessage()
            ], 500);
        }
    }
}