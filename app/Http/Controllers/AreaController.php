<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AreaController extends Controller
{
    /**
     * Obtener todas las áreas
     */
    public function index()
    {
        $areas = DB::table('area')->orderBy('NombreArea')->get();
        return response()->json($areas);
    }

    /**
     * Guardar una nueva área
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
                'nombre' => 'required|string|max:100|unique:area,NombreArea'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $id = DB::table('area')->insertGetId([
                'NombreArea' => $request->nombre
            ]);

            return response()->json([
                'success' => true,
                'message' => '✅ Área agregada correctamente',
                'id' => $id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ Error al agregar el área: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar un área
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
                'nombre' => 'required|string|max:100|unique:area,NombreArea,' . $id . ',idArea'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            DB::table('area')
                ->where('idArea', $id)
                ->update(['NombreArea' => $request->nombre]);

            return response()->json([
                'success' => true,
                'message' => '✅ Área actualizada correctamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ Error al actualizar el área: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar un área
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
            $articulosAsignados = DB::table('articulo')->where('idArea', $id)->count();
            
            if ($articulosAsignados > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "❌ No se puede eliminar el área porque tiene {$articulosAsignados} artículo(s) asignado(s)."
                ], 400);
            }
            
            DB::table('area')->where('idArea', $id)->delete();
            return response()->json([
                'success' => true,
                'message' => '✅ Área eliminada correctamente'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ Error al eliminar: ' . $e->getMessage()
            ], 500);
        }
    }
}