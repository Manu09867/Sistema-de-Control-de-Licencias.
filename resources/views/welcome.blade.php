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


.btn-login {
    background: #0f3057;
    color: white;
    border: none;
    padding: 12px;
    border-radius: 25px;
    cursor: pointer;
    font-weight: bold;
    transition: 0.3s;
}

.btn-login:hover {
    background: #00587a;
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
            <img src="{{ asset('images/login-bg.png') }}" class="logo-institucional">

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

        <form method="POST" action="#">
            @csrf

            <div class="input-group">
                <input type="user" name="user" placeholder="Usuario" required>
            </div>

            <div class="input-group">
                <input type="password" name="password" placeholder="Contraseña" required>
            </div>

            <button type="submit" class="btn-login">
                Entrar
            </button>
        </form>
    </div>

</div>

</body>
</html>