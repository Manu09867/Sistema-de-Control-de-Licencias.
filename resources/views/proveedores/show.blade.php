<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detalle de Proveedor - {{ $proveedor->Nombre }}</title>

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
            max-width: 800px;
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

        .info-section {
            background: #f8fafc;
            border-radius: 12px;
            padding: 20px;
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

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }

            .info-row {
                flex-direction: column;
                align-items: flex-start;
            }

            .info-label {
                width: 100%;
                margin-bottom: 5px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <a href="{{ url()->previous() }}" class="back-btn">
                ← Volver
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
                <span>🏢</span>
                Detalle de Proveedor
            </h1>
            <div style="display: flex; align-items: center; gap: 15px;">
                <div class="header-badge">
                    {{ $proveedor->Nombre }}
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
                <span>📋</span> Información del Proveedor
            </div>

            <div class="info-section">
                <!-- Nombre -->
                <div class="info-row">
                    <span class="info-label">Nombre:</span>
                    <span class="info-value" id="nombre-text"><strong>{{ $proveedor->Nombre }}</strong></span>
                    <input type="text" id="nombre-input" class="edit-input" value="{{ $proveedor->Nombre }}"
                        style="display: none;" maxlength="45">
                </div>

                <!-- RFC -->
                <div class="info-row">
                    <span class="info-label">RFC:</span>
                    <span class="info-value" id="rfc-text">{{ $proveedor->RFC ?? 'No especificado' }}</span>
                    <input type="text" id="rfc-input" class="edit-input" value="{{ $proveedor->RFC }}"
                        style="display: none;" maxlength="20">
                </div>

                <!-- Teléfono -->
                <div class="info-row">
                    <span class="info-label">Teléfono:</span>
                    <span class="info-value" id="telefono-text">{{ $proveedor->Telefono ?? 'No especificado' }}</span>
                    <input type="text" id="telefono-input" class="edit-input" value="{{ $proveedor->Telefono }}"
                        style="display: none;" maxlength="20">
                </div>

                <!-- Dirección -->
                <div class="info-row">
                    <span class="info-label">Dirección:</span>
                    <span class="info-value" id="direccion-text">{{ $proveedor->Direccion ?? 'No especificado' }}</span>
                    <textarea id="direccion-input" class="edit-input" rows="3"
                        style="display: none; resize: vertical; width: 100%;"
                        maxlength="900">{{ $proveedor->Direccion ?? '' }}</textarea>
                </div>

                <!-- Correo -->
                <div class="info-row">
                    <span class="info-label">Correo:</span>
                    <span class="info-value" id="correo-text">{{ $proveedor->correo ?? 'No especificado' }}</span>
                    <input type="email" id="correo-input" class="edit-input" value="{{ $proveedor->correo }}"
                        style="display: none;" maxlength="50">
                </div>
            </div>
        </div>

        <div style="text-align: center; margin-top: 20px; color: #666; font-size: 12px;">
            Información actualizada: {{ now()->format('d/m/Y H:i:s') }}
        </div>
    </div>

    <!-- MODAL DE CONFIRMACIÓN PARA ELIMINAR -->
    <div id="modal-eliminar" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>⚠️ Eliminar Proveedor</h2>
                <span class="close-modal" onclick="cerrarModalEliminar()">&times;</span>
            </div>

            <p style="margin-bottom: 20px; color: #666;">
                ¿Estás seguro de eliminar el proveedor <strong>{{ $proveedor->Nombre }}</strong>?
            </p>

            <p style="margin-bottom: 15px; color: #dc3545; font-size: 14px;">
                ⚠️ No se podrá eliminar si tiene licitaciones, productos o software asociados.
            </p>

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
        let modoEdicion = false;

        function activarEdicion() {
            modoEdicion = true;

            document.getElementById('nombre-text').style.display = 'none';
            document.getElementById('nombre-input').style.display = 'block';

            document.getElementById('rfc-text').style.display = 'none';
            document.getElementById('rfc-input').style.display = 'block';

            document.getElementById('telefono-text').style.display = 'none';
            document.getElementById('telefono-input').style.display = 'block';

            document.getElementById('direccion-text').style.display = 'none';
            document.getElementById('direccion-input').style.display = 'block';

            document.getElementById('correo-text').style.display = 'none';
            document.getElementById('correo-input').style.display = 'block';

            document.getElementById('btnEditar').style.display = 'none';
            document.getElementById('btnGuardar').style.display = 'inline-flex';
            document.getElementById('btnCancelar').style.display = 'inline-flex';
        }

        function cancelarEdicion() {
            modoEdicion = false;

            document.getElementById('nombre-text').style.display = 'block';
            document.getElementById('nombre-input').style.display = 'none';

            document.getElementById('rfc-text').style.display = 'block';
            document.getElementById('rfc-input').style.display = 'none';

            document.getElementById('telefono-text').style.display = 'block';
            document.getElementById('telefono-input').style.display = 'none';

            document.getElementById('direccion-text').style.display = 'block';
            document.getElementById('direccion-input').style.display = 'none';

            document.getElementById('correo-text').style.display = 'block';
            document.getElementById('correo-input').style.display = 'none';

            document.getElementById('btnEditar').style.display = 'inline-flex';
            document.getElementById('btnGuardar').style.display = 'none';
            document.getElementById('btnCancelar').style.display = 'none';
        }

        async function guardarCambios() {
            const nombre = document.getElementById('nombre-input').value.trim();
            const rfc = document.getElementById('rfc-input').value.trim();
            const telefono = document.getElementById('telefono-input').value.trim();
            const direccion = document.getElementById('direccion-input').value.trim();
            const correo = document.getElementById('correo-input').value.trim();

            if (!nombre) {
                mostrarAlerta('error', 'El nombre del proveedor es requerido');
                return;
            }

            const btn = document.getElementById('btnGuardar');
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span>⏳</span> Guardando...';

            try {
                const response = await fetch(`/proveedores/{{ $proveedor->idProveedor }}/actualizar`, {
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
                    mostrarAlerta('success', '✅ Proveedor actualizado correctamente');
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
                const response = await fetch(`/proveedores/{{ $proveedor->idProveedor }}/eliminar`, {
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
                        window.location.href = '/proveedores';
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