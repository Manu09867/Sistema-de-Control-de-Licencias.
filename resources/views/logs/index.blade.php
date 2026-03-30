<x-app-layout>
    <style>
        .welcome-header {
            background: linear-gradient(135deg, #4a6fa5, #6b8cae);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin: 20px 20px 0 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .welcome-header h1 {
            font-size: 28px;
            margin-bottom: 5px;
        }

        .badge-admin {
            background: #ffd700;
            color: #0f3057;
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 12px;
            display: inline-block;
        }

        .logs-container {
            margin: 20px auto;
            max-width: 95%;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            border: 1px solid #e6ecf2;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: calc(100vh - 250px);
        }

        .container-header {
            background: linear-gradient(135deg, #4a6fa5, #6b8cae);
            color: white;
            padding: 8px 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .filters-wrapper {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
            flex: 1;
        }

        .search-wrapper {
            flex: 0 0 200px;
        }

        .search-container {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50px;
            padding: 2px 2px 2px 12px;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .search-icon {
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
            margin-right: 6px;
        }

        .search-input {
            flex: 1;
            border: none;
            background: transparent;
            padding: 6px 0;
            color: white;
            font-size: 13px;
            outline: none;
        }

        .search-input::placeholder {
            color: rgba(255, 255, 255, 0.6);
            font-style: italic;
        }

        .search-button {
            background: #ffd700;
            color: #4a6fa5;
            border: none;
            padding: 6px 15px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 12px;
            cursor: pointer;
        }

        .filter-select {
            background: white;
            color: #0f3057;
            border: 1px solid #ffd700;
            border-radius: 50px;
            padding: 6px 12px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            min-width: 130px;
        }

        .date-badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 12px;
            white-space: nowrap;
        }

        .container-content {
            padding: 20px;
            overflow-y: auto;
            height: 100%;
            background: #fafcfc;
        }

        .section-title {
            color: #0f3057;
            margin: 0 0 15px 0;
            font-size: 18px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-title span {
            background: #e6ecf2;
            padding: 3px 8px;
            border-radius: 20px;
            font-size: 12px;
            color: #4a6fa5;
        }

        .table-container {
            overflow-x: auto;
            border-radius: 12px;
            border: 1px solid #e6ecf2;
            margin-top: 15px;
        }

        .logs-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        .logs-table th {
            background: #4a6fa5;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
        }

        .logs-table td {
            padding: 12px;
            border-bottom: 1px solid #e6ecf2;
            color: #333;
            font-size: 13px;
        }

        .logs-table tr:hover td {
            background: #f8fafc;
        }

        .sql-query {
            font-family: monospace;
            font-size: 12px;
            max-width: 400px;
            overflow-x: auto;
            white-space: nowrap;
        }

        .badge-select {
            background: #d4edda;
            color: #155724;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            display: inline-block;
        }

        .badge-insert {
            background: #cce5ff;
            color: #004085;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            display: inline-block;
        }

        .badge-update {
            background: #fff3cd;
            color: #856404;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            display: inline-block;
        }

        .badge-delete {
            background: #f8d7da;
            color: #721c24;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            display: inline-block;
        }

        .duration {
            font-family: monospace;
        }

        .no-data {
            text-align: center;
            padding: 50px 20px;
            color: #666;
            font-size: 15px;
        }

        .pagination {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }

        .pagination nav {
            display: inline-block;
        }

        /* Estilos para el modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            border-radius: 20px;
            padding: 30px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid #4a6fa5;
        }

        .modal-header h2 {
            color: #0f3057;
            font-size: 24px;
            margin: 0;
        }

        .close-modal {
            font-size: 28px;
            font-weight: bold;
            color: #666;
            cursor: pointer;
        }

        .close-modal:hover {
            color: #4a6fa5;
        }

        .radio-group {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .radio-option {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .input-group {
            margin-top: 15px;
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #0f3057;
        }

        .input-rango {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .input-rango input {
            flex: 1;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        @media (max-width: 768px) {
            .welcome-header {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }

            .container-header {
                flex-direction: column;
            }

            .filters-wrapper {
                width: 100%;
            }

            .search-wrapper {
                max-width: 100%;
                width: 100%;
            }

            .logs-table th,
            .logs-table td {
                padding: 8px;
                font-size: 12px;
            }

            .sql-query {
                max-width: 150px;
            }
        }
    </style>

    <div>
        <!-- Header de bienvenida -->
        <div class="welcome-header">
            <div>
                <h1>Registro de Actividades</h1>
                <p style="opacity: 0.9; margin: 5px 0;">Bitácora de Consultas SQL</p>
            </div>

            <div class="date-badge" style="background: rgba(255,255,255,0.2); padding: 8px 20px; border-radius: 50px;">
                {{ now()->format('d/m/Y H:i') }}
            </div>
        </div>

        <!-- Contenedor principal -->
        <div class="logs-container">
            <div class="container-header">
                <div class="filters-wrapper">
                    <div class="search-wrapper">
                        <div class="search-container">
                            <span class="search-icon">🔍</span>
                            <input type="text" id="buscar-usuario" class="search-input"
                                placeholder="Buscar por usuario..." value="{{ request('usuario') }}">
                            <button id="btn-buscar-usuario" class="search-button">Buscar</button>
                        </div>
                    </div>

                    <select id="filtro-accion" class="filter-select">
                        <option value="">📋 Acción</option>
                        <option value="SELECT" {{ request('accion') == 'SELECT' ? 'selected' : '' }}>🔍 SELECT</option>
                        <option value="INSERT" {{ request('accion') == 'INSERT' ? 'selected' : '' }}>➕ INSERT</option>
                        <option value="UPDATE" {{ request('accion') == 'UPDATE' ? 'selected' : '' }}>✏️ UPDATE</option>
                        <option value="DELETE" {{ request('accion') == 'DELETE' ? 'selected' : '' }}>🗑️ DELETE</option>
                    </select>

                    <select id="filtro-tabla" class="filter-select">
                        <option value="">📁 Tabla</option>
                        <option value="proveedor" {{ request('tabla') == 'proveedor' ? 'selected' : '' }}>Proveedores
                        </option>
                        <option value="articulo" {{ request('tabla') == 'articulo' ? 'selected' : '' }}>Artículos</option>
                        <option value="licencia" {{ request('tabla') == 'licencia' ? 'selected' : '' }}>Licencias</option>
                        <option value="area" {{ request('tabla') == 'area' ? 'selected' : '' }}>Áreas</option>
                        <option value="software" {{ request('tabla') == 'software' ? 'selected' : '' }}>Software</option>
                        <option value="users" {{ request('tabla') == 'users' ? 'selected' : '' }}>Usuarios</option>
                    </select>

                    <input type="date" id="fecha-inicio" class="filter-select" style="min-width: 160px;"
                        value="{{ request('fecha_inicio') }}" placeholder="Fecha inicio">

                    @if(auth()->check() && auth()->user()->role === 'admin')
                        <button id="btn-limpiar" class="btn-delete"
                            style="background: #dc3545; color: white; border: none; padding: 6px 15px; border-radius: 50px; font-weight: 600; font-size: 12px; cursor: pointer;">
                            🗑️ Borrar
                        </button>
                    @endif
                </div>

                <div class="date-badge">
                    {{ $logs->total() }} registros
                </div>
            </div>

            <div class="container-content">
                <div class="section-title" id="tabla-titulo">
                    📋 Bitácora de Consultas SQL
                    <span>últimos registros</span>
                </div>

                <div class="table-container">
                    <table class="logs-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Fecha/Hora</th>
                                <th>Usuario</th>
                                <th>Acción</th>
                                <th>Tabla</th>
                                <th>Consulta SQL</th>
                                <th>Resultado</th>
                                <th>Duración</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-logs">
                            @forelse($logs as $log)
                                <tr>
                                    <td>{{ $log->id }}</td>
                                    <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>

                                    <td>
                                        <strong>{{ $log->user_name ?? 'Sistema' }}</strong>
                                        @if($log->user_id)
                                            <br><small style="color: #666;">ID: {{ $log->user_id }}</small>
                                        @endif
                                    </td>

                                    <td>
                                        @php
                                            $badgeClass = match ($log->accion) {
                                                'SELECT' => 'badge-select',
                                                'INSERT' => 'badge-insert',
                                                'UPDATE' => 'badge-update',
                                                'DELETE' => 'badge-delete',
                                                default => ''
                                            };
                                        @endphp
                                        <span class="{{ $badgeClass }}">{{ $log->accion }}</span>
                                    </td>

                                    <td><code>{{ $log->tabla ?? '-' }}</code></td>
                                    <td class="sql-query"><code>{{ $log->query }}</code></td>
                                    <td>{{ $log->resultado ?? '-' }}</td>
                                    <td class="duration">{{ number_format($log->duracion ?? 0, 3) }} sec</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="no-data">
                                        📭 No hay registros que coincidan con los filtros
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pagination">
                    {{ $logs->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL PARA BORRAR REGISTROS -->
    <div id="modal-borrar" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>🗑️ Eliminar Registros</h2>
                <span class="close-modal" onclick="cerrarModalBorrar()">&times;</span>
            </div>

            <div class="radio-group">
                <label class="radio-option">
                    <input type="radio" name="tipo-borrado" value="unico" checked onchange="toggleTipoBorrado()">
                    <span>Eliminar por ID específico</span>
                </label>
                <label class="radio-option">
                    <input type="radio" name="tipo-borrado" value="rango" onchange="toggleTipoBorrado()">
                    <span>Eliminar por rango de IDs</span>
                </label>
            </div>

            <div id="borrado-unico" class="input-group">
                <label for="id_borrar">ID del registro a eliminar:</label>
                <input type="number" id="id_borrar" class="filter-select" placeholder="Ej: 123" min="1">
            </div>

            <div id="borrado-rango" class="input-group" style="display: none;">
                <label>Rango de IDs a eliminar:</label>
                <div class="input-rango">
                    <input type="number" id="id_desde" placeholder="Desde" min="1">
                    <span>→</span>
                    <input type="number" id="id_hasta" placeholder="Hasta" min="1">
                </div>
                <p style="font-size: 12px; color: #666; margin-top: 8px;">Ej: Desde 1 hasta 100 eliminará los IDs 1 al
                    100</p>
            </div>

            <div class="modal-buttons" style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px;">
                <button onclick="cerrarModalBorrar()" class="search-button"
                    style="background: #6c757d;">Cancelar</button>
                <button onclick="confirmarBorrado()" class="btn-danger">🗑️ Eliminar Registros</button>
            </div>
        </div>
    </div>

    <script>
        // Función para aplicar filtros
        function aplicarFiltros() {
            const params = new URLSearchParams();

            const usuario = document.getElementById('buscar-usuario')?.value;
            const accion = document.getElementById('filtro-accion')?.value;
            const tabla = document.getElementById('filtro-tabla')?.value;
            const fechaInicio = document.getElementById('fecha-inicio')?.value;

            if (usuario) params.append('usuario', usuario);
            if (accion) params.append('accion', accion);
            if (tabla) params.append('tabla', tabla);
            if (fechaInicio) params.append('fecha_inicio', fechaInicio);

            window.location.href = '/logs?' + params.toString();
        }

        // Botón Buscar usuario
        document.getElementById('btn-buscar-usuario')?.addEventListener('click', function (e) {
            e.preventDefault();
            aplicarFiltros();
        });

        // Enter en búsqueda
        document.getElementById('buscar-usuario')?.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                aplicarFiltros();
            }
        });

        // Filtros automáticos
        document.getElementById('filtro-accion')?.addEventListener('change', function () {
            aplicarFiltros();
        });

        document.getElementById('filtro-tabla')?.addEventListener('change', function () {
            aplicarFiltros();
        });

        document.getElementById('fecha-inicio')?.addEventListener('change', function () {
            aplicarFiltros();
        });

        // Botón Limpiar - abre modal
        document.getElementById('btn-limpiar')?.addEventListener('click', function (e) {
            e.preventDefault();
            abrirModalBorrar();
        });

        // Funciones del modal
        function abrirModalBorrar() {
            document.getElementById('modal-borrar').style.display = 'flex';
            document.getElementById('id_borrar').value = '';
            document.getElementById('id_desde').value = '';
            document.getElementById('id_hasta').value = '';
        }

        function cerrarModalBorrar() {
            document.getElementById('modal-borrar').style.display = 'none';
        }

        function toggleTipoBorrado() {
            const tipo = document.querySelector('input[name="tipo-borrado"]:checked').value;
            const divUnico = document.getElementById('borrado-unico');
            const divRango = document.getElementById('borrado-rango');

            if (tipo === 'unico') {
                divUnico.style.display = 'block';
                divRango.style.display = 'none';
            } else {
                divUnico.style.display = 'none';
                divRango.style.display = 'block';
            }
        }

        function confirmarBorrado() {
            const tipo = document.querySelector('input[name="tipo-borrado"]:checked').value;

            if (tipo === 'unico') {
                const id = document.getElementById('id_borrar').value;
                if (!id) {
                    mostrarAlerta('error', '❌ Debes ingresar un ID');
                    return;
                }
                if (confirm(`⚠️ ¿Estás seguro de eliminar el registro con ID ${id}?`)) {
                    window.location.href = `/logs/eliminar/${id}`;
                }
            } else {
                const desde = document.getElementById('id_desde').value;
                const hasta = document.getElementById('id_hasta').value;
                if (!desde || !hasta) {
                    mostrarAlerta('error', '❌ Debes ingresar el rango de IDs');
                    return;
                }
                if (parseInt(desde) > parseInt(hasta)) {
                    mostrarAlerta('error', '❌ El ID "desde" debe ser menor que el ID "hasta"');
                    return;
                }
                if (confirm(`⚠️ ¿Estás seguro de eliminar los registros desde ID ${desde} hasta ID ${hasta}?`)) {
                    window.location.href = `/logs/eliminar-rango?desde=${desde}&hasta=${hasta}`;
                }
            }
        }

        function mostrarAlerta(tipo, mensaje) {
            // Crear alerta temporal
            const alerta = document.createElement('div');
            alerta.className = `alert-message alert-${tipo}`;
            alerta.textContent = mensaje;
            alerta.style.cssText = 'position:fixed;top:20px;right:20px;padding:15px 25px;border-radius:8px;color:white;font-weight:600;z-index:1100;background:#dc3545;';
            if (tipo === 'success') alerta.style.background = '#28a745';
            document.body.appendChild(alerta);
            setTimeout(() => alerta.remove(), 3000);
        }

        // Cerrar modal al hacer clic fuera
        window.onclick = function (event) {
            const modal = document.getElementById('modal-borrar');
            if (event.target === modal) {
                cerrarModalBorrar();
            }
        };
    </script>
</x-app-layout>