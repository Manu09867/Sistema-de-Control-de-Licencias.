<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detalle de Licitación - {{ $licitacion->folio }}</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background: #e6ecf2;
            padding: 30px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            background: linear-gradient(135deg, #4a6fa5, #6b8cae);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 32px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header-badge {
            background: #ffd700;
            color: #4a6fa5;
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: bold;
            font-size: 16px;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .btn-edit {
            background: #ffd700;
            color: #0f3057;
            border: none;
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-edit:hover {
            background: #e6c200;
            transform: translateY(-2px);
        }

        .btn-save {
            background: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-save:hover {
            background: #218838;
            transform: translateY(-2px);
        }

        .btn-cancel {
            background: #6c757d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-cancel:hover {
            background: #5a6268;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 12px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-delete:hover {
            background: #c82333;
        }

        .readonly-badge {
            background: #e6ecf2;
            color: #0f3057;
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: white;
            color: #4a6fa5;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 50px;
            font-weight: 600;
            margin-bottom: 20px;
            border: 2px solid #4a6fa5;
            transition: 0.3s;
        }

        .back-btn:hover {
            background: #4a6fa5;
            color: white;
            transform: translateX(-5px);
        }

        .info-card-large {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            border: 1px solid #e6ecf2;
            margin-bottom: 25px;
        }

        .card-title {
            color: #4a6fa5;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid #4a6fa5;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-grid-3 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            margin-bottom: 20px;
        }

        .info-section {
            background: #f8fafc;
            border-radius: 12px;
            padding: 20px;
        }

        .section-subtitle {
            color: #4a6fa5;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-row {
            display: flex;
            margin-bottom: 12px;
            padding: 6px 0;
            border-bottom: 1px dashed #e6ecf2;
            align-items: center;
        }

        .info-label {
            width: 120px;
            font-weight: 600;
            color: #666;
            font-size: 14px;
        }

        .info-value {
            flex: 1;
            color: #0f3057;
            font-weight: 500;
            font-size: 14px;
        }

        .edit-input {
            width: 100%;
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid #ccd6dd;
            background: white;
            font-size: 14px;
            transition: all 0.3s;
        }

        .edit-input:focus {
            outline: none;
            border-color: #4a6fa5;
            box-shadow: 0 0 0 3px rgba(74, 111, 165, 0.1);
        }

        .edit-select {
            width: 100%;
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid #ccd6dd;
            background: white;
            font-size: 14px;
            cursor: pointer;
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-activo {
            background: #d4edda;
            color: #155724;
        }

        .badge-vencida {
            background: #f8d7da;
            color: #721c24;
        }

        .badge-pronto {
            background: #fff3cd;
            color: #856404;
        }

        .badge-inactivo {
            background: #e9ecef;
            color: #495057;
        }

        .articulos-section {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            margin-top: 25px;
            border: 1px solid #e6ecf2;
        }

        .table-container {
            max-height: 400px;
            overflow-y: auto;
            overflow-x: auto;
            margin-top: 15px;
            border-radius: 10px;
        }

        .articulos-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px;
        }

        .articulos-table th {
            background: #4a6fa5;
            color: white;
            padding: 12px;
            text-align: left;
            font-size: 13px;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .articulos-table td {
            padding: 12px;
            border-bottom: 1px solid #e6ecf2;
            font-size: 13px;
        }

        .articulos-table tr:hover td {
            background: #f8fafc;
        }

        .info-btn-small {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #4a6fa5;
            color: white;
            border: none;
            font-size: 12px;
            font-weight: bold;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .info-btn-small:hover {
            background: #ffd700;
            color: #4a6fa5;
            transform: scale(1.1);
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
        }

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
            max-width: 450px;
            width: 90%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid #dc3545;
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
            color: #dc3545;
        }

        .alert-message {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 25px;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            z-index: 1100;
            display: none;
            animation: slideIn 0.3s;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .alert-success {
            background: #28a745;
            border-left: 5px solid #1e7e34;
        }

        .alert-error {
            background: #dc3545;
            border-left: 5px solid #bd2130;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .table-container::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        .table-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .table-container::-webkit-scrollbar-thumb {
            background: #4a6fa5;
            border-radius: 10px;
        }

        .table-container::-webkit-scrollbar-thumb:hover {
            background: #6b8cae;
        }

        @media (max-width: 768px) {
            .info-grid-3 {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .header {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }

            .table-container {
                max-height: 300px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
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
                    <span class="readonly-badge">
                        <span>👁️</span> Solo vista
                    </span>
                @endif
            </div>
        </div>

        <div class="header">
            <h1>
                <span>📄</span>
                Detalle de Licitación
            </h1>
            <div style="display: flex; align-items: center; gap: 15px;">
                <div class="header-badge">
                    {{ $licitacion->folio }}
                </div>
                @if(auth()->check() && auth()->user()->role === 'admin')
                    <button onclick="abrirModalEliminar()" class="btn-delete">
                        🗑️ Eliminar
                    </button>
                @endif
            </div>
        </div>

        <div class="info-card-large">
            <div class="card-title">
                <span>📋</span> Información Completa de la Licitación
            </div>

            <div class="info-grid-3">
                <!-- Columna 1: Datos Generales (EDITABLE) -->
                <div class="info-section">
                    <div class="section-subtitle">
                        <span>📋</span> Datos Generales
                    </div>

                    <!-- Folio - Editable -->
                    <div class="info-row">
                        <span class="info-label">Folio:</span>
                        <span class="info-value" id="folio-text"><strong>{{ $licitacion->folio }}</strong></span>
                        <input type="text" id="folio-input" class="edit-input" value="{{ $licitacion->folio }}"
                            style="display: none;" maxlength="50">
                    </div>

                    <!-- Proveedor - Select con datos de BD -->
                    <div class="info-row">
                        <span class="info-label">Proveedor:</span>
                        <span class="info-value"
                            id="proveedor-text">{{ $licitacion->proveedor_nombre ?? 'Sin proveedor' }}</span>
                        <select id="proveedor-input" class="edit-select" style="display: none;">
                            <option value="">-- Selecciona un proveedor --</option>
                            @foreach($proveedores as $proveedor)
                                <option value="{{ $proveedor->idProveedor }}" {{ $licitacion->idProveedor == $proveedor->idProveedor ? 'selected' : '' }}>
                                    {{ $proveedor->Nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Estado - Select con opciones -->
                    <div class="info-row">
                        <span class="info-label">Estado:</span>
                        <span class="info-value" id="estado-text">
                            @php
                                $estadoClass = match ($licitacion->estado) {
                                    'Abierta' => 'badge-activo',
                                    'En proceso' => 'badge-pronto',
                                    'Adjudicada' => 'badge-activo',
                                    'Cerrada' => 'badge-inactivo',
                                    'Cancelada' => 'badge-vencida',
                                    default => 'badge-inactivo'
                                };
                            @endphp
                            <span class="badge {{ $estadoClass }}">
                                {{ $licitacion->estado ?? 'N/A' }}
                            </span>
                        </span>
                        <select id="estado-input" class="edit-select" style="display: none;">
                            <option value="Abierta" {{ $licitacion->estado == 'Abierta' ? 'selected' : '' }}>📂 Abierta
                            </option>
                            <option value="En proceso" {{ $licitacion->estado == 'En proceso' ? 'selected' : '' }}>⚙️ En
                                proceso</option>
                            <option value="Adjudicada" {{ $licitacion->estado == 'Adjudicada' ? 'selected' : '' }}>✅
                                Adjudicada</option>
                            <option value="Cerrada" {{ $licitacion->estado == 'Cerrada' ? 'selected' : '' }}>🔒 Cerrada
                            </option>
                            <option value="Cancelada" {{ $licitacion->estado == 'Cancelada' ? 'selected' : '' }}>❌
                                Cancelada</option>
                        </select>
                    </div>

                    <!-- Recurso - Editable -->
                    <div class="info-row">
                        <span class="info-label">Recurso:</span>
                        <span class="info-value"
                            id="recurso-text">{{ $licitacion->recurso ?? 'No especificado' }}</span>
                        <input type="text" id="recurso-input" class="edit-input" value="{{ $licitacion->recurso }}"
                            style="display: none;" maxlength="45" placeholder="Ej: Federal, Estatal, Propio...">
                    </div>
                </div>

                <!-- Columna 2: Fechas y Total (EDITABLES) -->
                <div class="info-section">
                    <div class="section-subtitle">
                        <span>📅</span> Fechas y Totales
                    </div>

                    <div class="info-row">
                        <span class="info-label">Fecha Inicio:</span>
                        <span class="info-value"
                            id="fecha-inicio-text">{{ $licitacion->fecha_inicio ? date('d/m/Y', strtotime($licitacion->fecha_inicio)) : '-' }}</span>
                        <input type="date" id="fecha-inicio-input" class="edit-input"
                            value="{{ $licitacion->fecha_inicio }}" style="display: none;">
                    </div>

                    <div class="info-row">
                        <span class="info-label">Fecha Fin:</span>
                        <span class="info-value"
                            id="fecha-fin-text">{{ $licitacion->fecha_fin ? date('d/m/Y', strtotime($licitacion->fecha_fin)) : '-' }}</span>
                        <input type="date" id="fecha-fin-input" class="edit-input" value="{{ $licitacion->fecha_fin }}"
                            style="display: none;">
                    </div>

                    <div class="info-row">
                        <span class="info-label">Total:</span>
                        <span class="info-value" style="color: #28a745; font-weight: bold;">
                            ${{ number_format($licitacion->Total ?? 0, 2) }}
                        </span>
                    </div>
                </div>

                <!-- Columna 3: Descripción (EDITABLE) -->
                <div class="info-section">
                    <div class="section-subtitle">
                        <span>📝</span> Descripción
                    </div>

                    <div class="info-row" style="flex-direction: column; align-items: flex-start;">
                        <span class="info-value" id="descripcion-text" style="width: 100%;">
                            {{ $licitacion->descripcion ?? 'Sin descripción' }}
                        </span>
                        <textarea id="descripcion-input" class="edit-input" rows="4"
                            style="display: none; resize: vertical; width: 100%;"
                            placeholder="Descripción de la licitación"
                            maxlength="255">{{ $licitacion->descripcion ?? '' }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        @if(!empty($detalles))
            <div class="articulos-section">
                <div class="card-title" style="font-size: 20px; margin-bottom: 15px;">
                    <span>📦</span> Items de la Licitación ({{ count($detalles) }})
                </div>

                <div class="table-container">
                    <table class="articulos-table">
                        <thead>
                            <tr>
                                <th>Tipo</th>
                                <th>Item</th>
                                <th>Cantidad</th>
                                <th>Precio Unitario</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($detalles as $detalle)
                                <tr>
                                    <td>
                                        @if($detalle->TipoItem == 'SOFTWARE')
                                            <span
                                                style="background: #cce5ff; color: #004085; padding: 4px 8px; border-radius: 12px; font-size: 11px;">
                                                🔑 Software
                                            </span>
                                        @else
                                            <span
                                                style="background: #e6ecf2; color: #4a6fa5; padding: 4px 8px; border-radius: 12px; font-size: 11px;">
                                                📦 Hardware
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        <strong>{{ $detalle->item_nombre ?? 'N/A' }}</strong>
                                    </td>

                                    <td>{{ $detalle->Cantidad }}</td>

                                    <td>
                                        ${{ number_format($detalle->PrecioU ?? 0, 2) }}
                                    </td>

                                    <td style="color: #28a745; font-weight: bold;">
                                        ${{ number_format($detalle->Subtotal ?? 0, 2) }}
                                    </td>

                                    <td>
                                        @if(!empty($detalle->item_referencia))
                                            <button
                                                onclick="window.open('{{ $detalle->TipoItem == 'SOFTWARE' ? '/licencia/' : '/articulo/' }}{{ $detalle->item_referencia }}', '_blank')"
                                                class="info-btn-small"
                                                title="Ver {{ $detalle->TipoItem == 'SOFTWARE' ? 'licencia' : 'artículo' }}">
                                                🔍
                                            </button>
                                        @else
                                            —
                                        @endif
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
                    <span>📦</span> Items de la Licitación
                </div>
                <div class="no-data">
                    📭 No hay items asociados a esta licitación
                </div>
            </div>
        @endif

        <div style="text-align: center; margin-top: 20px; color: #666; font-size: 12px;">
            Información actualizada: {{ now()->format('d/m/Y H:i:s') }}
        </div>
    </div>

    <!-- MODAL DE CONFIRMACIÓN PARA ELIMINAR -->
    <div id="modal-eliminar" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>⚠️ Eliminar Licitación</h2>
                <span class="close-modal" onclick="cerrarModalEliminar()">&times;</span>
            </div>

            <p style="margin-bottom: 20px; color: #666;">
                ¿Estás seguro de eliminar la licitación <strong>{{ $licitacion->folio }}</strong>?
            </p>

            <p style="margin-bottom: 15px; color: #dc3545; font-size: 14px;">
                ⚠️ Esta acción eliminará TODOS los elementos relacionados:
            </p>

            <ul style="margin-bottom: 20px; margin-left: 20px; color: #666; font-size: 13px;">
                <li>La licitación completa</li>
                <li>Todos los detalles de la licitación</li>
                <li>Licencias asociadas y sus asignaciones</li>
                <li>Artículos asociados y sus relaciones (grupos, red, etc.)</li>
            </ul>

            <div class="form-group" style="margin-bottom: 20px;">
                <label for="password_confirmacion" style="display: block; margin-bottom: 8px; font-weight: 600;">
                    Contraseña de administrador <span style="color: #dc3545;">*</span>
                </label>
                <input type="password" id="password_confirmacion" class="edit-input"
                    placeholder="Ingresa tu contraseña">
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button onclick="cerrarModalEliminar()" class="btn-cancel">Cancelar</button>
                <button onclick="confirmarEliminar()" class="btn-delete">🗑️ Eliminar</button>
            </div>
        </div>
    </div>

    <div id="alerta" class="alert-message"></div>

    <script>
        // Variables para el modo edición
        let modoEdicion = false;

        function activarEdicion() {
            modoEdicion = true;

            // Ocultar textos y mostrar inputs
            document.getElementById('folio-text').style.display = 'none';
            document.getElementById('folio-input').style.display = 'block';

            document.getElementById('proveedor-text').style.display = 'none';
            document.getElementById('proveedor-input').style.display = 'block';

            document.getElementById('estado-text').style.display = 'none';
            document.getElementById('estado-input').style.display = 'block';

            document.getElementById('recurso-text').style.display = 'none';
            document.getElementById('recurso-input').style.display = 'block';

            document.getElementById('fecha-inicio-text').style.display = 'none';
            document.getElementById('fecha-inicio-input').style.display = 'block';

            document.getElementById('fecha-fin-text').style.display = 'none';
            document.getElementById('fecha-fin-input').style.display = 'block';

            document.getElementById('descripcion-text').style.display = 'none';
            document.getElementById('descripcion-input').style.display = 'block';

            // Cambiar botones
            document.getElementById('btnEditar').style.display = 'none';
            document.getElementById('btnGuardar').style.display = 'inline-flex';
            document.getElementById('btnCancelar').style.display = 'inline-flex';
        }

        function cancelarEdicion() {
            modoEdicion = false;

            // Restaurar textos y ocultar inputs
            document.getElementById('folio-text').style.display = 'block';
            document.getElementById('folio-input').style.display = 'none';

            document.getElementById('proveedor-text').style.display = 'block';
            document.getElementById('proveedor-input').style.display = 'none';

            document.getElementById('estado-text').style.display = 'block';
            document.getElementById('estado-input').style.display = 'none';

            document.getElementById('recurso-text').style.display = 'block';
            document.getElementById('recurso-input').style.display = 'none';

            document.getElementById('fecha-inicio-text').style.display = 'block';
            document.getElementById('fecha-inicio-input').style.display = 'none';

            document.getElementById('fecha-fin-text').style.display = 'block';
            document.getElementById('fecha-fin-input').style.display = 'none';

            document.getElementById('descripcion-text').style.display = 'block';
            document.getElementById('descripcion-input').style.display = 'none';

            // Restaurar botones
            document.getElementById('btnEditar').style.display = 'inline-flex';
            document.getElementById('btnGuardar').style.display = 'none';
            document.getElementById('btnCancelar').style.display = 'none';
        }

        async function guardarCambios() {
            const folio = document.getElementById('folio-input').value.trim();
            const idProveedor = document.getElementById('proveedor-input').value;
            const estado = document.getElementById('estado-input').value;
            const recurso = document.getElementById('recurso-input').value.trim();
            const fechaInicio = document.getElementById('fecha-inicio-input').value;
            const fechaFin = document.getElementById('fecha-fin-input').value;
            const descripcion = document.getElementById('descripcion-input').value.trim();

            if (!folio) {
                mostrarAlerta('error', 'El folio de la licitación es requerido');
                return;
            }

            const btn = document.getElementById('btnGuardar');
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span>⏳</span> Guardando...';

            try {
                const response = await fetch(`/licitaciones/{{ $licitacion->idLicitacion }}/actualizar`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        folio: folio,
                        idProveedor: idProveedor || null,
                        estado: estado,
                        recurso: recurso || null,
                        fecha_inicio: fechaInicio || null,
                        fecha_fin: fechaFin || null,
                        descripcion: descripcion || null
                    })
                });

                const data = await response.json();

                if (data.success) {
                    mostrarAlerta('success', '✅ Licitación actualizada correctamente');
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
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

        function abrirModalEliminar() {
            document.getElementById('modal-eliminar').style.display = 'flex';
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

            const btn = document.querySelector('#modal-eliminar .btn-delete');
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '⏳ Eliminando...';

            try {
                const response = await fetch(`/licitaciones/{{ $licitacion->idLicitacion }}/eliminar`, {
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
                        window.location.href = '/licitaciones';
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
</body>

</html>