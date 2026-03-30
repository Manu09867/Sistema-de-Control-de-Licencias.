<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AreaController extends Controller
{
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