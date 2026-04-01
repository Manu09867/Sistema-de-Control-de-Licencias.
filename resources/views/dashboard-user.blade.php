<x-app-layout>
    <div>
        <!-- Header de bienvenida con botón Agregar -->
        <div class="welcome-header">
            <div>
                <h1>¡Hola, {{ Auth::user()->name }}!</h1>
                <p style="opacity: 0.9; margin: 5px 0;">Sistema de Inventario</p>
                <span class="badge-user">👤 USUARIO</span>
            </div>
            
            <!-- Botón Agregar -->
            <div class="add-button-container">
                <button class="add-button" onclick="toggleAddMenu()">
                    <span style="margin-right: 8px;">➕</span>
                    Agregar
                    <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" style="width: 16px; height: 16px; margin-left: 8px;">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                
                <!-- Menú desplegable -->
                <div id="addMenu" class="add-menu">
                    <a href="{{ route('articulos.crear') }}">📦 Nuevo Artículo</a>
                    <a href="#">🔑 Nueva Licencia</a>
                    <a href="#" onclick="abrirModalArea(); return false;">📍 Nueva Área</a>
                    <a href="#" onclick="abrirModalProveedor(); return false;">🏢 Nuevo Proveedor</a>
                </div>
            </div>
        </div>

        <!-- ===== RECUADRO PRINCIPAL ===== -->
        <div class="main-container">
            <!-- HEADER con barra de búsqueda y FILTROS -->
            <div class="container-header">
                <div class="filters-wrapper">
                    <!-- Barra de búsqueda -->
                    <div class="search-wrapper">
                        <div class="search-container">
                            <span class="search-icon">🔍</span>
                            <input type="text" class="search-input" placeholder="Buscar..." value="">
                        </div>
                    </div>

                    <!-- Filtro Tipo de Producto -->
                    <div style="flex: 0 0 140px;">
                        <select id="filtro-tipo" class="filter-select">
                            <option value="">📦 Tipo</option>
                            @foreach($tiposProducto ?? [] as $tipo)
                                <option value="{{ $tipo->idTipo_Producto }}">{{ $tipo->NombreTP }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtro Área -->
                    <div style="flex: 0 0 140px;">
                        <select id="filtro-area" class="filter-select">
                            <option value="">📍 Área</option>
                            @foreach($areas ?? [] as $area)
                                <option value="{{ $area->idArea }}">{{ $area->NombreArea }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Checkbox "Solo licencias" -->
                    <div class="checkbox-container">
                        <input type="checkbox" id="solo-licencias">
                        <label for="solo-licencias">🔑 Licencias</label>
                    </div>
                </div>
                
                <div class="date-badge">
                    {{ now()->format('d/m/Y') }}
                </div>
            </div>
            
            <!-- Contenido con scroll -->
            <div class="container-content">
                <!-- Título dinámico -->
                <div class="section-title" id="tabla-titulo">
                    📋 Artículos y Licencias
                    <span>recientes</span>
                </div>

                <!-- Tabla -->
                <div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Serie</th>
                <th>Estado</th>
                <th>RP</th>
                <th>Producto</th>
                <th>Marca</th>
                <th>Tipo</th>
                <th>Área / Asignación</th>
            </tr>
        </thead>
        <tbody id="tabla-body">
            <!-- Se llena con JavaScript -->
        </tbody>
    </table>
</div>
            </div>
        </div>
    </div>

    <!-- MODAL AGREGAR ÁREA -->
    <div id="modal-area" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>📍 Agregar Nueva Área</h2>
                <span class="close-modal" onclick="cerrarModalArea()">&times;</span>
            </div>
            
            <form id="form-area" onsubmit="guardarArea(event)">
                @csrf
                
                <div class="form-group">
                    <label for="nombre_area">Nombre del área <span style="color: #dc3545;">*</span></label>
                    <input type="text" id="nombre_area" name="nombre_area" placeholder="Ej: Sistemas, Administración..." maxlength="100" required autofocus>
                    <p style="font-size: 12px; color: #666; margin-top: 5px;">
                        Nombre del departamento o área
                    </p>
                </div>

                <div class="modal-buttons">
                    <button type="button" onclick="cerrarModalArea()" class="btn-cancel">Cancelar</button>
                    <button type="submit" class="btn-save">
                        <span>💾</span> Guardar Área
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL AGREGAR PROVEEDOR -->
    <div id="modal-proveedor" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>🏢 Agregar Nuevo Proveedor</h2>
                <span class="close-modal" onclick="cerrarModalProveedor()">&times;</span>
            </div>
            
            <form id="form-proveedor" onsubmit="guardarProveedor(event)">
                @csrf
                
                <div class="form-group">
                    <label for="nombre_proveedor">Nombre del proveedor <span style="color: #dc3545;">*</span></label>
                    <input type="text" id="nombre_proveedor" name="nombre_proveedor" placeholder="Ej: Tecnología SA, Suministros LP..." maxlength="45" required>
                </div>

                <div class="form-group">
                    <label for="rfc_proveedor">RFC</label>
                    <input type="text" id="rfc_proveedor" name="rfc_proveedor" placeholder="RFC (opcional)" maxlength="20">
                </div>

                <div class="form-group">
                    <label for="telefono_proveedor">Teléfono</label>
                    <input type="text" id="telefono_proveedor" name="telefono_proveedor" placeholder="Teléfono (opcional)" maxlength="20">
                </div>

                <div class="form-group">
                    <label for="direccion_proveedor">Dirección</label>
                    <input type="text" id="direccion_proveedor" name="direccion_proveedor" placeholder="Dirección (opcional)" maxlength="900">
                </div>

                <div class="form-group">
                    <label for="correo_proveedor">Correo electrónico</label>
                    <input type="email" id="correo_proveedor" name="correo_proveedor" placeholder="correo@ejemplo.com (opcional)" maxlength="50">
                </div>

                <div class="modal-buttons">
                    <button type="button" onclick="cerrarModalProveedor()" class="btn-cancel">Cancelar</button>
                    <button type="submit" class="btn-save">
                        <span>💾</span> Guardar Proveedor
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ALERTA FLOTANTE -->
    <div id="alerta" class="alert-message"></div>

    <!-- Pasar datos a JavaScript -->
    <script>
        window.articulosIniciales = @json($articulosParaJs);
        window.licenciasIniciales = @json($licenciasIniciales);
    </script>
    
    <!-- CSS y JS separados -->
    <link rel="stylesheet" href="{{ asset('css/dashboard-user.css') }}">
    <script src="{{ asset('js/dashboard-user.js') }}"></script>
    
    <script>
        // Funciones para el modal de proveedor
        function abrirModalProveedor() {
            document.getElementById('modal-proveedor').style.display = 'flex';
            document.getElementById('nombre_proveedor').focus();
        }

        function cerrarModalProveedor() {
            document.getElementById('modal-proveedor').style.display = 'none';
            document.getElementById('nombre_proveedor').value = '';
            document.getElementById('rfc_proveedor').value = '';
            document.getElementById('telefono_proveedor').value = '';
            document.getElementById('direccion_proveedor').value = '';
            document.getElementById('correo_proveedor').value = '';
        }

        // Guardar nuevo proveedor
        async function guardarProveedor(event) {
            event.preventDefault();
            
            const nombre = document.getElementById('nombre_proveedor').value.trim();
            const rfc = document.getElementById('rfc_proveedor').value.trim();
            const telefono = document.getElementById('telefono_proveedor').value.trim();
            const direccion = document.getElementById('direccion_proveedor').value.trim();
            const correo = document.getElementById('correo_proveedor').value.trim();
            
            if (!nombre) {
                mostrarAlerta('error', '❌ El nombre del proveedor es requerido');
                return;
            }

            const btn = event.target.querySelector('button[type="submit"]');
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span>⏳</span> Guardando...';

            try {
                const response = await fetch('/api/proveedores', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        nombre: nombre,
                        rfc: rfc || null,
                        telefono: telefono || null,
                        direccion: direccion || null,
                        correo: correo || null
                    })
                });

                const data = await response.json();

                if (data.success) {
                    mostrarAlerta('success', '✅ Proveedor agregado correctamente');
                    cerrarModalProveedor();
                } else {
                    mostrarAlerta('error', data.message || '❌ Error al agregar el proveedor');
                }
            } catch (error) {
                console.error('Error:', error);
                mostrarAlerta('error', '❌ Error de conexión al servidor');
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        }
    </script>
</x-app-layout>