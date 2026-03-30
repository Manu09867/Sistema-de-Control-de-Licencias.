<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detalle del Artículo - {{ $articulo->RP }}</title>
    
    <!-- CSS separado -->
    <link rel="stylesheet" href="{{ asset('css/articulo-detalle.css') }}">
</head>
<body>
    <!-- Overlay y modal para agregar tipo de grupo (solo visible para admin) -->
    @if(auth()->check() && auth()->user()->role === 'admin')
    <div class="overlay" id="overlay" onclick="cerrarModalTipo()"></div>
    <div class="modal-small" id="modal-tipo">
        <h3>➕ Agregar Tipo de Grupo</h3>
        <div class="form-group">
            <label for="nuevo_tipo_nombre">Nombre del tipo:</label>
            <input type="text" id="nuevo_tipo_nombre" class="edit-input" placeholder="Ej: EQUIPO_COMPUTO, SERVIDOR, PERIFERICO..." maxlength="45">
        </div>
        <div class="btn-group">
            <button class="btn-cancel" onclick="cerrarModalTipo()" style="flex:1;">Cancelar</button>
            <button class="btn-save" onclick="guardarNuevoTipoGrupo()" style="flex:1;">Guardar</button>
        </div>
    </div>
    @endif

    <div class="container">
        <!-- Botón de regreso y acciones -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <a href="javascript:window.close()" class="back-btn">
                ← Cerrar ventana
            </a>
            
            <div class="action-buttons">
                @if(auth()->check() && auth()->user()->role === 'admin')
                    <!-- Solo admin ve los botones de edición -->
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
                    <span class="readonly-badge">
                        <span>👁️</span> Solo vista
                    </span>
                @endif
            </div>
        </div>

        <!-- Header -->
        <div class="header">
            <h1>
                <span>📦</span> 
                Detalle del Artículo
            </h1>
            <div class="header-badge">
                {{ $articulo->RP }}
            </div>
        </div>

        <!-- CARD ÚNICA GRANDE -->
        <div class="info-card-large">
            <div class="card-title">
                <span>📋</span> Información Completa del Artículo
            </div>

            <!-- Grid de 3 columnas -->
            <div class="info-grid-3">
                <!-- Columna 1: Información Básica -->
                <div class="info-section">
                    <div class="section-subtitle">
                        <span>📋</span> Datos Generales
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">Serie:</span>
                        <span class="info-value" id="serie-text"><strong>{{ $articulo->serie ?? 'N/A' }}</strong></span>
                        @if(auth()->check() && auth()->user()->role === 'admin')
                        <input type="text" id="serie-input" class="edit-input" value="{{ $articulo->serie }}" style="display: none;" maxlength="45">
                        @endif
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">Estado:</span>
                        <span class="info-value" id="estado-text">
                            @php
                                $estadoClass = match($articulo->estado) {
                                    'Activo' => 'badge-activo',
                                    'Mantenimiento' => 'badge-mantenimiento',
                                    default => 'badge-inactivo'
                                };
                            @endphp
                            <span class="badge {{ $estadoClass }}">
                                {{ $articulo->estado ?? 'N/A' }}
                            </span>
                        </span>
                        @if(auth()->check() && auth()->user()->role === 'admin')
                        <select id="estado-input" class="edit-select" style="display: none;">
                            <option value="Activo" {{ $articulo->estado == 'Activo' ? 'selected' : '' }}>✅ Activo</option>
                            <option value="Mantenimiento" {{ $articulo->estado == 'Mantenimiento' ? 'selected' : '' }}>🔧 Mantenimiento</option>
                            <option value="Inactivo" {{ $articulo->estado == 'Inactivo' ? 'selected' : '' }}>❌ Inactivo</option>
                            <option value="Almacén" {{ $articulo->estado == 'Almacén' ? 'selected' : '' }}>📦 Almacén</option>
                            <option value="Baja" {{ $articulo->estado == 'Baja' ? 'selected' : '' }}>🗑️ Baja</option>
                        </select>
                        @endif
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">Área:</span>
                        <span class="info-value" id="area-text">{{ $articulo->NombreArea ?? 'Sin asignar' }}</span>
                        @if(auth()->check() && auth()->user()->role === 'admin')
                        <select id="area-input" class="edit-select" style="display: none;">
                            <option value="">-- Sin asignar --</option>
                            @foreach($areas as $area)
                                <option value="{{ $area->idArea }}" {{ $articulo->idArea == $area->idArea ? 'selected' : '' }}>
                                    {{ $area->NombreArea }}
                                </option>
                            @endforeach
                        </select>
                        @endif
                    </div>
                    
                    <!-- GRUPO (input de texto - como la serie) -->
                    <div class="info-row">
                        <span class="info-label">Grupo:</span>
                        <span class="info-value" id="grupo-text">{{ $articulo->NombreGrupo ?? 'Sin grupo' }}</span>
                        @if(auth()->check() && auth()->user()->role === 'admin')
                        <input type="text" id="grupo-input" class="edit-input" value="{{ $articulo->NombreGrupo }}" style="display: none;" placeholder="Nombre del grupo" maxlength="100">
                        @endif
                    </div>
                    
                    <!-- TIPO DE GRUPO (select con opciones) -->
                    <div class="info-row">
                        <span class="info-label">Tipo Grupo:</span>
                        <span class="info-value" id="tipo-grupo-text">
                            @if($articulo->TipoGrupo)
                                <span class="badge" style="background: #4a6fa5; color: white;">{{ $articulo->TipoGrupo }}</span>
                            @else
                                No aplica
                            @endif
                        </span>
                        @if(auth()->check() && auth()->user()->role === 'admin')
                        <div class="input-group" style="display: none;" id="tipo-grupo-container">
                            <select id="tipo-grupo-input" class="edit-select">
                                <option value="">-- Seleccionar tipo --</option>
                                <option value="EQUIPO_COMPUTO" {{ $articulo->TipoGrupo == 'EQUIPO_COMPUTO' ? 'selected' : '' }}>💻 EQUIPO_COMPUTO</option>
                                <option value="SERVIDOR" {{ $articulo->TipoGrupo == 'SERVIDOR' ? 'selected' : '' }}>🖥️ SERVIDOR</option>
                                @foreach($tiposGrupo as $tipo)
                                    @if(!in_array($tipo, ['EQUIPO_COMPUTO', 'SERVIDOR']))
                                        <option value="{{ $tipo }}" {{ $articulo->TipoGrupo == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <button class="btn-add-small" onclick="abrirModalTipo()">➕</button>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Columna 2: Producto (solo vista) -->
                <div class="info-section">
                    <div class="section-subtitle">
                        <span>🏷️</span> Producto
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">Producto:</span>
                        <span class="info-value">{{ $articulo->producto ?? 'N/A' }}</span>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">Marca:</span>
                        <span class="info-value">{{ $articulo->Marca ?? 'N/A' }}</span>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">Modelo:</span>
                        <span class="info-value">{{ $articulo->Modelo ?? 'N/A' }}</span>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">Tipo:</span>
                        <span class="info-value">{{ $articulo->tipo_producto ?? 'N/A' }}</span>
                    </div>
                </div>

                <!-- Columna 3: Licitación (con descripción) -->
                <div class="info-section">
                    <div class="section-subtitle">
                        <span>📄</span> Licitación
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">Folio:</span>
                        <span class="info-value">{{ $articulo->folio_licitacion ?? 'N/A' }}</span>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">Proveedor:</span>
                        <span class="info-value">{{ $articulo->proveedor ?? 'N/A' }}</span>
                    </div>
                    
                    @if(isset($articulo->descripcion_licitacion) && $articulo->descripcion_licitacion)
                    <div class="info-row">
                        <span class="info-label">Descripción:</span>
                        <span class="info-value">{{ $articulo->descripcion_licitacion }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- SECCIÓN DE RED (solo si es router o switch) -->
            @if($infoRed)
            <div class="red-section">
                <div class="red-title">
                    @if($tipoRed == 'router')
                        <span style="font-size: 24px;">🔷</span> Información del Router
                    @else
                        <span style="font-size: 24px;">🔶</span> Información del Switch
                    @endif
                </div>
                
                <div class="red-grid">
                    <!-- MAC -->
                    <div class="info-row" style="border-bottom: none; padding: 0;">
                        <span class="info-label">MAC:</span>
                        <span class="info-value" id="mac-text">
                            {{ $tipoRed == 'router' ? $infoRed->MACR : $infoRed->MACSw ?? 'N/A' }}
                        </span>
                        @if(auth()->check() && auth()->user()->role === 'admin')
                        <input type="text" id="mac-input" class="edit-input" 
                               value="{{ $tipoRed == 'router' ? $infoRed->MACR : $infoRed->MACSw }}" 
                               style="display: none;" placeholder="00:11:22:33:44:55" maxlength="90">
                        @endif
                    </div>

                    <!-- IP -->
                    <div class="info-row" style="border-bottom: none; padding: 0;">
                        <span class="info-label">IP:</span>
                        <span class="info-value" id="ip-text">
                            {{ $tipoRed == 'router' ? $infoRed->IpaddressR : $infoRed->IpaddressSw ?? 'N/A' }}
                        </span>
                        @if(auth()->check() && auth()->user()->role === 'admin')
                        <input type="text" id="ip-input" class="edit-input" 
                               value="{{ $tipoRed == 'router' ? $infoRed->IpaddressR : $infoRed->IpaddressSw }}" 
                               style="display: none;" placeholder="192.168.1.1" maxlength="45">
                        @endif
                    </div>

                    <!-- Observación -->
                    <div class="info-row" style="border-bottom: none; padding: 0;">
                        <span class="info-label">Observación:</span>
                        <span class="info-value" id="obs-text">
                            {{ $tipoRed == 'router' ? $infoRed->ObservacionR : $infoRed->ObservacionSw ?? 'N/A' }}
                        </span>
                        @if(auth()->check() && auth()->user()->role === 'admin')
                        <input type="text" id="obs-input" class="edit-input" 
                               value="{{ $tipoRed == 'router' ? $infoRed->ObservacionR : $infoRed->ObservacionSw }}" 
                               style="display: none;" placeholder="Observaciones" maxlength="90">
                        @endif
                    </div>
                </div>
                
                <!-- Campo oculto para tipo de red -->
                <input type="hidden" id="tipo-red" value="{{ $tipoRed }}">
            </div>
            @endif
        </div>

        <!-- Sección de Licencias CON BOTÓN 🔑 -->
        @if(count($licencias) > 0)
        <div class="licencias-section">
    <div class="card-title" style="font-size: 20px; margin-bottom: 15px;">
        <span>🔑</span> Licencias Asignadas ({{ count($licencias) }})
    </div>

    <table class="licencias-table">
        <thead>
            <tr>
                <th>Software</th>
                <th>Clave</th>
                <th>Fecha Compra</th>
                <th>Vencimiento</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($licencias as $lic)
            <tr>
                <td>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <strong>{{ $lic->software }}</strong>
                        <button 
                            onclick="window.open('/licencia/{{ $lic->Clave }}', '_blank')"
                            class="info-btn-small"
                            title="Ver licencia"
                            style="background: #4a6fa5;"
                        >
                            🔑
                        </button>
                    </div>
                </td>

                <td>
                    <code>{{ $lic->Clave }}</code>
                </td>

                <td>
                    {{ date('d/m/Y', strtotime($lic->Fechacompra)) }}
                </td>

                <td>
                    @php
                        $dias = (strtotime($lic->Fechavencimiento) - time()) / 86400;
                        $color = $dias < 30 ? '#dc3545' : ($dias < 90 ? '#ffc107' : '#28a745');
                    @endphp
                    <span style="color: {{ $color }}; font-weight: 600;">
                        {{ date('d/m/Y', strtotime($lic->Fechavencimiento)) }}
                        @if($dias < 0)
                            (Vencida)
                        @elseif($dias < 30)
                            (Pronto)
                        @endif
                    </span>
                </td>

                <td>
                    <span class="badge {{ $lic->estadoLic == 'Activa' ? 'badge-activo' : 'badge-inactivo' }}">
                        {{ $lic->estadoLic }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
        @endif

        <div style="text-align: center; margin-top: 20px; color: #666; font-size: 12px;">
            Información actualizada: {{ now()->format('d/m/Y H:i:s') }}
        </div>
    </div>

    <div id="alerta" class="alert-message"></div>

    <!-- Pasar el ID del artículo a JavaScript -->
    <script>
        const articuloId = {{ $articulo->idArticulo }};
    </script>
    
    <!-- JavaScript separado -->
    <script src="{{ asset('js/articulo-detalle.js') }}"></script>
</body>
</html>