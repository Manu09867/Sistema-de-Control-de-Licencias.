<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ArticuloController;
use App\Http\Controllers\BusquedaController;
use App\Http\Controllers\LicenciaController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\LogController;
use App\Http\Controllers\LicitacionController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\TipoProductoController;



Route::get('/', function () {
    return redirect('/login');
});

// Dashboard principal - redirige según el rol
Route::get('/dashboard', function () {
    if (auth()->user()->role === 'admin') {
        return redirect()->route('dashboard.admin');
    }
    return redirect()->route('dashboard.user');
})->middleware(['auth'])->name('dashboard');

// Dashboards por rol
Route::get('/dashboard/admin', [DashboardController::class, 'admin'])
    ->middleware(['auth', 'role:admin'])
    ->name('dashboard.admin');

Route::get('/dashboard/user', [DashboardController::class, 'user'])
    ->middleware(['auth', 'role:user'])
    ->name('dashboard.user');

// Rutas de perfil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ===== RUTAS DE ARTÍCULOS Y LICENCIAS =====
Route::get('/articulo/{rp}', [ArticuloController::class, 'show'])
    ->middleware('auth')
    ->name('articulo.show');

Route::get('/buscar-articulos', [BusquedaController::class, 'buscar'])
    ->middleware('auth')
    ->name('buscar.articulos');

Route::get('/licencia/{clave}', [LicenciaController::class, 'show'])
    ->middleware('auth')
    ->name('licencia.show');

// Verificar contraseña
Route::post('/verify-password', function (Request $request) {
    return response()->json([
        'valid' => Hash::check($request->password, Auth::user()->password)
    ]);
})->middleware('auth');

// ===== RUTAS DE ADMIN (SOLO ADMIN) =====
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

// ===== RUTAS PARA CREACIÓN (AUTH) =====
Route::middleware(['auth'])->prefix('articulos')->name('articulos.')->group(function () {
    Route::get('/crear', [ArticuloController::class, 'create'])->name('crear');
    Route::post('/', [ArticuloController::class, 'store'])->name('store');
});

Route::middleware(['auth'])->prefix('licencias')->name('licencias.')->group(function () {
    Route::get('/crear', [LicenciaController::class, 'create'])->name('crear');
    Route::post('/', [LicenciaController::class, 'store'])->name('store');
});

// ===== RUTAS PARA ACTUALIZAR (SOLO ADMIN) =====
Route::post('/articulo/{id}/actualizar', [ArticuloController::class, 'actualizar'])
    ->middleware(['auth', 'role:admin'])
    ->name('articulo.actualizar');

Route::post('/licencia/{id}/actualizar', [LicenciaController::class, 'actualizar'])
    ->middleware(['auth', 'role:admin'])
    ->name('licencia.actualizar');

// ===== RUTAS PARA ASIGNACIÓN DE LICENCIAS (AUTH) =====
Route::get('/articulos/lista', [LicenciaController::class, 'listarArticulos'])
    ->middleware('auth');

Route::post('/licencia/asignar-articulo', [LicenciaController::class, 'asignarArticulo'])
    ->middleware('auth');

// ===== RUTA PARA ELIMINAR ASIGNACIÓN (SOLO ADMIN) =====
Route::delete('/licencia/asignacion/{id}', [LicenciaController::class, 'eliminarAsignacion'])
    ->middleware(['auth', 'role:admin'])
    ->name('licencia.eliminar-asignacion');

// ===== RUTAS API PARA CATÁLOGOS (CON VALIDACIÓN) =====
Route::post('/api/tipos-producto', function (Request $request) {
    try {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100'
        ]);

        $existe = DB::table('Tipo_Producto')
            ->whereRaw('LOWER(NombreTP) = ?', [strtolower($validated['nombre'])])
            ->exists();
            
        if ($existe) {
            return response()->json([
                'success' => false,
                'message' => '❌ Este tipo de producto ya existe en la base de datos'
            ], 400);
        }

        $id = DB::table('Tipo_Producto')->insertGetId([
            'NombreTP' => $validated['nombre']
        ]);
        
        return response()->json([
            'success' => true,
            'id' => $id,
            'message' => '✅ Tipo de producto agregado correctamente'
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error de validación: ' . implode(', ', $e->errors())
        ], 422);
    } catch (\Exception $e) {
        \Log::error('Error en api/tipos-producto: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error interno del servidor'
        ], 500);
    }
})->middleware(['auth']);

Route::post('/api/proveedores', function (Request $request) {
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
                'message' => '❌ Este proveedor ya existe en la base de datos'
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
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error de validación: ' . implode(', ', $e->errors())
        ], 422);
    } catch (\Exception $e) {
        \Log::error('Error en api/proveedores: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error interno del servidor'
        ], 500);
    }
})->middleware(['auth']);

Route::post('/api/areas', function (Request $request) {
    try {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100'
        ]);

        $existe = DB::table('area')
            ->whereRaw('LOWER(NombreArea) = ?', [strtolower($validated['nombre'])])
            ->exists();
            
        if ($existe) {
            return response()->json([
                'success' => false,
                'message' => '❌ Esta área ya existe en la base de datos'
            ], 400);
        }

        $id = DB::table('area')->insertGetId([
            'NombreArea' => $validated['nombre']
        ]);
        
        return response()->json([
            'success' => true,
            'id' => $id,
            'message' => '✅ Área agregada correctamente'
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error de validación: ' . implode(', ', $e->errors())
        ], 422);
    } catch (\Exception $e) {
        \Log::error('Error en api/areas: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error interno del servidor'
        ], 500);
    }
})->middleware(['auth']);

Route::post('/api/softwares', function (Request $request) {
    try {
        $validated = $request->validate([
            'nombre' => 'required|string|max:45',
            'tipo' => 'nullable|string|max:45'
        ]);

        $existe = DB::table('software')
            ->whereRaw('LOWER(Nombre) = ?', [strtolower($validated['nombre'])])
            ->exists();
            
        if ($existe) {
            return response()->json([
                'success' => false,
                'message' => '❌ Este software ya existe en la base de datos'
            ], 400);
        }

        $id = DB::table('software')->insertGetId([
            'Nombre' => $validated['nombre'],
            'Tipo' => $validated['tipo'] ?? null
        ]);
        
        return response()->json([
            'success' => true,
            'id' => $id,
            'message' => '✅ Software agregado correctamente'
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error de validación: ' . implode(', ', $e->errors())
        ], 422);
    } catch (\Exception $e) {
        \Log::error('Error en api/softwares: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error interno del servidor'
        ], 500);
    }
})->middleware(['auth']);

// Rutas para Licitaciones
Route::get('/licitaciones', [LicitacionController::class, 'index'])
    ->middleware('auth')
    ->name('licitaciones.index');

// ===== RUTAS PARA LOGS (TODOS LOS USUARIOS PUEDEN VER) =====
Route::get('/logs', [LogController::class, 'index'])
    ->middleware('auth')
    ->name('logs.index');

Route::get('/logs/filtrar', [LogController::class, 'filtrar'])
    ->middleware('auth')
    ->name('logs.filtrar');

// ===== RUTAS PARA ELIMINAR LOGS (SOLO ADMIN) =====
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/logs/eliminar/{id}', [LogController::class, 'eliminarUnico'])
        ->name('logs.eliminar');
    
    Route::get('/logs/eliminar-rango', [LogController::class, 'eliminarRango'])
        ->name('logs.eliminar.rango');
    
    Route::get('/logs/eliminar-todos', [LogController::class, 'eliminarTodos'])
        ->name('logs.eliminar.todos');
});

// ===== ELIMINAR ÁREA Y TIPO DE PRODUCTO (SOLO ADMIN) =====
Route::delete('/api/areas/{id}', [AreaController::class, 'destroy'])
    ->middleware(['auth', 'role:admin']);

Route::delete('/api/tipos-producto/{id}', [TipoProductoController::class, 'destroy'])
    ->middleware(['auth', 'role:admin']);

// Rutas para Licitaciones
Route::get('/licitaciones', [LicitacionController::class, 'index'])
    ->middleware('auth')
    ->name('licitaciones.index');

Route::get('/licitaciones/{id}', [LicitacionController::class, 'show'])
    ->middleware('auth')
    ->name('licitaciones.show');


// Eliminar licitación (solo admin)
Route::delete('/licitaciones/{id}/eliminar', [LicitacionController::class, 'destroy'])
    ->middleware(['auth', 'role:admin'])
    ->name('licitaciones.eliminar');



// Actualizar licitación (solo admin)
Route::post('/licitaciones/{id}/actualizar', [LicitacionController::class, 'update'])
    ->middleware(['auth', 'role:admin'])
    ->name('licitaciones.actualizar');
    
require __DIR__.'/auth.php';