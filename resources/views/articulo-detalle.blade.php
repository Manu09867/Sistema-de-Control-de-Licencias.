<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detalle del Artículo - {{ $articulo->serie }}</title>
    
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
          <a href="{{ url()->previous() }}" class="back-btn">
                ← Volver
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
                    <!-- Botón Eliminar -->
                    <button onclick="abrirModalEliminar()" class="btn-delete" style="background: #dc3545; color: white; border: none; padding: 10px 20px; border-radius: 50px; font-weight: 600; cursor: pointer;">
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
                <span>📦</span> 
                Detalle del Artículo
            </h1>
            <div class="header-badge">
                {{ $articulo->serie}}
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
        const articuloSerie = '{{ $articulo->serie }}';
        const articuloRP = '{{ $articulo->RP }}';
    </script>
    
    <!-- JavaScript separado -->
    <script src="{{ asset('js/articulo-detalle.js') }}"></script>
    
    <script>
        // ===== FUNCIONES PARA ELIMINAR ARTÍCULO =====
        
        function abrirModalEliminar() {
            const modal = document.getElementById('modal-eliminar');
            modal.style.display = 'flex';
            modal.style.alignItems = 'center';
            modal.style.justifyContent = 'center';
            document.getElementById('password_confirmacion').value = '';
            document.getElementById('password_confirmacion').focus();
        }

        function cerrarModalEliminar() {
            document.getElementById('modal-eliminar').style.display = 'none';
        }

        async function confirmarEliminar() {
    const password = document.getElementById('password_confirmacion').value;

    if (!password) {
        mostrarAlerta('error', '❌ Ingresa tu contraseña para confirmar');
        return;
    }

    // 🔥 Selecciona el botón correctamente (el último botón dentro del modal)
    const btn = document.querySelector('#modal-eliminar button:last-child');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '⏳ Eliminando...';

    try {
        const response = await fetch(`/api/articulos/${articuloSerie}`, {
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

        function mostrarAlerta(tipo, mensaje) {
            const alerta = document.getElementById('alerta');
            alerta.className = `alert-message alert-${tipo}`;
            alerta.textContent = mensaje;
            alerta.style.display = 'block';
            setTimeout(() => {
                alerta.style.display = 'none';
            }, 3000);
        }

        window.onclick = function (event) {
            const modal = document.getElementById('modal-eliminar');
            if (event.target === modal) {
                cerrarModalEliminar();
            }
        }
    </script>

    <!-- MODAL DE CONFIRMACIÓN PARA ELIMINAR ARTÍCULO - FUERA DEL CONTAINER -->
    <div id="modal-eliminar" class="modal" style="display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); align-items: center; justify-content: center;">
        <div class="modal-content" style="background: white; border-radius: 20px; padding: 30px; max-width: 450px; width: 90%; margin: 0 auto;">
            <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 3px solid #dc3545;">
                <h2 style="color: #0f3057; font-size: 24px; margin: 0;">⚠️ Eliminar Artículo</h2>
                <span class="close-modal" onclick="cerrarModalEliminar()" style="font-size: 28px; font-weight: bold; color: #666; cursor: pointer;">&times;</span>
            </div>

            <p style="margin-bottom: 20px; color: #666;">
                ¿Estás seguro de eliminar el artículo con RP <strong>{{ $articulo->RP }}</strong>?
            </p>

            <p style="margin-bottom: 15px; color: #dc3545; font-size: 14px;">
                ⚠️ Esta acción eliminará:
            </p>

            <ul style="margin-bottom: 20px; margin-left: 20px; color: #666; font-size: 13px;">
                <li>El artículo completo</li>
                <li>Sus relaciones con grupos</li>
                <li>Datos de red (router/switch)</li>
                <li>Asignaciones de licencias</li>
                <li>Se actualizará el total de la licitación</li>
            </ul>

            <div style="margin-bottom: 20px;">
                <label for="password_confirmacion" style="display: block; margin-bottom: 8px; font-weight: 600;">
                    Contraseña de administrador <span style="color: #dc3545;">*</span>
                </label>
                <input type="password" id="password_confirmacion" class="edit-input" style="width: 100%; padding: 12px 15px; border-radius: 8px; border: 1px solid #ccd6dd; font-size: 14px;" placeholder="Ingresa tu contraseña">
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button onclick="cerrarModalEliminar()" style="background: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 50px; font-weight: 600; cursor: pointer;">Cancelar</button>
                <button onclick="confirmarEliminar()" style="background: #dc3545; color: white; border: none; padding: 10px 20px; border-radius: 50px; font-weight: 600; cursor: pointer;">🗑️ Eliminar</button>
            </div>
        </div>
    </div>
</body>
</html>