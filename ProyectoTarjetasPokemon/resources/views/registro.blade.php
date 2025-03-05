<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - Tienda en Línea</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <!-- Mensajes de alerta -->
        <div id="alertMessage" class="alert d-none"></div>
        
        <!-- Formulario de Registro -->
        <div class="card mb-4">
            <div class="card-header">
                <h3>Registro de Usuario</h3>
            </div>
            <div class="card-body">
                <form id="registerForm">
                    <div class="mb-3">
                        <label for="registerName" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="registerName" required>
                    </div>
                    <div class="mb-3">
                        <label for="registerEmail" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="registerEmail" required>
                    </div>
                    <div class="mb-3">
                        <label for="registerPassword" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="registerPassword" required>
                    </div>
                    <div class="mb-3">
                        <label for="registerPasswordConfirm" class="form-label">Confirmar Contraseña</label>
                        <input type="password" class="form-control" id="registerPasswordConfirm" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Registrarse</button>
                </form>
            </div>
        </div>
        
        <!-- Formulario de Inicio de Sesión -->
        <div class="card mb-4">
            <div class="card-header">
                <h3>Iniciar Sesión</h3>
            </div>
            <div class="card-body">
                <form id="loginForm">
                    <div class="mb-3">
                        <label for="loginEmail" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="loginEmail" required>
                    </div>
                    <div class="mb-3">
                        <label for="loginPassword" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="loginPassword" required>
                    </div>
                    <button type="submit" class="btn btn-success">Iniciar Sesión</button>
                </form>
            </div>
        </div>
        
        <!-- Información del Usuario (visible solo cuando está autenticado) -->
        <div id="userInfo" class="card d-none">
            <div class="card-header">
                <h3>Información del Usuario</h3>
            </div>
            <div class="card-body">
                <p><strong>Nombre:</strong> <span id="userName"></span></p>
                <p><strong>Correo:</strong> <span id="userEmail"></span></p>
                <p><strong>Rol:</strong> <span id="userRole"></span></p>
                <button id="logoutButton" class="btn btn-danger">Cerrar Sesión</button>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/Registro.js') }}"></script>
</body>
</html>