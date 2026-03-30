<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TipoProductoController extends Controller
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
            $productosAsociados = DB::table('producto')->where('idTipo_Producto', $id)->count();
            
            if ($productosAsociados > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "❌ No se puede eliminar el tipo porque tiene {$productosAsociados} producto(s) asociado(s)."
                ], 400);
            }
            
            DB::table('Tipo_Producto')->where('idTipo_Producto', $id)->delete();
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