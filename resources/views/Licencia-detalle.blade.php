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
                @else
                    <!-- Mensaje para usuarios normales -->
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

                <!-- 🔥 Botón + - Visible para todos los usuarios autenticados (si no está agotada la capacidad) -->
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
                                        <button onclick="window.open('/articulo/{{ $art->RP }}', '_blank')"
                                            class="info-btn-small" title="Ver artículo">
                                            !
                                        </button>
                                        <!-- 🔥 Botón 🗑️ - SOLO para administradores -->
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

        <!-- ===== MODAL DE CONFIRMACIÓN ===== -->
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

        <script>
            const licenciaId = {{ $licencia->idLicencia }};
        </script>

        <script src="{{ asset('js/licencia-detalle.js') }}"></script>
</body>

</html>