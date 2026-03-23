<x-app-layout>
    <style>
        /* Eliminar scroll extra del layout principal */
        html, body {
            overflow: hidden;
            height: 100%;
        }
        
        /* Contenedor principal con scroll controlado */
        .profile-wrapper {
            height: calc(100vh - 64px);
            overflow-y: auto;
            padding: 20px 20px 20px 50px;
            background: #f5f7fa;
        }

        /* Scrollbar personalizada */
        .profile-wrapper::-webkit-scrollbar {
            width: 8px;
        }
        
        .profile-wrapper::-webkit-scrollbar-track {
            background: #e6ecf2;
        }
        
        .profile-wrapper::-webkit-scrollbar-thumb {
            background: #0f3057;
            border-radius: 10px;
        }

        /* Contenedor de contenido */
        .profile-container {
            max-width: 650px;
            margin: 0;
        }

        /* Header decorativo */
        .profile-header {
            background: linear-gradient(135deg, #0f3057, #00587a);
            color: white;
            padding: 20px 25px;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .profile-header h1 {
            font-size: 24px;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .profile-header p {
            font-size: 14px;
            opacity: 0.9;
        }

        /* Cards - MISMO ESTILO PARA AMBOS */
        .profile-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            margin-bottom: 20px;
            overflow: hidden;
            border: 1px solid #e0e8f0;
            transition: transform 0.2s;
        }

        .profile-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }

        .card-title {
            background: linear-gradient(135deg, #0f3057, #00587a);
            color: white;
            padding: 12px 20px;
            font-weight: 600;
            font-size: 16px;
            letter-spacing: 0.3px;
        }

        .card-body {
            padding: 20px;
        }

        /* Labels */
        label {
            font-weight: 500;
            color: #0f3057;
            margin-bottom: 5px;
            display: block;
            font-size: 14px;
        }

        /* Inputs */
        input {
            width: 100%;
            padding: 10px 15px;
            border-radius: 8px;
            border: 1px solid #ccd6dd;
            background: #f8fafc;
            font-size: 14px;
            transition: all 0.3s;
        }

        input:focus {
            outline: none;
            border-color: #00587a;
            background: white;
            box-shadow: 0 0 0 3px rgba(0,88,122,0.1);
        }

        /* Inputs deshabilitados */
        input:disabled {
            background: #e9ecef;
            color: #6c757d;
            cursor: not-allowed;
            opacity: 0.7;
        }

        .input-error {
            border-color: #dc3545 !important;
        }

        .error-message {
            color: #dc3545;
            font-size: 12px;
            margin-top: 4px;
        }

        /* Mensaje de validación */
        .validation-message {
            font-size: 12px;
            margin-top: 5px;
            padding: 5px;
            border-radius: 4px;
        }

        .validation-success {
            color: #28a745;
            background: #d4edda;
        }

        .validation-error {
            color: #dc3545;
            background: #f8d7da;
        }

        .validation-warning {
            color: #856404;
            background: #fff3cd;
        }

        /* Botón principal */
        .btn-primary {
            background: #0f3057;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: 0.3s;
            width: 100%;
        }

        .btn-primary:hover {
            background: #00587a;
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(0,88,122,0.3);
        }

        .btn-primary:disabled {
            background: #6c757d;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* Botón peligro */
        .btn-danger {
            background: #dc3545;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: 0.3s;
            width: 100%;
        }

        .btn-danger:hover {
            background: #c82333;
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(220,53,69,0.3);
        }

        /* Info box (mismo estilo para ambos) */
        .info-box {
            background: #eef2f6;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #00587a;
            font-size: 13px;
            color: #0f3057;
        }

        .info-box strong {
            font-size: 14px;
            display: block;
            margin-bottom: 3px;
        }

        /* Mensajes */
        .success-message {
            color: #28a745;
            font-size: 13px;
            margin-top: 10px;
            text-align: center;
            background: #e8f5e9;
            padding: 8px;
            border-radius: 6px;
        }

        .divider {
            height: 1px;
            background: #e6ecf2;
            margin: 20px 0 10px;
        }

        .help-text {
            text-align: center;
            color: #666;
            font-size: 12px;
            margin-top: 15px;
        }

        /* Grid para 2 columnas (opcional para admin) */
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        @media (max-width: 768px) {
            .grid-2 {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="profile-wrapper">
        <div class="profile-container">
            <!-- Header - IGUAL PARA AMBOS -->
            <div class="profile-header">
                <h1>👤 {{ Auth::user()->name }}</h1>
                <p>{{ Auth::user()->role === 'admin' ? 'Administrador del Sistema' : 'Usuario' }}</p>
            </div>

            @if(Auth::user()->role === 'admin')
                <!-- ========== ADMIN ========== -->
                
                <!-- Información Personal -->
                <div class="profile-card">
                    <div class="card-title">📋 Información Personal</div>
                    <div class="card-body">
                        <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
                            @csrf
                            @method('patch')

                            <div>
                                <label for="name">Nombre completo</label>
                                <input 
                                    id="name" 
                                    name="name" 
                                    type="text" 
                                    value="{{ old('name', Auth::user()->name) }}" 
                                    required 
                                    autofocus 
                                    autocomplete="name"
                                />
                                @error('name')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label for="email">Correo electrónico</label>
                                <input 
                                    id="email" 
                                    name="email" 
                                    type="email" 
                                    value="{{ old('email', Auth::user()->email) }}" 
                                    required 
                                    autocomplete="username"
                                />
                                @error('email')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn-primary">
                                💾 Guardar Cambios
                            </button>

                            @if (session('status') === 'profile-updated')
                                <div class="success-message">
                                    ✓ Información actualizada
                                </div>
                            @endif
                        </form>
                    </div>
                </div>

                <!-- Cambiar Contraseña CON VALIDACIÓN -->
                <div class="profile-card">
                    <div class="card-title">🔐 Cambiar Contraseña</div>
                    <div class="card-body">
                        <form method="post" action="{{ route('password.update') }}" id="password-form">
                            @csrf
                            @method('put')

                            <div style="margin-bottom: 15px;">
                                <label for="current_password">Contraseña actual</label>
                                <input 
                                    id="current_password" 
                                    name="current_password" 
                                    type="password" 
                                    class="@error('current_password') input-error @enderror"
                                    autocomplete="current-password"
                                    placeholder="Ingresa tu contraseña actual"
                                />
                                @error('current_password')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                                <div id="current-validation" class="validation-message" style="display: none;"></div>
                            </div>

                            <div style="margin-bottom: 15px;">
                                <label for="password">Nueva contraseña</label>
                                <input 
                                    id="password" 
                                    name="password" 
                                    type="password" 
                                    class="@error('password') input-error @enderror"
                                    autocomplete="new-password"
                                    placeholder="Mínimo 8 caracteres"
                                    disabled
                                />
                                @error('password')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div style="margin-bottom: 20px;">
                                <label for="password_confirmation">Confirmar contraseña</label>
                                <input 
                                    id="password_confirmation" 
                                    name="password_confirmation" 
                                    type="password" 
                                    autocomplete="new-password"
                                    placeholder="Repite tu nueva contraseña"
                                    disabled
                                />
                                <div id="password-match" class="validation-message" style="display: none;"></div>
                            </div>

                            <button type="submit" class="btn-primary" id="submit-btn" disabled>
                                🔒 Actualizar Contraseña
                            </button>

                            @if (session('status') === 'password-updated')
                                <div class="success-message">
                                    ✓ Contraseña actualizada
                                </div>
                            @endif
                        </form>
                    </div>
                </div>

                <!-- ZONA DE ELIMINACIÓN DE CUENTA (PROTEGIDA) -->
                @php
                    // Obtener el admin más antiguo (ID más bajo)
                    $primerAdmin = App\Models\User::where('role', 'admin')->orderBy('id')->first();
                @endphp

                @if(Auth::user()->id !== $primerAdmin->id)
                    <!-- Admin SECUNDARIO - Puede eliminarse -->
                    <div class="profile-card" style="border: 2px solid #dc3545;">
                        <div class="card-title" style="background: #dc3545;">⚠️ Eliminar Cuenta</div>
                        <div class="card-body">
                            <div class="info-box" style="border-left-color: #dc3545; background: #fff3f3;">
                                <strong>🚨 Zona de Peligro</strong>
                                Esta acción es permanente. Todos tus datos serán eliminados.
                            </div>

                            <form method="post" action="{{ route('profile.destroy') }}">
                                @csrf
                                @method('delete')

                                <div style="margin-bottom: 15px;">
                                    <label for="password">Confirma tu contraseña para continuar</label>
                                    <input 
                                        id="password" 
                                        name="password" 
                                        type="password" 
                                        placeholder="Ingresa tu contraseña"
                                    />
                                </div>

                                <button type="submit" class="btn-danger" onclick="return confirm('¿Estás SEGURO de eliminar tu cuenta? Esta acción NO se puede deshacer.')">
                                    🗑️ Eliminar mi cuenta permanentemente
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Admin PRINCIPAL - NO puede eliminarse -->
                    <div class="profile-card" style="border: 2px solid #ffd700; background: #fff9e6;">
                        <div class="card-title" style="background: #0f3057;">👑 Administrador Principal</div>
                        <div class="card-body">
                            <div class="info-box" style="border-left-color: #ffd700; background: #fff3cd;">
                                <strong>🔒 Cuenta Protegida</strong>
                                Esta es la cuenta de administrador principal del sistema. No puede ser eliminada para garantizar que siempre haya al menos un administrador.
                            </div>
                            
                            <div style="text-align: center; padding: 20px; color: #0f3057;">
                                <span style="font-size: 48px;">👑</span>
                                <p style="margin-top: 10px; font-weight: 600;">Administrador Principal</p>
                                <p style="font-size: 13px; color: #666;">Esta cuenta es permanente y no puede ser eliminada.</p>
                            </div>
                        </div>
                    </div>
                @endif
                
            @else
                <!-- ========== USUARIO - CON VALIDACIÓN ========== -->
                <div class="profile-card">
                    <div class="card-title">🔐 Cambiar mi Contraseña</div>
                    <div class="card-body">
                        <div class="info-box">
                            <strong>ℹ️ Información</strong>
                            Para modificar tu nombre o correo, contacta  a algún administrador.
                        </div>

                        <form method="post" action="{{ route('password.update') }}" id="password-form">
                            @csrf
                            @method('put')

                            <div style="margin-bottom: 15px;">
                                <label for="current_password">Contraseña actual</label>
                                <input 
                                    id="current_password" 
                                    name="current_password" 
                                    type="password" 
                                    class="@error('current_password') input-error @enderror"
                                    autocomplete="current-password"
                                    placeholder="Ingresa tu contraseña actual"
                                />
                                @error('current_password')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                                <div id="current-validation" class="validation-message" style="display: none;"></div>
                            </div>

                            <div style="margin-bottom: 15px;">
                                <label for="password">Nueva contraseña</label>
                                <input 
                                    id="password" 
                                    name="password" 
                                    type="password" 
                                    class="@error('password') input-error @enderror"
                                    autocomplete="new-password"
                                    placeholder="Mínimo 8 caracteres"
                                    disabled
                                />
                                @error('password')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div style="margin-bottom: 20px;">
                                <label for="password_confirmation">Confirmar contraseña</label>
                                <input 
                                    id="password_confirmation" 
                                    name="password_confirmation" 
                                    type="password" 
                                    autocomplete="new-password"
                                    placeholder="Repite tu nueva contraseña"
                                    disabled
                                />
                                <div id="password-match" class="validation-message" style="display: none;"></div>
                            </div>

                            <button type="submit" class="btn-primary" id="submit-btn" disabled>
                                🔒 Actualizar Contraseña
                            </button>

                            @if (session('status') === 'password-updated')
                                <div class="success-message">
                                    ✓ ¡Contraseña actualizada!
                                </div>
                            @endif
                        </form>

                        <div class="divider"></div>
                        <div class="help-text">
                            ¿Problemas? Contacta al administrador
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const currentPassword = document.getElementById('current_password');
        const newPassword = document.getElementById('password');
        const confirmPassword = document.getElementById('password_confirmation');
        const submitBtn = document.getElementById('submit-btn');
        const currentValidation = document.getElementById('current-validation');
        const passwordMatch = document.getElementById('password-match');
        
        let isCurrentValid = false;
        let timeoutId;

        // Verificar contraseña actual en tiempo real
        currentPassword.addEventListener('input', function() {
            clearTimeout(timeoutId);
            const password = this.value;
            
            if (password.length === 0) {
                resetValidation();
                return;
            }

            // Mostrar loading
            currentValidation.style.display = 'block';
            currentValidation.className = 'validation-message validation-warning';
            currentValidation.textContent = 'Verificando...';

            timeoutId = setTimeout(() => {
                // Petición AJAX para verificar contraseña
                fetch('/verify-password', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ password: password })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.valid) {
                        currentValidation.className = 'validation-message validation-success';
                        currentValidation.textContent = '✓ Contraseña correcta';
                        isCurrentValid = true;
                        
                        // Habilitar campos de nueva contraseña
                        newPassword.disabled = false;
                        confirmPassword.disabled = false;
                        
                        // Verificar si las nuevas contraseñas coinciden
                        checkPasswordMatch();
                    } else {
                        currentValidation.className = 'validation-message validation-error';
                        currentValidation.textContent = '✗ Contraseña incorrecta';
                        isCurrentValid = false;
                        
                        // Deshabilitar campos de nueva contraseña
                        newPassword.disabled = true;
                        confirmPassword.disabled = true;
                        newPassword.value = '';
                        confirmPassword.value = '';
                        submitBtn.disabled = true;
                        
                        if (passwordMatch) {
                            passwordMatch.style.display = 'none';
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    currentValidation.className = 'validation-message validation-error';
                    currentValidation.textContent = 'Error al verificar';
                });
            }, 500);
        });

        // Verificar que las contraseñas coincidan
        function checkPasswordMatch() {
            if (!isCurrentValid) {
                submitBtn.disabled = true;
                return;
            }

            const pass = newPassword.value;
            const confirm = confirmPassword.value;

            if (pass.length === 0 && confirm.length === 0) {
                passwordMatch.style.display = 'none';
                submitBtn.disabled = true;
                return;
            }

            passwordMatch.style.display = 'block';

            if (pass === confirm && pass.length >= 8) {
                passwordMatch.className = 'validation-message validation-success';
                passwordMatch.textContent = '✓ Las contraseñas coinciden';
                submitBtn.disabled = false;
            } else if (pass !== confirm && confirm.length > 0) {
                passwordMatch.className = 'validation-message validation-error';
                passwordMatch.textContent = '✗ Las contraseñas no coinciden';
                submitBtn.disabled = true;
            } else if (pass.length < 8 && pass.length > 0) {
                passwordMatch.className = 'validation-message validation-warning';
                passwordMatch.textContent = '⚠ La contraseña debe tener al menos 8 caracteres';
                submitBtn.disabled = true;
            } else {
                passwordMatch.style.display = 'none';
                submitBtn.disabled = true;
            }
        }

        newPassword.addEventListener('input', checkPasswordMatch);
        confirmPassword.addEventListener('input', checkPasswordMatch);

        function resetValidation() {
            currentValidation.style.display = 'none';
            isCurrentValid = false;
            newPassword.disabled = true;
            confirmPassword.disabled = true;
            newPassword.value = '';
            confirmPassword.value = '';
            submitBtn.disabled = true;
            
            if (passwordMatch) {
                passwordMatch.style.display = 'none';
            }
        }
    });
    </script>
</x-app-layout>