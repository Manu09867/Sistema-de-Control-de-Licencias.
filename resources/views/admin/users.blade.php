<x-app-layout>
    <style>
        .users-header {
            background: linear-gradient(135deg, #0f3057, #00587a);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin: 20px 20px 0 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .users-container {
            margin: 20px auto;
            max-width: 95%;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(69, 58, 58, 0.1);
            border: 1px solid #e6ecf2;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: calc(100vh - 250px);
        }

        .users-table {
            width: 100%;
            border-collapse: collapse;
        }

        .users-table th {
            background: #0f3057;
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }

        .users-table td {
            padding: 15px;
            border-bottom: 1px solid #e6ecf2;
            color: #333;
        }

        .users-table tr:hover td {
            background: #f8fafc;
        }

        .badge-role {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-admin {
            background: #0f3057;
            color: white;
        }

        .badge-user {
            background: #e6ecf2;
            color: #0f3057;
        }

        .btn-action {
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 13px;
            margin: 0 3px;
            transition: 0.3s;
            border: none;
            cursor: pointer;
        }

        .btn-edit {
            background: #ffd700;
            color: #0f3057;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
        }

        .btn-create {
            background: #28a745;
            color: white;
            padding: 10px 20px;
            border-radius: 50px;
            text-decoration: none;
            display: inline-block;
            margin: 20px;
            border: none;
            cursor: pointer;
            font-weight: 600;
        }

        /* MODALES */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            border-radius: 20px;
            padding: 30px;
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid #00587a;
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
            color: #0f3057;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: 600;
            color: #0f3057;
            display: block;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px 15px;
            border-radius: 8px;
            border: 1px solid #ccd6dd;
            background: #f8fafc;
            font-size: 14px;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #00587a;
            box-shadow: 0 0 0 3px rgba(0,88,122,0.1);
        }

        .modal-buttons {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .btn-save {
            background: #28a745;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-cancel {
            background: #6c757d;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
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
        }

        .alert-success {
            background: #28a745;
        }

        .alert-error {
            background: #dc3545;
        }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
    </style>

    <div>
        <div class="users-header">
            <h1>👥 Gestión de Usuarios</h1>
            <p style="opacity: 0.9; margin-top: 5px;">Administra las cuentas del sistema</p>
        </div>

        <div class="users-container">
            <div style="display: flex; justify-content: flex-end;">
                <button onclick="abrirModalCrear()" class="btn-create">
                    ➕ Crear Nuevo Usuario
                </button>
            </div>

            <table class="users-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Fecha Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tabla-usuarios">
                    @foreach($users as $user)
                    <tr id="user-{{ $user->id }}">
                        <td><strong>#{{ $user->id }}</strong></td>
                        <td class="user-name">{{ $user->name }}</td>
                        <td class="user-email">{{ $user->email }}</td>
                        <td>
                            <span class="badge-role {{ $user->role === 'admin' ? 'badge-admin' : 'badge-user' }}" data-role="{{ $user->role }}">
                                {{ $user->role === 'admin' ? '🔑 ADMIN' : '👤 USUARIO' }}
                            </span>
                        </td>
                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                        <td>
                            @if($user->id === auth()->id())
                                <!-- Admin viendo su propio usuario -->
                                <span style="color: #999; font-size: 13px; display: flex; align-items: center; gap: 5px;">
                                    <span style="font-size: 16px;">👑</span> Tu usuario
                                </span>
                            @else
                                <!-- Puede editar/eliminar a otros -->
                                <button onclick="abrirModalEditar({{ $user->id }})" class="btn-action btn-edit">
                                    ✏️ Editar
                                </button>
                                <button onclick="eliminarUsuario({{ $user->id }})" class="btn-action btn-delete">
                                    🗑️ Eliminar
                                </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL CREAR USUARIO -->
    <div id="modal-crear" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>➕ Crear Nuevo Usuario</h2>
                <span class="close-modal" onclick="cerrarModal('crear')">&times;</span>
            </div>
            
            <form id="form-crear" onsubmit="guardarUsuario(event, 'crear')">
                @csrf
                
                <div class="form-group">
                    <label for="crear-nombre">Nombre completo</label>
                    <input type="text" id="crear-nombre" name="name" required>
                </div>

                <div class="form-group">
                    <label for="crear-email">Correo electrónico</label>
                    <input type="email" id="crear-email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="crear-password">Contraseña</label>
                    <input type="password" id="crear-password" name="password" required minlength="8">
                </div>

                <div class="form-group">
                    <label for="crear-role">Rol</label>
                    <select id="crear-role" name="role" required>
                        <option value="user">👤 Usuario</option>
                        <option value="admin">🔑 Administrador</option>
                    </select>
                </div>

                <div class="modal-buttons">
                    <button type="button" onclick="cerrarModal('crear')" class="btn-cancel">Cancelar</button>
                    <button type="submit" class="btn-save">💾 Crear Usuario</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDITAR USUARIO -->
    <div id="modal-editar" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>✏️ Editar Usuario</h2>
                <span class="close-modal" onclick="cerrarModal('editar')">&times;</span>
            </div>
            
            <form id="form-editar" onsubmit="guardarUsuario(event, 'editar')">
                @csrf
                @method('PUT')
                <input type="hidden" id="editar-id" name="user_id">
                
                <div class="form-group">
                    <label for="editar-nombre">Nombre completo</label>
                    <input type="text" id="editar-nombre" name="name" required>
                </div>

                <div class="form-group">
                    <label for="editar-email">Correo electrónico</label>
                    <input type="email" id="editar-email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="editar-password">Nueva contraseña <span style="font-weight: normal; color: #666;">(dejar vacío si no quieres cambiarla)</span></label>
                    <input type="password" id="editar-password" name="password" minlength="8">
                </div>

                <div class="form-group">
                    <label for="editar-role">Rol</label>
                    <select id="editar-role" name="role" required>
                        <option value="user">👤 Usuario</option>
                        <option value="admin">🔑 Administrador</option>
                    </select>
                </div>

                <div class="modal-buttons">
                    <button type="button" onclick="cerrarModal('editar')" class="btn-cancel">Cancelar</button>
                    <button type="submit" class="btn-save">💾 Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ALERTA FLOTANTE -->
    <div id="alerta" class="alert-message"></div>

    <script>
    // Abrir modales
    function abrirModalCrear() {
        document.getElementById('modal-crear').style.display = 'flex';
        document.getElementById('form-crear').reset();
    }

    function abrirModalEditar(userId) {
        const fila = document.getElementById(`user-${userId}`);
        const nombre = fila.querySelector('.user-name').textContent;
        const email = fila.querySelector('.user-email').textContent;
        const rolSpan = fila.querySelector('.badge-role');
        const rol = rolSpan.dataset.role;

        document.getElementById('editar-id').value = userId;
        document.getElementById('editar-nombre').value = nombre;
        document.getElementById('editar-email').value = email;
        document.getElementById('editar-role').value = rol;
        document.getElementById('editar-password').value = '';

        document.getElementById('modal-editar').style.display = 'flex';
    }

    function cerrarModal(modal) {
        document.getElementById(`modal-${modal}`).style.display = 'none';
    }

    // Cerrar modales haciendo clic fuera
    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = 'none';
        }
    }

    // Guardar usuario (crear o editar)
    async function guardarUsuario(event, tipo) {
        event.preventDefault();
        
        const form = document.getElementById(`form-${tipo}`);
        const formData = new FormData(form);
        
        // 🔥 CORREGIDO: URLs correctas
        const url = tipo === 'crear' 
            ? '/admin/users' 
            : `/admin/users/${document.getElementById('editar-id').value}`;
        
        // Mostrar indicador de carga
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Guardando...';
        submitBtn.disabled = true;
        
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                mostrarAlerta('success', data.message);
                cerrarModal(tipo);
                
                if (tipo === 'crear') {
                    // Recargar después de crear
                    setTimeout(() => location.reload(), 1000);
                } else {
                    // Actualizar fila sin recargar
                    actualizarFilaUsuario(data.user);
                }
            } else {
                mostrarAlerta('error', data.message || 'Error al procesar la solicitud');
            }
        } catch (error) {
            console.error('Error:', error);
            mostrarAlerta('error', 'Error de conexión al servidor');
        } finally {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        }
    }

    // Eliminar usuario
    async function eliminarUsuario(userId) {
        if (!confirm('¿Estás seguro de eliminar este usuario?')) return;
        
        try {
            const response = await fetch(`/admin/users/${userId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                mostrarAlerta('success', data.message);
                document.getElementById(`user-${userId}`).remove();
            } else {
                mostrarAlerta('error', data.message);
            }
        } catch (error) {
            console.error('Error:', error);
            mostrarAlerta('error', 'Error al eliminar usuario');
        }
    }

    // Actualizar fila después de editar
    function actualizarFilaUsuario(user) {
        const fila = document.getElementById(`user-${user.id}`);
        if (fila) {
            fila.querySelector('.user-name').textContent = user.name;
            fila.querySelector('.user-email').textContent = user.email;
            
            const badge = fila.querySelector('.badge-role');
            badge.className = `badge-role ${user.role === 'admin' ? 'badge-admin' : 'badge-user'}`;
            badge.dataset.role = user.role;
            badge.textContent = user.role === 'admin' ? '🔑 ADMIN' : '👤 USUARIO';
        }
    }

    // Mostrar alerta flotante
    function mostrarAlerta(tipo, mensaje) {
        const alerta = document.getElementById('alerta');
        alerta.className = `alert-message alert-${tipo}`;
        alerta.textContent = mensaje;
        alerta.style.display = 'block';
        
        setTimeout(() => {
            alerta.style.display = 'none';
        }, 3000);
    }
    </script>
</x-app-layout>