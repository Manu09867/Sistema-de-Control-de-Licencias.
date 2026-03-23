<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ArticuloController;
use App\Http\Controllers\BusquedaController;
use App\Http\Controllers\LicenciaController;  // ← AGREGADO
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\DB;

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

// Rutas de artículos y licencias
Route::get('/articulo/{rp}', [ArticuloController::class, 'show'])->name('articulo.show');
Route::get('/buscar-articulos', [BusquedaController::class, 'buscar'])->name('buscar.articulos');
Route::get('/licencia/{clave}', [LicenciaController::class, 'show'])->name('licencia.show');  // ← AHORA USA EL IMPORT

// Verificar contraseña (para cambio de contraseña)
Route::post('/verify-password', function (Request $request) {
    return response()->json([
        'valid' => Hash::check($request->password, Auth::user()->password)
    ]);
})->middleware('auth');

// ===== RUTAS DE ADMIN PARA GESTIÓN DE USUARIOS (solo admin) =====
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

// ===== RUTAS PARA CREACIÓN DE ARTÍCULOS (accesible para todos los usuarios) =====
Route::middleware(['auth'])->prefix('articulos')->name('articulos.')->group(function () {
    Route::get('/crear', [ArticuloController::class, 'create'])->name('crear');
    Route::post('/', [ArticuloController::class, 'store'])->name('store');
});

// ===== RUTAS PARA CREACIÓN DE LICENCIAS =====
Route::middleware(['auth'])->prefix('licencias')->name('licencias.')->group(function () {
    Route::get('/crear', [LicenciaController::class, 'create'])->name('crear');
    Route::post('/', [LicenciaController::class, 'store'])->name('store');
});

// ===== RUTAS API PARA CATÁLOGOS =====

// RUTA API PARA AGREGAR TIPOS DE PRODUCTO
Route::post('/api/tipos-producto', function (Request $request) {
    try {
        if (empty($request->nombre)) {
            return response()->json([
                'success' => false,
                'message' => 'El nombre del tipo de producto es requerido'
            ], 400);
        }

        $existe = DB::table('Tipo_Producto')
            ->whereRaw('LOWER(NombreTP) = ?', [strtolower($request->nombre)])
            ->exists();
            
        if ($existe) {
            return response()->json([
                'success' => false,
                'message' => '❌ Este tipo de producto ya existe en la base de datos'
            ], 400);
        }

        $id = DB::table('Tipo_Producto')->insertGetId([
            'NombreTP' => $request->nombre
        ]);
        
        return response()->json([
            'success' => true,
            'id' => $id,
            'message' => '✅ Tipo de producto agregado correctamente'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al agregar: ' . $e->getMessage()
        ], 500);
    }
})->middleware(['auth']);

// RUTA API PARA AGREGAR PROVEEDORES
Route::post('/api/proveedores', function (Request $request) {
    try {
        if (empty($request->nombre)) {
            return response()->json([
                'success' => false,
                'message' => 'El nombre del proveedor es requerido'
            ], 400);
        }

        $existe = DB::table('proveedor')
            ->whereRaw('LOWER(Nombre) = ?', [strtolower($request->nombre)])
            ->exists();
            
        if ($existe) {
            return response()->json([
                'success' => false,
                'message' => '❌ Este proveedor ya existe en la base de datos'
            ], 400);
        }

        $id = DB::table('proveedor')->insertGetId([
            'Nombre' => $request->nombre,
            'RFC' => $request->rfc,
            'Telefono' => $request->telefono,
            'Direccion' => $request->direccion,
            'correo' => $request->correo
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
})->middleware(['auth']);

// RUTA API PARA AGREGAR ÁREAS
Route::post('/api/areas', function (Request $request) {
    try {
        if (empty($request->nombre)) {
            return response()->json([
                'success' => false,
                'message' => 'El nombre del área es requerido'
            ], 400);
        }

        $existe = DB::table('area')
            ->whereRaw('LOWER(NombreArea) = ?', [strtolower($request->nombre)])
            ->exists();
            
        if ($existe) {
            return response()->json([
                'success' => false,
                'message' => '❌ Esta área ya existe en la base de datos'
            ], 400);
        }

        $id = DB::table('area')->insertGetId([
            'NombreArea' => $request->nombre
        ]);
        
        return response()->json([
            'success' => true,
            'id' => $id,
            'message' => '✅ Área agregada correctamente'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al agregar: ' . $e->getMessage()
        ], 500);
    }
})->middleware(['auth']);

// RUTA API PARA AGREGAR SOFTWARE
Route::post('/api/softwares', function (Request $request) {
    try {
        if (empty($request->nombre)) {
            return response()->json([
                'success' => false,
                'message' => 'El nombre del software es requerido'
            ], 400);
        }

        $existe = DB::table('software')
            ->whereRaw('LOWER(Nombre) = ?', [strtolower($request->nombre)])
            ->exists();
            
        if ($existe) {
            return response()->json([
                'success' => false,
                'message' => '❌ Este software ya existe en la base de datos'
            ], 400);
        }

        $id = DB::table('software')->insertGetId([
            'Nombre' => $request->nombre,
            'Tipo' => $request->tipo
        ]);
        
        return response()->json([
            'success' => true,
            'id' => $id,
            'message' => '✅ Software agregado correctamente'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al agregar: ' . $e->getMessage()
        ], 500);
    }
})->middleware(['auth']);

Route::post('/articulo/{id}/actualizar', [ArticuloController::class, 'actualizar'])->name('articulo.actualizar');

Route::post('/licencia/{id}/actualizar', [LicenciaController::class, 'actualizar'])->middleware('auth');

require __DIR__.'/auth.php';