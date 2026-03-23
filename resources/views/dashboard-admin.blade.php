<x-app-layout>
    <div>
        <!-- Header-->
        <div class="welcome-header">
            <div>
                <h1>¡Hola, {{ Auth::user()->name }}!</h1>
                <p style="opacity: 0.9; margin: 5px 0;">Panel de Administración</p>
                <span class="badge-admin">🔑 ADMINISTRADOR</span>
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
                    <a href="{{ route('licencias.crear') }}">🔑 Nueva Licencia</a>
                    <a href="#" onclick="abrirModalArea(); return false;">📍 Nueva Área</a>
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
                            <button class="search-button">Buscar</button>
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
                    {{ now()->format('d/m/Y H:i') }}
                </div>
            </div>
            
            <!-- Contenido con scroll -->
            <div class="container-content">
                <!-- Título -->
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


    <div id="alerta" class="alert-message"></div>

    <!-- Pasar datos a JavaScript -->
    <script>
        window.articulosIniciales = @json($articulosParaJs);
        window.licenciasIniciales = @json($licenciasIniciales);
    </script>
    

    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <script src="{{ asset('js/dashboard.js') }}"></script>
</x-app-layout>