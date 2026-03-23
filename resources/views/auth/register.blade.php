<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Sistema de Inventario</title>

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
            height: 600px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 45px rgba(0,0,0,0.2);
            display: flex;
            overflow: hidden;
        }

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

        .right {
            width: 50%;
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .right h2 {
            font-size: 26px;
            margin-bottom: 25px;
            color: #0f3057;
        }

        .input-group {
            margin-bottom: 15px;
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

        .btn {
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

        .btn:hover {
            background: #00587a;
        }

        .links {
            margin-top: 20px;
            text-align: center;
        }

        .links a {
            color: #0f3057;
            text-decoration: none;
            font-size: 14px;
        }

        .links a:hover {
            text-decoration: underline;
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
    </style>
</head>
<body>

<div class="container">

    <div class="left">
        <div class="left-content">
            <img src="{{ asset('images/login-bg.png') }}" class="logo-institucional">
            <h2>Sistema de Control</h2>
            <p>
                Fiscalía General<br>
                del Estado de Aguascalientes
            </p>
        </div>
    </div>

    <div class="right">
        <h2>Registro de Usuario</h2>

        @if($errors->any())
            <div class="alert">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="input-group">
                <input type="text" name="name" placeholder="Nombre completo"
                    value="{{ old('name') }}"
                    class="@error('name') input-error @enderror" required>
                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="input-group">
                <input type="email" name="email" placeholder="Correo electrónico"
                    value="{{ old('email') }}"
                    class="@error('email') input-error @enderror" required>
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="input-group">
                <input type="password" name="password" placeholder="Contraseña"
                    class="@error('password') input-error @enderror" required>
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="input-group">
                <input type="password" name="password_confirmation" placeholder="Confirmar contraseña" required>
            </div>

            <button type="submit" class="btn">
                Crear Cuenta
            </button>

            <div class="links">
                <a href="{{ route('login') }}">
                    ¿Ya tienes cuenta? Inicia sesión
                </a>
            </div>
        </form>
    </div>

</div>

</body>
</html>