<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detalle de Licencia - {{ $licencia->Clave }}</title>
    
    <!-- CSS desde public/css/ -->
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
                <button id="btnEditar" class="btn-edit" onclick="activarEdicion()">
                    <span>✏️</span> Editar
                </button>
                <button id="btnGuardar" class="btn-save" style="display: none;" onclick="guardarCambios()">
                    <span>💾</span> Guardar Cambios
                </button>
                <button id="btnCancelar" class="btn-cancel" style="display: none;" onclick="cancelarEdicion()">
                    <span>❌</span> Cancelar
                </button>
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
                <!-- Columna 1: Información Básica (EDITABLE) -->
                <div class="info-section">
                    <div class="section-subtitle">
                        <span>🔑</span> Datos de la Licencia
                    </div>
                    
                    <!-- CLAVE - Editable -->
                    <div class="info-row">
                        <span class="info-label">Clave:</span>
                        <span class="info-value" id="clave-text"><code>{{ $licencia->Clave ?? 'N/A' }}</code></span>
                        <input type="text" id="clave-input" class="edit-input" value="{{ $licencia->Clave }}" style="display: none;">
                    </div>
                    
                    <!-- ESTADO - Select con opciones -->
                    <div class="info-row">
                        <span class="info-label">Estado:</span>
                        <span class="info-value" id="estado-text">
                            @php
                                $estadoClass = match($licencia->estadoLic) {
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
                            <option value="Activa" {{ $licencia->estadoLic == 'Activa' ? 'selected' : '' }}>✅ Activa</option>
                            <option value="Por vencer" {{ $licencia->estadoLic == 'Por vencer' ? 'selected' : '' }}>⚠️ Por vencer</option>
                            <option value="Vencida" {{ $licencia->estadoLic == 'Vencida' ? 'selected' : '' }}>📅 Vencida</option>
                            <option value="Inactiva" {{ $licencia->estadoLic == 'Inactiva' ? 'selected' : '' }}>⛔ Inactiva</option>
                        </select>
                    </div>
                    
                    <!-- SOFTWARE - Select con datos de BD -->
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
                    
                    <!-- CAPACIDAD - Input numérico -->
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
                               value="{{ $licencia->CapacidadLicencia }}" 
                               placeholder="Dejar vacío para ilimitado"
                               style="display: none;" min="1">
                    </div>

                    <!-- DESCRIPCIÓN - Textarea -->
                    <div class="info-row">
                        <span class="info-label">Descripción:</span>
                        <span class="info-value" id="descripcion-text">
                            {{ $licencia->DescripcionLicencia ?? 'Sin descripción' }}
                        </span>
                        <textarea id="descripcion-input" class="edit-input" 
                                  rows="3" style="display: none; resize: vertical; min-height: 60px;"
                                  placeholder="Descripción de la licencia">{{ $licencia->DescripcionLicencia ?? '' }}</textarea>
                    </div>
                </div>

                <!-- Columna 2: Fechas (EDITABLES) -->
                <div class="info-section">
                    <div class="section-subtitle">
                        <span>📅</span> Fechas Importantes
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">Fecha Activación:</span>
                        <span class="info-value" id="fecha-activacion-text">{{ $licencia->Fechacompra ? date('d/m/Y', strtotime($licencia->Fechacompra)) : 'N/A' }}</span>
                        <input type="date" id="fecha-activacion-input" class="edit-input" 
                               value="{{ $licencia->Fechacompra }}" 
                               style="display: none;">
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">Fecha Vencimiento:</span>
                        <span class="info-value" id="fecha-vencimiento-text">
                            {{ $licencia->Fechavencimiento ? date('d/m/Y', strtotime($licencia->Fechavencimiento)) : 'N/A' }}
                            @php
                                if($licencia->Fechavencimiento) {
                                    $dias = (strtotime($licencia->Fechavencimiento) - time()) / 86400;
                                    if($dias < 0) {
                                        echo '<span class="dias-restantes" style="background: #f8d7da; color: #721c24;">Vencida</span>';
                                    } elseif($dias < 30) {
                                        echo '<span class="dias-restantes" style="background: #fff3cd; color: #856404;">Vence en ' . round($dias) . ' días</span>';
                                    } else {
                                        echo '<span class="dias-restantes" style="background: #d4edda; color: #155724;">Vigente</span>';
                                    }
                                }
                            @endphp
                        </span>
                        <input type="date" id="fecha-vencimiento-input" class="edit-input" 
                               value="{{ $licencia->Fechavencimiento }}" 
                               style="display: none;">
                    </div>

                    <div class="info-row">
                        <span class="info-label">Total Artículos:</span>
                        <span class="info-value">
                            <span style="font-weight: 600; color: #4a6fa5;">{{ $licencia->total_articulos ?? 0 }} asignados</span>
                            @if(isset($licencia->CapacidadLicencia) && $licencia->CapacidadLicencia && ($licencia->total_articulos ?? 0) >= $licencia->CapacidadLicencia)
                                <span class="dias-restantes" style="background: #f8d7da; color: #721c24; margin-left: 10px;">
                                    ⚠️ Capacidad agotada
                                </span>
                            @endif
                        </span>
                    </div>
                </div>

                <!-- Columna 3: Licitación (NO EDITABLE) -->
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
        @if(count($articulos) > 0)
        <div class="articulos-section">
            <div class="card-title" style="font-size: 20px; margin-bottom: 15px;">
                <span>📦</span> Artículos con esta Licencia ({{ count($articulos) }})
                @if(isset($licencia->CapacidadLicencia) && $licencia->CapacidadLicencia)
                    <span style="font-size: 12px; font-weight: normal; background: #e6ecf2; padding: 4px 12px; border-radius: 20px; margin-left: 10px;">
                        Capacidad: {{ count($articulos) }}/{{ $licencia->CapacidadLicencia }}
                    </span>
                @endif
            </div>

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
                            <th></th>
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
                                    $estadoClass = match($art->estado) {
                                        'Activo' => 'badge-activo',
                                        'Mantenimiento' => 'badge-pronto',
                                        default => 'badge-inactivo'
                                    };
                                @endphp
                                <span class="badge {{ $estadoClass }}">
                                    {{ $art->estado ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                <button 
                                    onclick="window.open('/articulo/{{ $art->RP }}', '_blank')"
                                    class="info-btn-small"
                                    title="Ver artículo"
                                >
                                    !
                                </button>
                            </td>
                          </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div class="articulos-section">
            <div class="card-title" style="font-size: 20px; margin-bottom: 15px;">
                <span>📦</span> Artículos con esta Licencia
            </div>
            <div class="no-data">
                📭 No hay artículos asignados a esta licencia
            </div>
        </div>
        @endif

        <div style="text-align: center; margin-top: 20px; color: #666; font-size: 12px;">
            Información actualizada: {{ now()->format('d/m/Y H:i:s') }}
        </div>
    </div>

    <div id="alerta" class="alert-message"></div>

    <!-- Pasar el ID de la licencia a JavaScript -->
    <script>
        const licenciaId = {{ $licencia->idLicencia }};
    </script>
    
    <!-- JavaScript desde public/js/ -->
    <script src="{{ asset('js/licencia-detalle.js') }}"></script>
</body>
</html>