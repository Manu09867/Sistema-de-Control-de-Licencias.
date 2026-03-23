<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso - Sistema de Inventario</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #e6ecf2;
        }

        .container {
            width: 950px;
            height: 550px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 45px rgba(0,0,0,0.2);
            display: flex;
            overflow: hidden;
        }

        /* PANEL IZQUIERDO */
        .left {
            width: 50%;
            background: linear-gradient(135deg, #0f3057, #00587a);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .left-content {
            padding: 40px;
        }

        .logo-institucional {
            width: 180px;
            margin-bottom: 25px;
        }

        .left-content h2 {
            font-size: 28px;
            margin-bottom: 15px;
        }

        .left-content p {
            font-size: 15px;
            opacity: 0.9;
        }

        /* PANEL DERECHO */
        .right {
            width: 50%;
            padding: 70px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .right h2 {
            font-size: 26px;
            margin-bottom: 30px;
            color: #0f3057;
        }

        .input-group {
            margin-bottom: 18px;
        }

        input {
            width: 100%;
            padding: 12px 15px;
            border-radius: 6px;
            border: 1px solid #ccd6dd;
            background: #f4f7fa;
            font-size: 14px;
        }

        input:focus {
            outline: none;
            border-color: #00587a;
            background: white;
        }

        .input-error {
            border-color: #dc3545 !important;
        }

        .error-message {
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
            margin-left: 5px;
        }

        .btn-login {
            width: 100%;
            background: #0f3057;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 25px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s;
            margin-top: 10px;
        }

        .btn-login:hover {
            background: #00587a;
        }

        .alert {
            background: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
            font-size: 14px;
        }

        /* Mensaje de ayuda (opcional) */
        .help-text {
            margin-top: 20px;
            text-align: center;
            font-size: 13px;
            color: #888;
            border-top: 1px solid #e6ecf2;
            padding-top: 20px;
        }

        .help-text p {
            margin-bottom: 5px;
        }

        /* RESPONSIVE */
        @media(max-width: 900px) {
            .container {
                flex-direction: column;
                width: 95%;
                height: auto;
            }

            .left, .right {
                width: 100%;
                padding: 40px;
            }

            .right {
                padding: 40px;
            }
        }
    </style>
</head>
<body>

<div class="container">

    <!-- LADO IZQUIERDO -->
    <div class="left">
        <div class="left-content">
            <img src="{{ asset('images/login-bg.png') }}" class="logo-institucional" alt="Logo Fiscalía">
            <h2>Sistema de Control</h2>
            <p>
                Fiscalía General<br>
                del Estado de Aguascalientes
            </p>
        </div>
    </div>

    <!-- LADO DERECHO -->
    <div class="right">
        <h2>Iniciar Sesión</h2>

        <!-- Mostrar errores de sesión -->
        @if($errors->any())
            <div class="alert">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="input-group">
                <input 
                    type="email" 
                    name="email" 
                    placeholder="Correo electrónico" 
                    value="{{ old('email') }}"
                    class="@error('email') input-error @enderror"
                    required 
                    autofocus
                >
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="input-group">
                <input 
                    type="password" 
                    name="password" 
                    placeholder="Contraseña"
                    class="@error('password') input-error @enderror"
                    required
                >
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <label style="display: flex; align-items: center; gap: 5px; font-size: 14px; color: #666;">
                    <input type="checkbox" name="remember"> Recordarme
                </label>
            </div>

            <button type="submit" class="btn-login">
                Entrar al Sistema
            </button>

            <!-- SOLO UN MENSAJE INFORMATIVO, SIN ENLACES -->
            <div class="help-text">
                <p>🔒 Sistema de uso interno</p>
                <p>Las cuentas son gestionadas por el administrador del sistema</p>
            </div>
        </form>
    </div>

</div>

</body>
</html>