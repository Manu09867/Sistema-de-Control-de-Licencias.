<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detalle de Licencia - {{ $licencia->Clave }}</title>

    <link rel="stylesheet" href="{{ asset('css/licencia-detalle.css') }}">
</head>

<body>
    <div class="container">
        <!-- Botón de regreso y acciones -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <a href="javascript:window.close()" class="back-btn">
                ← Cerrar ventana
            </a>

            <div class="action-buttons">
                @if(auth()->check() && auth()->user()->role === 'admin')
                    <button id="btnEditar" class="btn-edit" onclick="activarEdicion()">
                        <span>✏️</span> Editar
                    </button>
                    <button id="btnGuardar" class="btn-save" style="display: none;" onclick="guardarCambios()">
                        <span>💾</span> Guardar Cambios
                    </button>
                    <button id="btnCancelar" class="btn-cancel" style="display: none;" onclick="cancelarEdicion()">
                        <span>❌</span> Cancelar
                    </button>
                    {{-- DESPUÉS --}}
                    <button onclick="abrirModalEliminar()" class="btn-delete-header">
                        <span>🗑️</span> Eliminar
                    </button>
                @else
                    <span class="readonly-badge">
                        <span>👁️</span> Solo vista
                    </span>
                @endif
            </div>
        </div>

        <!-- Header -->
        <div class="header">
            <h1>
                <span>🔑</span>
                Detalle de Licencia
            </h1>
            <div class="header-badge">
                {{ $licencia->Clave }}
            </div>
        </div>

        <!-- CARD ÚNICA GRANDE -->
        <div class="info-card-large">
            <div class="card-title">
                <span>📋</span> Información Completa de la Licencia
            </div>

            <!-- Grid de 3 columnas -->
            <div class="info-grid-3">
                <!-- Columna 1: Información Básica -->
                <div class="info-section">
                    <div class="section-subtitle">
                        <span>🔑</span> Datos de la Licencia
                    </div>

                    <div class="info-row">
                        <span class="info-label">Clave:</span>
                        <span class="info-value" id="clave-text"><code>{{ $licencia->Clave ?? 'N/A' }}</code></span>
                        <input type="text" id="clave-input" class="edit-input" value="{{ $licencia->Clave }}"
                            style="display: none;" maxlength="45">
                    </div>

                    <div class="info-row">
                        <span class="info-label">Estado:</span>
                        <span class="info-value" id="estado-text">
                            @php
                                $estadoClass = match ($licencia->estadoLic) {
                                    'Activa' => 'badge-activo',
                                    'Por vencer' => 'badge-pronto',
                                    'Vencida' => 'badge-vencida',
                                    default => 'badge-inactivo'
                                };
                            @endphp
                            <span class="badge {{ $estadoClass }}">
                                {{ $licencia->estadoLic ?? 'N/A' }}
                            </span>
                        </span>
                        <select id="estado-input" class="edit-select" style="display: none;">
                            <option value="Activa" {{ $licencia->estadoLic == 'Activa' ? 'selected' : '' }}>✅ Activa
                            </option>
                            <option value="Por vencer" {{ $licencia->estadoLic == 'Por vencer' ? 'selected' : '' }}>⚠️ Por
                                vencer</option>
                            <option value="Vencida" {{ $licencia->estadoLic == 'Vencida' ? 'selected' : '' }}>📅 Vencida
                            </option>
                            <option value="Inactiva" {{ $licencia->estadoLic == 'Inactiva' ? 'selected' : '' }}>⛔ Inactiva
                            </option>
                        </select>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Software:</span>
                        <span class="info-value" id="software-text">{{ $licencia->software_nombre ?? 'N/A' }}</span>
                        <select id="software-input" class="edit-select" style="display: none;">
                            <option value="">-- Selecciona un software --</option>
                            @foreach($softwares as $software)
                                <option value="{{ $software->idSoftware }}" {{ $licencia->idSoftware == $software->idSoftware ? 'selected' : '' }}>
                                    {{ $software->Nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Capacidad:</span>
                        <span class="info-value" id="capacidad-text">
                            @if(isset($licencia->CapacidadLicencia) && $licencia->CapacidadLicencia)
                                <span class="capacidad-badge">📊 {{ $licencia->CapacidadLicencia }} equipos</span>
                            @else
                                <span class="capacidad-badge">♾️ Ilimitada</span>
                            @endif
                        </span>
                        <input type="number" id="capacidad-input" class="edit-input"
                            value="{{ $licencia->CapacidadLicencia }}" placeholder="Dejar vacío para ilimitado"
                            style="display: none;" min="1">
                    </div>

                    <div class="info-row">
                        <span class="info-label">Descripción:</span>
                        <span class="info-value" id="descripcion-text">
                            {{ $licencia->DescripcionLicencia ?? 'Sin descripción' }}
                        </span>
                        <textarea id="descripcion-input" class="edit-input" rows="3"
                            style="display: none; resize: vertical; min-height: 60px;"
                            placeholder="Descripción de la licencia"
                            maxlength="255">{{ $licencia->DescripcionLicencia ?? '' }}</textarea>
                    </div>
                </div>

                <!-- Columna 2: Fechas -->
                <div class="info-section">
                    <div class="section-subtitle">
                        <span>📅</span> Fechas Importantes
                    </div>

                    <div class="info-row">
                        <span class="info-label">Fecha Activación:</span>
                        <span class="info-value"
                            id="fecha-activacion-text">{{ $licencia->Fechacompra ? date('d/m/Y', strtotime($licencia->Fechacompra)) : 'N/A' }}</span>
                        <input type="date" id="fecha-activacion-input" class="edit-input"
                            value="{{ $licencia->Fechacompra }}" style="display: none;">
                    </div>

                    <div class="info-row">
                        <span class="info-label">Fecha Vencimiento:</span>
                        <span class="info-value" id="fecha-vencimiento-text">
                            {{ $licencia->Fechavencimiento ? date('d/m/Y', strtotime($licencia->Fechavencimiento)) : 'N/A' }}
                            @php
                                if ($licencia->Fechavencimiento) {
                                    $dias = (strtotime($licencia->Fechavencimiento) - time()) / 86400;
                                    if ($dias < 0) {
                                        echo '<span class="dias-restantes" style="background: #f8d7da; color: #721c24;">Vencida</span>';
                                    } elseif ($dias < 30) {
                                        echo '<span class="dias-restantes" style="background: #fff3cd; color: #856404;">Vence en ' . round($dias) . ' días</span>';
                                    } else {
                                        echo '<span class="dias-restantes" style="background: #d4edda; color: #155724;">Vigente</span>';
                                    }
                                }
                            @endphp
                        </span>
                        <input type="date" id="fecha-vencimiento-input" class="edit-input"
                            value="{{ $licencia->Fechavencimiento }}" style="display: none;">
                    </div>

                    <div class="info-row">
                        <span class="info-label">Total Artículos:</span>
                        <span class="info-value">
                            <span style="font-weight: 600; color: #4a6fa5;">{{ $licencia->total_articulos ?? 0 }}
                                asignados</span>
                            @if(isset($licencia->CapacidadLicencia) && $licencia->CapacidadLicencia && ($licencia->total_articulos ?? 0) >= $licencia->CapacidadLicencia)
                                <span class="dias-restantes"
                                    style="background: #f8d7da; color: #721c24; margin-left: 10px;">
                                    ⚠️ Capacidad agotada
                                </span>
                            @endif
                        </span>
                    </div>
                </div>

                <!-- Columna 3: Licitación -->
                <div class="info-section">
                    <div class="section-subtitle">
                        <span>📄</span> Licitación
                    </div>

                    <div class="info-row">
                        <span class="info-label">Folio:</span>
                        <span class="info-value">{{ $licencia->folio_licitacion ?? 'N/A' }}</span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Proveedor:</span>
                        <span class="info-value">{{ $licencia->proveedor ?? 'N/A' }}</span>
                    </div>

                    @if(isset($licencia->descripcion_licitacion) && $licencia->descripcion_licitacion)
                        <div class="info-row">
                            <span class="info-label">Descripción:</span>
                            <span class="info-value">{{ $licencia->descripcion_licitacion }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sección de Artículos Asignados -->
        <div class="articulos-section">
            <div class="card-title" style="font-size: 20px; margin-bottom: 15px;">
                <span>📦</span> Artículos con esta Licencia ({{ count($articulos) }})
                @if(isset($licencia->CapacidadLicencia) && $licencia->CapacidadLicencia)
                    @php
                        $capacidad = $licencia->CapacidadLicencia;
                        $total = $licencia->total_articulos ?? 0;
                        $capacidadAgotada = $total >= $capacidad;
                    @endphp
                    @if($capacidadAgotada)
                        <span class="capacidad-agotada"
                            style="background: #f8d7da; color: #721c24; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 5px;">
                            ⚠️ Capacidad agotada ({{ $total }}/{{ $capacidad }})
                        </span>
                    @else
                        <span
                            style="font-size: 12px; font-weight: normal; background: #e6ecf2; padding: 4px 12px; border-radius: 20px; margin-left: 10px;">
                            Capacidad: {{ $total }}/{{ $capacidad }}
                        </span>
                    @endif
                @endif

                @if(auth()->check() && !(isset($licencia->CapacidadLicencia) && $licencia->CapacidadLicencia && ($licencia->total_articulos ?? 0) >= $licencia->CapacidadLicencia))
                    <button onclick="abrirModalArticulos()" class="btn-add">
                        ➕
                    </button>
                @endif
            </div>

            @if(count($articulos) > 0)
                <div class="table-container">
                    <table class="articulos-table">
                        <thead>
                            <tr>
                                <th>Serie</th>
                                <th>RP</th>
                                <th>Producto</th>
                                <th>Marca</th>
                                <th>Modelo</th>
                                <th>Área</th>
                                <th>Estado</th>
                                <th>Observación</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($articulos as $art)
                                <tr>
                                    <td><strong>{{ $art->serie ?? 'N/A' }}</strong></td>
                                    <td>{{ $art->RP ?? 'N/A' }}</td>
                                    <td>{{ $art->producto ?? 'N/A' }}</td>
                                    <td>{{ $art->marca ?? 'N/A' }}</td>
                                    <td>{{ $art->modelo ?? 'N/A' }}</td>
                                    <td>{{ $art->area ?? 'N/A' }}</td>
                                    <td>
                                        @php
                                            $estadoClass = match ($art->estado) {
                                                'Activo' => 'badge-activo',
                                                'Mantenimiento' => 'badge-pronto',
                                                default => 'badge-inactivo'
                                            };
                                        @endphp
                                        <span class="badge {{ $estadoClass }}">
                                            {{ $art->estado ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="observacion-cell" title="{{ $art->observacion ?? 'Sin observación' }}">
                                        {{ $art->observacion ?? '—' }}
                                    </td>
                                    <td style="white-space: nowrap;">
                                        <button onclick="window.open('/articulo/{{ $art->serie }}', '_blank')"
                                            class="info-btn-small" title="Ver artículo">
                                            !
                                        </button>
                                        @if(auth()->check() && auth()->user()->role === 'admin')
                                            <button
                                                onclick="eliminarAsignacion({{ $art->idAsignacion }}, '{{ $art->serie }}', '{{ $art->RP }}')"
                                                class="btn-delete" title="Eliminar asignación">
                                                🗑️
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="no-data">
                    📭 No hay artículos asignados a esta licencia
                </div>
            @endif
        </div>

        <div style="text-align: center; margin-top: 20px; color: #666; font-size: 12px;">
            Información actualizada: {{ now()->format('d/m/Y H:i:s') }}
        </div>
    </div>

    <div id="alerta" class="alert-message"></div>

    <!-- ===== MODAL PARA BUSCAR ARTÍCULOS ===== -->
    <div id="modalArticulos" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>🔍 Buscar Artículo</h2>
                <span class="close-modal" onclick="cerrarModalArticulos()">&times;</span>
            </div>

            <input type="text" id="buscarArticulo" class="edit-input"
                placeholder="Buscar por serie, RP, producto o marca..." onkeyup="buscarArticulos()"
                style="margin-bottom: 15px; width: 100%;">

            <div class="table-container" style="max-height: 400px; overflow-y: auto;">
                <table class="articulos-table" style="min-width: 100%;">
                    <thead>
                        <tr>
                            <th>Serie</th>
                            <th>RP</th>
                            <th>Producto</th>
                            <th>Marca</th>
                            <th>Estado</th>
                    </thead>
                    <tbody id="tablaArticulos">
                        <tr <td colspan="5" class="no-data">Cargando artículos... </td </tr </tbody>
                </table </div>

                <div class="modal-buttons">
                    <button onclick="cerrarModalArticulos()" class="btn-cancel">Cancelar</button>
                </div>
            </div>
        </div>
    </div> {{-- ← cierre de modalArticulos --}}

    <!-- ===== MODAL DE CONFIRMACIÓN PARA ASIGNAR ===== -->
    <div id="modalConfirmar" class="modal">
        <div class="modal-content" style="max-width: 500px;">
            <div class="modal-header">
                <h2>✅ Confirmar Asignación</h2>
                <span class="close-modal" onclick="cerrarModalConfirmar()">&times;</span>
            </div>

            <div id="infoArticulo"
                style="background: #f8fafc; border-radius: 12px; padding: 20px; margin-bottom: 20px;">
                <!-- Se llena con JS -->
            </div>

            <div class="modal-buttons">
                <button onclick="cerrarModalConfirmar()" class="btn-cancel">Cancelar</button>
                <button onclick="confirmarAsignacion()" class="btn-save">✅ Confirmar Asignación</button>
            </div>
        </div>
    </div>

    <!-- ===== MODAL DE CONFIRMACIÓN PARA ELIMINAR LICENCIA ===== -->
    <div id="modal-eliminar" class="modal"
        style="display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); align-items: center; justify-content: center;">
        <div class="modal-content"
            style="background: white; border-radius: 20px; padding: 30px; max-width: 450px; width: 90%; margin: 0 auto;">
            <div class="modal-header"
                style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 3px solid #dc3545;">
                <h2 style="color: #0f3057; font-size: 24px; margin: 0;">⚠️ Eliminar Licencia</h2>
                <span class="close-modal" onclick="cerrarModalEliminar()"
                    style="font-size: 28px; font-weight: bold; color: #666; cursor: pointer;">&times;</span>
            </div>

            <p style="margin-bottom: 20px; color: #666;">
                ¿Estás seguro de eliminar la licencia con clave <strong>{{ $licencia->Clave }}</strong>?
            </p>

            <p style="margin-bottom: 15px; color: #dc3545; font-size: 14px;">
                ⚠️ Esta acción eliminará:
            </p>

            <ul style="margin-bottom: 20px; margin-left: 20px; color: #666; font-size: 13px;">
                <li>La licencia completa</li>
                <li>Todas sus asignaciones a artículos</li>
                <li>Se actualizará el total de la licitación</li>
            </ul>

            <div style="margin-bottom: 20px;">
                <label for="password_confirmacion" style="display: block; margin-bottom: 8px; font-weight: 600;">
                    Contraseña de administrador <span style="color: #dc3545;">*</span>
                </label>
                <input type="password" id="password_confirmacion" class="edit-input"
                    style="width: 100%; padding: 12px 15px; border-radius: 8px; border: 1px solid #ccd6dd; font-size: 14px;"
                    placeholder="Ingresa tu contraseña">
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button onclick="cerrarModalEliminar()"
                    style="background: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 50px; font-weight: 600; cursor: pointer;">Cancelar</button>
                <button onclick="confirmarEliminarLicencia()" id="btnConfirmarEliminar"
                    style="background: #dc3545; color: white; border: none; padding: 10px 20px; border-radius: 50px; font-weight: 600; cursor: pointer;">🗑️
                    Eliminar</button>
            </div>
        </div>
    </div>

    <script>
        const licenciaId = {{ $licencia->idLicencia }};
    </script>

    <script>
        // ===== FUNCIONES DE EDICIÓN =====
        let modoEdicion = false;

        function activarEdicion() {
            modoEdicion = true;

            document.getElementById('clave-text').style.display = 'none';
            document.getElementById('clave-input').style.display = 'block';
            document.getElementById('estado-text').style.display = 'none';
            document.getElementById('estado-input').style.display = 'block';
            document.getElementById('software-text').style.display = 'none';
            document.getElementById('software-input').style.display = 'block';
            document.getElementById('capacidad-text').style.display = 'none';
            document.getElementById('capacidad-input').style.display = 'block';
            document.getElementById('descripcion-text').style.display = 'none';
            document.getElementById('descripcion-input').style.display = 'block';
            document.getElementById('fecha-activacion-text').style.display = 'none';
            document.getElementById('fecha-activacion-input').style.display = 'block';
            document.getElementById('fecha-vencimiento-text').style.display = 'none';
            document.getElementById('fecha-vencimiento-input').style.display = 'block';

            document.getElementById('btnEditar').style.display = 'none';
            document.getElementById('btnGuardar').style.display = 'inline-flex';
            document.getElementById('btnCancelar').style.display = 'inline-flex';
        }

        function cancelarEdicion() {
            modoEdicion = false;

            document.getElementById('clave-text').style.display = 'block';
            document.getElementById('clave-input').style.display = 'none';
            document.getElementById('estado-text').style.display = 'block';
            document.getElementById('estado-input').style.display = 'none';
            document.getElementById('software-text').style.display = 'block';
            document.getElementById('software-input').style.display = 'none';
            document.getElementById('capacidad-text').style.display = 'block';
            document.getElementById('capacidad-input').style.display = 'none';
            document.getElementById('descripcion-text').style.display = 'block';
            document.getElementById('descripcion-input').style.display = 'none';
            document.getElementById('fecha-activacion-text').style.display = 'block';
            document.getElementById('fecha-activacion-input').style.display = 'none';
            document.getElementById('fecha-vencimiento-text').style.display = 'block';
            document.getElementById('fecha-vencimiento-input').style.display = 'none';

            document.getElementById('btnEditar').style.display = 'inline-flex';
            document.getElementById('btnGuardar').style.display = 'none';
            document.getElementById('btnCancelar').style.display = 'none';
        }

        async function guardarCambios() {
            const clave = document.getElementById('clave-input').value.trim();
            const estado = document.getElementById('estado-input').value;
            const idSoftware = document.getElementById('software-input').value;
            const capacidad = document.getElementById('capacidad-input').value;
            const descripcion = document.getElementById('descripcion-input').value.trim();
            const fechaActivacion = document.getElementById('fecha-activacion-input').value;
            const fechaVencimiento = document.getElementById('fecha-vencimiento-input').value;

            if (!clave) { mostrarAlerta('error', 'La clave de licencia es requerida'); return; }
            if (!idSoftware) { mostrarAlerta('error', 'Debes seleccionar un software'); return; }
            if (!fechaActivacion) { mostrarAlerta('error', 'La fecha de activación es requerida'); return; }
            if (!fechaVencimiento) { mostrarAlerta('error', 'La fecha de vencimiento es requerida'); return; }

            const btn = document.getElementById('btnGuardar');
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span>⏳</span> Guardando...';

            try {
                const response = await fetch(`/licencia/${licenciaId}/actualizar`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        clave, estado, idSoftware,
                        capacidad: capacidad || null,
                        descripcion: descripcion || null,
                        fecha_activacion: fechaActivacion,
                        fecha_vencimiento: fechaVencimiento
                    })
                });

                const data = await response.json();

                if (data.success) {
                    mostrarAlerta('success', '✅ Licencia actualizada correctamente');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    mostrarAlerta('error', data.message || 'Error al guardar');
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            } catch (error) {
                console.error('Error:', error);
                mostrarAlerta('error', 'Error de conexión al servidor');
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        }

        // ===== FUNCIONES PARA ASIGNAR ARTÍCULO =====
        let articuloSeleccionado = null;
        let observacionActual = '';

        function abrirModalArticulos() {
            const capacidadTexto = document.getElementById('capacidad-text')?.innerText || '';
            const capacidadMatch = capacidadTexto.match(/\d+/);
            const capacidad = capacidadMatch ? parseInt(capacidadMatch[0]) : 0;
            const totalTexto = document.querySelector('.info-value span:first-child')?.innerText || '0';
            const totalAsignados = parseInt(totalTexto) || 0;

            if (capacidad > 0 && totalAsignados >= capacidad) {
                mostrarAlerta('error', '⚠️ Capacidad máxima alcanzada. No se pueden agregar más artículos.');
                return;
            }

            const modal = document.getElementById('modalArticulos');
            if (modal) {
                modal.style.display = 'flex';
                modal.style.alignItems = 'center';
                modal.style.justifyContent = 'center';
                cargarArticulos();
            }
        }

        function cerrarModalArticulos() {
            const modal = document.getElementById('modalArticulos');
            if (modal) modal.style.display = 'none';
        }

        function cerrarModalConfirmar() {
            const modal = document.getElementById('modalConfirmar');
            if (modal) modal.style.display = 'none';
            articuloSeleccionado = null;
            observacionActual = '';
        }

        async function cargarArticulos() {
            const tbody = document.getElementById('tablaArticulos');
            if (!tbody) return;
            tbody.innerHTML = '<tr><td colspan="5" class="no-data">Cargando artículos...</td></tr>';

            try {
                const response = await fetch(`/articulos/lista?licencia_id=${licenciaId}`);
                const data = await response.json();
                renderArticulos(data);
            } catch (error) {
                console.error('Error:', error);
                tbody.innerHTML = '<tr><td colspan="5" class="no-data">Error al cargar artículos</td></tr>';
            }
        }

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function renderArticulos(articulos) {
            const tbody = document.getElementById('tablaArticulos');
            if (!tbody) return;
            tbody.innerHTML = '';

            if (articulos.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="no-data">No hay artículos disponibles</td></tr>';
                return;
            }

            articulos.forEach(art => {
                const tr = document.createElement('tr');

                if (art.ya_asignado) {
                    tr.className = 'fila-asignada';
                    tr.onclick = (e) => {
                        e.stopPropagation();
                        mostrarAlerta('error', `⚠️ El artículo ${art.serie} (${art.RP}) ya tiene esta licencia asignada`);
                    };
                } else {
                    tr.className = 'fila-disponible';
                    tr.onclick = (e) => {
                        e.stopPropagation();
                        seleccionarArticulo(art);
                    };
                }

                tr.style.cursor = art.ya_asignado ? 'not-allowed' : 'pointer';
                tr.innerHTML = `
                        <td><strong>${escapeHtml(art.serie) || 'N/A'}</strong> ${art.ya_asignado ? '<span style="color:#28a745;">✓</span>' : ''}</td>
                        <td>${escapeHtml(art.RP) || 'N/A'}</td>
                        <td>${escapeHtml(art.producto) || 'N/A'}</td>
                        <td>${escapeHtml(art.marca) || 'N/A'}</td>
                        <td style="text-align: center;">
                            ${art.ya_asignado ?
                        '<span class="badge-asignado" style="background:#d4edda; color:#155724; padding:4px 8px; border-radius:12px; font-size:11px;">Ya asignada</span>' :
                        '<span class="badge-disponible" style="background:#cce5ff; color:#004085; padding:4px 8px; border-radius:12px; font-size:11px;">Disponible</span>'}
                        </td>
                    `;
                tbody.appendChild(tr);
            });
        }

        async function buscarArticulos() {
            const query = document.getElementById('buscarArticulo')?.value.trim() || '';
            const tbody = document.getElementById('tablaArticulos');
            if (!tbody) return;

            tbody.innerHTML = '<tr><td colspan="5" class="no-data">Buscando...</td></tr>';

            try {
                const response = await fetch(`/articulos/lista?q=${encodeURIComponent(query)}&licencia_id=${licenciaId}`);
                const data = await response.json();
                renderArticulos(data);
            } catch (error) {
                console.error('Error:', error);
                tbody.innerHTML = '<tr><td colspan="5" class="no-data">Error al buscar</td></tr>';
            }
        }

        function seleccionarArticulo(art) {
            articuloSeleccionado = art;

            const infoDiv = document.getElementById('infoArticulo');
            if (infoDiv) {
                infoDiv.innerHTML = `
                        <div style="background: #f8fafc; border-radius: 12px; padding: 15px; margin-bottom: 20px;">
                            <p><strong>📦 Serie:</strong> ${escapeHtml(art.serie) || 'N/A'}</p>
                            <p><strong>🔖 RP:</strong> ${escapeHtml(art.RP) || 'N/A'}</p>
                            <p><strong>🏷️ Producto:</strong> ${escapeHtml(art.producto) || 'N/A'}</p>
                            <p><strong>🏭 Marca:</strong> ${escapeHtml(art.marca) || 'N/A'}</p>
                        </div>
                        <div style="margin-bottom: 15px;">
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #0f3057;">📝 Observación (opcional, máx. 85 caracteres):</label>
                            <textarea id="observacionInput" class="campo-observacion" rows="2" maxlength="85" placeholder="Ej: Licencia asignada para equipo de desarrollo..."></textarea>
                            <div id="charCounter" class="char-counter" style="font-size: 12px; color: #666; margin-top: 5px;">0/85</div>
                        </div>
                        <p style="color: #666; font-size: 14px;">¿Deseas asignar esta licencia al artículo?</p>
                    `;
            }

            const observacionInput = document.getElementById('observacionInput');
            if (observacionInput) {
                observacionInput.addEventListener('input', function () {
                    const length = this.value.length;
                    const counter = document.getElementById('charCounter');
                    if (counter) counter.textContent = `${length}/85`;
                    observacionActual = this.value;
                });
            }

            cerrarModalArticulos();
            const modalConfirmar = document.getElementById('modalConfirmar');
            if (modalConfirmar) {
                modalConfirmar.style.display = 'flex';
                modalConfirmar.style.alignItems = 'center';
                modalConfirmar.style.justifyContent = 'center';
            }
        }

        async function confirmarAsignacion() {
            if (!articuloSeleccionado) {
                mostrarAlerta('error', 'No hay artículo seleccionado');
                return;
            }

            const observacion = document.getElementById('observacionInput')?.value || '';

            const btn = document.querySelector('#modalConfirmar .btn-save');
            if (!btn) return;
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '⏳ Asignando...';

            try {
                const response = await fetch('/licencia/asignar-articulo', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        idLicencia: licenciaId,
                        idArticulo: articuloSeleccionado.idArticulo,
                        observacion: observacion
                    })
                });

                const data = await response.json();

                if (data.success) {
                    mostrarAlerta('success', '✅ Licencia asignada correctamente');
                    cerrarModalConfirmar();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    mostrarAlerta('error', data.message || 'Error al asignar');
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            } catch (error) {
                console.error('Error:', error);
                mostrarAlerta('error', 'Error de conexión al servidor');
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        }

        // ===== FUNCIÓN PARA ELIMINAR ASIGNACIÓN =====
        async function eliminarAsignacion(idAsignacion, serie, rp) {
            const confirmar = confirm(`⚠️ ¿Estás seguro de eliminar la asignación?\n\nArtículo: ${serie} (${rp})\n\nEsta acción no se puede deshacer.`);
            if (!confirmar) return;

            try {
                const response = await fetch(`/licencia/asignacion/${idAsignacion}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();

                if (data.success) {
                    mostrarAlerta('success', '✅ Asignación eliminada correctamente');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    mostrarAlerta('error', data.message || 'Error al eliminar');
                }
            } catch (error) {
                console.error('Error:', error);
                mostrarAlerta('error', 'Error de conexión al servidor');
            }
        }

        // ===== FUNCIONES PARA ELIMINAR LICENCIA =====
        function abrirModalEliminar() {
            const modal = document.getElementById('modal-eliminar');
            if (modal) {
                modal.style.display = 'flex';
                modal.style.alignItems = 'center';
                modal.style.justifyContent = 'center';
            }
            const passwordInput = document.getElementById('password_confirmacion');
            if (passwordInput) {
                passwordInput.value = '';
                passwordInput.focus();
            }
        }

        function cerrarModalEliminar() {
            const modal = document.getElementById('modal-eliminar');
            if (modal) modal.style.display = 'none';
        }

        async function confirmarEliminarLicencia() {
            const password = document.getElementById('password_confirmacion').value;

            if (!password) {
                mostrarAlerta('error', '❌ Ingresa tu contraseña para confirmar');
                return;
            }

            const btn = document.getElementById('btnConfirmarEliminar');
            if (!btn) return;
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '⏳ Eliminando...';

            try {
                const response = await fetch(`/api/licencias/${licenciaId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ password: password })
                });

                const data = await response.json();

                if (data.success) {
                    mostrarAlerta('success', data.message);
                    cerrarModalEliminar();
                    setTimeout(() => {
                        window.close();
                    }, 2000);
                } else {
                    mostrarAlerta('error', data.message);
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            } catch (error) {
                console.error('Error:', error);
                mostrarAlerta('error', '❌ Error de conexión');
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        }

        // ===== FUNCIÓN DE ALERTA =====
        function mostrarAlerta(tipo, mensaje) {
            const alerta = document.getElementById('alerta');
            if (alerta) {
                alerta.className = `alert-message alert-${tipo}`;
                alerta.textContent = mensaje;
                alerta.style.display = 'block';
                setTimeout(() => {
                    alerta.style.display = 'none';
                }, 3000);
            }
        }

        // ===== CERRAR MODALES =====
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                cerrarModalArticulos();
                cerrarModalConfirmar();
                cerrarModalEliminar();
            }
        });

        window.onclick = function (event) {
            const modalArticulos = document.getElementById('modalArticulos');
            const modalConfirmar = document.getElementById('modalConfirmar');
            const modalEliminar = document.getElementById('modal-eliminar');

            if (event.target === modalArticulos) cerrarModalArticulos();
            if (event.target === modalConfirmar) cerrarModalConfirmar();
            if (event.target === modalEliminar) cerrarModalEliminar();
        }
    </script>
</body>

</html>