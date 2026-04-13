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
                    <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                        style="width: 16px; height: 16px; margin-left: 8px;">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>

                <!-- Menú desplegable -->
                <div id="addMenu" class="add-menu">
                    <a href="{{ route('articulos.crear') }}">📦 Nuevo Artículo</a>
                    <a href="{{ route('licencias.crear') }}">🔑 Nueva Licencia</a>
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
                    <div style="flex: 0 0 160px; display: flex; align-items: center; gap: 4px;">
                        <select id="filtro-tipo" class="filter-select">
                            <option value="">📦 Tipo</option>
                            @foreach($tiposProducto ?? [] as $tipo)
                                <option value="{{ $tipo->idTipo_Producto }}">{{ $tipo->NombreTP }}</option>
                            @endforeach
                        </select>
                        <button onclick="abrirModalEditar('tipo', filtroTipoSeleccionado())"
                            title="Editar tipo seleccionado" id="btn-editar-tipo"
                            style="background:none; border:none; cursor:pointer; font-size:16px; opacity:0.6; display:none;">
                            ✏️
                        </button>
                        <button onclick="abrirModalEliminar('tipo', filtroTipoSeleccionado())"
                            title="Eliminar tipo seleccionado" id="btn-eliminar-tipo"
                            style="background:none; border:none; cursor:pointer; font-size:16px; opacity:0.6; display:none;">
                            🗑️
                        </button>
                    </div>

                    <!-- Filtro Área -->
                    <div style="flex: 0 0 160px; display: flex; align-items: center; gap: 4px;">
                        <select id="filtro-area" class="filter-select">
                            <option value="">📍 Área</option>
                            @foreach($areas ?? [] as $area)
                                <option value="{{ $area->idArea }}">{{ $area->NombreArea }}</option>
                            @endforeach
                        </select>
                        <button onclick="abrirModalEditar('area', filtroAreaSeleccionada())"
                            title="Editar área seleccionada" id="btn-editar-area"
                            style="background:none; border:none; cursor:pointer; font-size:16px; opacity:0.6; display:none;">
                            ✏️
                        </button>
                        <button onclick="abrirModalEliminar('area', filtroAreaSeleccionada())"
                            title="Eliminar área seleccionada" id="btn-eliminar-area"
                            style="background:none; border:none; cursor:pointer; font-size:16px; opacity:0.6; display:none;">
                            🗑️
                        </button>
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
                    <input type="text" id="nombre_area" name="nombre_area" placeholder="Ej: Sistemas, Administración..."
                        maxlength="100" required autofocus>
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

    <!-- MODAL EDITAR ÁREA -->
    <div id="modal-editar-area" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>✏️ Editar Área</h2>
                <span class="close-modal" onclick="cerrarModalEditarArea()">&times;</span>
            </div>

            <form id="form-editar-area" onsubmit="actualizarArea(event)">
                @csrf
                @method('PUT')
                <input type="hidden" id="editar_area_id" name="id">

                <div class="form-group">
                    <label for="editar_nombre_area">Nombre del área <span style="color: #dc3545;">*</span></label>
                    <input type="text" id="editar_nombre_area" name="nombre_area" 
                        placeholder="Ej: Sistemas, Administración..." maxlength="100" required>
                    <p style="font-size: 12px; color: #666; margin-top: 5px;">
                        Nombre del departamento o área
                    </p>
                </div>

                <div class="modal-buttons">
                    <button type="button" onclick="cerrarModalEditarArea()" class="btn-cancel">Cancelar</button>
                    <button type="submit" class="btn-save">
                        <span>💾</span> Actualizar Área
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDITAR TIPO PRODUCTO -->
    <div id="modal-editar-tipo" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>✏️ Editar Tipo de Producto</h2>
                <span class="close-modal" onclick="cerrarModalEditarTipo()">&times;</span>
            </div>

            <form id="form-editar-tipo" onsubmit="actualizarTipoProducto(event)">
                @csrf
                @method('PUT')
                <input type="hidden" id="editar_tipo_id" name="id">

                <div class="form-group">
                    <label for="editar_nombre_tipo">Nombre del tipo <span style="color: #dc3545;">*</span></label>
                    <input type="text" id="editar_nombre_tipo" name="nombre_tipo" 
                        placeholder="Ej: Laptop, Monitor, Impresora..." maxlength="45" required>
                    <p style="font-size: 12px; color: #666; margin-top: 5px;">
                        Nombre del tipo de producto
                    </p>
                </div>

                <div class="modal-buttons">
                    <button type="button" onclick="cerrarModalEditarTipo()" class="btn-cancel">Cancelar</button>
                    <button type="submit" class="btn-save">
                        <span>💾</span> Actualizar Tipo
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
                    <input type="text" id="nombre_proveedor" name="nombre_proveedor"
                        placeholder="Ej: Tecnología SA, Suministros LP..." maxlength="45" required>
                </div>

                <div class="form-group">
                    <label for="rfc_proveedor">RFC</label>
                    <input type="text" id="rfc_proveedor" name="rfc_proveedor" placeholder="RFC (opcional)"
                        maxlength="13">
                </div>

                <div class="form-group">
                    <label for="telefono_proveedor">Teléfono</label>
                    <input type="text" id="telefono_proveedor" name="telefono_proveedor"
                        placeholder="Teléfono (opcional)" maxlength="20">
                </div>

                <div class="form-group">
                    <label for="direccion_proveedor">Dirección</label>
                    <input type="text" id="direccion_proveedor" name="direccion_proveedor"
                        placeholder="Dirección (opcional)" maxlength="900">
                </div>

                <div class="form-group">
                    <label for="correo_proveedor">Correo electrónico</label>
                    <input type="email" id="correo_proveedor" name="correo_proveedor"
                        placeholder="correo@ejemplo.com (opcional)" maxlength="50">
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

    <div id="alerta" class="alert-message"></div>

    <!-- MODAL CONFIRMAR ELIMINACIÓN -->
    <div id="modal-eliminar" class="modal">
        <div class="modal-content" style="max-width: 380px;">
            <div class="modal-header">
                <h2>🗑️ Confirmar eliminación</h2>
                <span class="close-modal" onclick="cerrarModalEliminar()">&times;</span>
            </div>
            <p id="modal-eliminar-texto" style="color: #555; margin-bottom: 25px;">
                ¿Deseas eliminar este elemento?
            </p>
            <div class="modal-buttons">
                <button onclick="cerrarModalEliminar()" class="btn-cancel">Cancelar</button>
                <button onclick="confirmarEliminar()" class="btn-save" style="background:#dc3545;">
                    🗑️ Eliminar
                </button>
            </div>
        </div>
    </div>

    <!-- Pasar datos a JavaScript -->
    <script>
        window.articulosIniciales = @json($articulosParaJs);
        window.licenciasIniciales = @json($licenciasIniciales);
        
        // Variables para eliminar
        let elementoAEliminar = null;
        let tipoElementoAEliminar = null;
        
        // Variables para editar
        let elementoAEditar = null;
        let tipoElementoAEditar = null;
    </script>

    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <script src="{{ asset('js/dashboard.js') }}"></script>

    <script>
        // ==================== FUNCIONES PARA LOS SELECTS ====================
        
        function filtroTipoSeleccionado() {
            const select = document.getElementById('filtro-tipo');
            const selectedOption = select.options[select.selectedIndex];
            if (select.value && selectedOption.value) {
                return {
                    id: select.value,
                    nombre: selectedOption.text
                };
            }
            return null;
        }
        
        function filtroAreaSeleccionada() {
            const select = document.getElementById('filtro-area');
            const selectedOption = select.options[select.selectedIndex];
            if (select.value && selectedOption.value) {
                return {
                    id: select.value,
                    nombre: selectedOption.text
                };
            }
            return null;
        }
        
        // Mostrar/ocultar botones según selección
        function actualizarBotonesFiltros() {
            const tipoSeleccionado = document.getElementById('filtro-tipo').value;
            const btnEditarTipo = document.getElementById('btn-editar-tipo');
            const btnEliminarTipo = document.getElementById('btn-eliminar-tipo');
            
            if (tipoSeleccionado) {
                btnEditarTipo.style.display = 'inline-block';
                btnEliminarTipo.style.display = 'inline-block';
            } else {
                btnEditarTipo.style.display = 'none';
                btnEliminarTipo.style.display = 'none';
            }
            
            const areaSeleccionada = document.getElementById('filtro-area').value;
            const btnEditarArea = document.getElementById('btn-editar-area');
            const btnEliminarArea = document.getElementById('btn-eliminar-area');
            
            if (areaSeleccionada) {
                btnEditarArea.style.display = 'inline-block';
                btnEliminarArea.style.display = 'inline-block';
            } else {
                btnEditarArea.style.display = 'none';
                btnEliminarArea.style.display = 'none';
            }
        }
        
        // ==================== FUNCIONES PARA EDITAR ====================
        
        function abrirModalEditar(tipo, elemento) {
            if (!elemento) {
                mostrarAlerta('error', '❌ Selecciona un elemento para editar');
                return;
            }
            
            tipoElementoAEditar = tipo;
            elementoAEditar = elemento;
            
            if (tipo === 'area') {
                document.getElementById('editar_area_id').value = elemento.id;
                document.getElementById('editar_nombre_area').value = elemento.nombre;
                document.getElementById('modal-editar-area').style.display = 'flex';
                document.getElementById('editar_nombre_area').focus();
            } else if (tipo === 'tipo') {
                document.getElementById('editar_tipo_id').value = elemento.id;
                document.getElementById('editar_nombre_tipo').value = elemento.nombre;
                document.getElementById('modal-editar-tipo').style.display = 'flex';
                document.getElementById('editar_nombre_tipo').focus();
            }
        }
        
        function cerrarModalEditarArea() {
            document.getElementById('modal-editar-area').style.display = 'none';
            document.getElementById('editar_area_id').value = '';
            document.getElementById('editar_nombre_area').value = '';
        }
        
        function cerrarModalEditarTipo() {
            document.getElementById('modal-editar-tipo').style.display = 'none';
            document.getElementById('editar_tipo_id').value = '';
            document.getElementById('editar_nombre_tipo').value = '';
        }
        
        async function actualizarArea(event) {
            event.preventDefault();
            
            const id = document.getElementById('editar_area_id').value;
            const nombre = document.getElementById('editar_nombre_area').value.trim();
            
            if (!nombre) {
                mostrarAlerta('error', '❌ El nombre del área es requerido');
                return;
            }
            
            const btn = event.target.querySelector('button[type="submit"]');
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span>⏳</span> Actualizando...';
            
            try {
                const response = await fetch(`/api/areas/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ nombre: nombre })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    mostrarAlerta('success', '✅ Área actualizada correctamente');
                    cerrarModalEditarArea();
                    
                    // Actualizar el select de áreas
                    const select = document.getElementById('filtro-area');
                    const option = select.querySelector(`option[value="${id}"]`);
                    if (option) {
                        option.textContent = nombre;
                        option.value = id;
                    }
                    
                    // Recargar la tabla si es necesario
                    if (typeof cargarDatos === 'function') {
                        cargarDatos();
                    }
                } else {
                    mostrarAlerta('error', data.message || '❌ Error al actualizar el área');
                }
            } catch (error) {
                console.error('Error:', error);
                mostrarAlerta('error', '❌ Error de conexión al servidor');
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        }
        
        async function actualizarTipoProducto(event) {
            event.preventDefault();
            
            const id = document.getElementById('editar_tipo_id').value;
            const nombre = document.getElementById('editar_nombre_tipo').value.trim();
            
            if (!nombre) {
                mostrarAlerta('error', '❌ El nombre del tipo es requerido');
                return;
            }
            
            const btn = event.target.querySelector('button[type="submit"]');
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span>⏳</span> Actualizando...';
            
            try {
                const response = await fetch(`/api/tipos-producto/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ nombre: nombre })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    mostrarAlerta('success', '✅ Tipo de producto actualizado correctamente');
                    cerrarModalEditarTipo();
                    
                    // Actualizar el select de tipos
                    const select = document.getElementById('filtro-tipo');
                    const option = select.querySelector(`option[value="${id}"]`);
                    if (option) {
                        option.textContent = nombre;
                        option.value = id;
                    }
                    
                    // Recargar la tabla si es necesario
                    if (typeof cargarDatos === 'function') {
                        cargarDatos();
                    }
                } else {
                    mostrarAlerta('error', data.message || '❌ Error al actualizar el tipo de producto');
                }
            } catch (error) {
                console.error('Error:', error);
                mostrarAlerta('error', '❌ Error de conexión al servidor');
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        }
        
        // ==================== FUNCIONES PARA ELIMINAR ====================
        
        function abrirModalEliminar(tipo, elemento) {
            if (!elemento) {
                mostrarAlerta('error', '❌ Selecciona un elemento para eliminar');
                return;
            }
            
            tipoElementoAEliminar = tipo;
            elementoAEliminar = elemento;
            
            const textoModal = document.getElementById('modal-eliminar-texto');
            if (tipo === 'area') {
                textoModal.innerHTML = `¿Deseas eliminar el área <strong>"${elemento.nombre}"</strong>?<br><span style="font-size: 12px; color: #999;">Esta acción no se puede deshacer.</span>`;
            } else if (tipo === 'tipo') {
                textoModal.innerHTML = `¿Deseas eliminar el tipo de producto <strong>"${elemento.nombre}"</strong>?<br><span style="font-size: 12px; color: #999;">Esta acción no se puede deshacer.</span>`;
            }
            
            document.getElementById('modal-eliminar').style.display = 'flex';
        }
        
        function cerrarModalEliminar() {
            document.getElementById('modal-eliminar').style.display = 'none';
            elementoAEliminar = null;
            tipoElementoAEliminar = null;
        }
        
        async function confirmarEliminar() {
            if (!elementoAEliminar) return;
            
            try {
                let url = '';
                if (tipoElementoAEliminar === 'area') {
                    url = `/api/areas/${elementoAEliminar.id}`;
                } else if (tipoElementoAEliminar === 'tipo') {
                    url = `/api/tipos-producto/${elementoAEliminar.id}`;
                }
                
                const response = await fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    mostrarAlerta('success', data.message);
                    cerrarModalEliminar();
                    
                    // Remover la opción del select
                    if (tipoElementoAEliminar === 'area') {
                        const select = document.getElementById('filtro-area');
                        const option = select.querySelector(`option[value="${elementoAEliminar.id}"]`);
                        if (option) option.remove();
                        select.value = '';
                    } else if (tipoElementoAEliminar === 'tipo') {
                        const select = document.getElementById('filtro-tipo');
                        const option = select.querySelector(`option[value="${elementoAEliminar.id}"]`);
                        if (option) option.remove();
                        select.value = '';
                    }
                    
                    actualizarBotonesFiltros();
                    
                    if (typeof cargarDatos === 'function') {
                        cargarDatos();
                    }
                } else {
                    mostrarAlerta('error', data.message);
                    cerrarModalEliminar();
                }
            } catch (error) {
                console.error('Error:', error);
                mostrarAlerta('error', '❌ Error de conexión al servidor');
                cerrarModalEliminar();
            }
        }
        
        // ==================== FUNCIONES PARA MODAL DE ÁREA ====================
        
        function abrirModalArea() {
            document.getElementById('modal-area').style.display = 'flex';
            document.getElementById('nombre_area').focus();
        }
        
        function cerrarModalArea() {
            document.getElementById('modal-area').style.display = 'none';
            document.getElementById('nombre_area').value = '';
        }
        
        async function guardarArea(event) {
            event.preventDefault();
            
            const nombre = document.getElementById('nombre_area').value.trim();
            
            if (!nombre) {
                mostrarAlerta('error', '❌ El nombre del área es requerido');
                return;
            }
            
            const btn = event.target.querySelector('button[type="submit"]');
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span>⏳</span> Guardando...';
            
            try {
                const response = await fetch('/api/areas', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ nombre: nombre })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    mostrarAlerta('success', '✅ Área agregada correctamente');
                    cerrarModalArea();
                    
                    // Agregar la nueva área al select
                    const select = document.getElementById('filtro-area');
                    const option = document.createElement('option');
                    option.value = data.id;
                    option.textContent = nombre;
                    select.appendChild(option);
                    
                    if (typeof cargarDatos === 'function') {
                        cargarDatos();
                    }
                } else {
                    mostrarAlerta('error', data.message || '❌ Error al agregar el área');
                }
            } catch (error) {
                console.error('Error:', error);
                mostrarAlerta('error', '❌ Error de conexión al servidor');
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        }
        
        // ==================== FUNCIONES PARA MODAL DE PROVEEDOR ====================
        
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
            
            if (correo && !correo.includes('@')) {
                mostrarAlerta('error', '⚠️ El correo electrónico debe contener un "@"');
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
        
        // ==================== FUNCIÓN DE ALERTA ====================
        
        function mostrarAlerta(tipo, mensaje) {
            const alerta = document.getElementById('alerta');
            alerta.className = `alert-message alert-${tipo}`;
            alerta.textContent = mensaje;
            alerta.style.display = 'block';
            
            setTimeout(() => {
                alerta.style.display = 'none';
            }, 3000);
        }
        
        // ==================== INICIALIZACIÓN ====================
        
        document.addEventListener('DOMContentLoaded', function() {
            const selectTipo = document.getElementById('filtro-tipo');
            const selectArea = document.getElementById('filtro-area');
            
            if (selectTipo) {
                selectTipo.addEventListener('change', actualizarBotonesFiltros);
            }
            if (selectArea) {
                selectArea.addEventListener('change', actualizarBotonesFiltros);
            }
            
            actualizarBotonesFiltros();
        });
        
        // Función para toggle del menú agregar
        function toggleAddMenu() {
            const menu = document.getElementById('addMenu');
            if (menu.style.display === 'flex') {
                menu.style.display = 'none';
            } else {
                menu.style.display = 'flex';
            }
        }
        
        // Cerrar menú al hacer clic fuera
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('addMenu');
            const button = document.querySelector('.add-button');
            if (menu && button && !button.contains(event.target) && !menu.contains(event.target)) {
                menu.style.display = 'none';
            }
        });
    </script>

    <script>
    document.title = "Panel Administrador";
</script>
</x-app-layout>