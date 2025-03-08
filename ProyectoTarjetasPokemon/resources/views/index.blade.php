<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TAZ PIZZA</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Tus archivos de estilos -->
    <link rel="stylesheet" href="{{ asset('css/paginaIndex.css') }}">
    <link rel="stylesheet" href="{{ asset('css/CarruselFotos.css') }}">

    <!-- Fuentes -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&family=Fredoka+One&display=swap"
          rel="stylesheet">
          <!-- Agregar en el head -->
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <img src="{{ asset('storage/images/pizzaLogopreview.png') }}" alt="TAZ PIZZA" height="50" class="me-2">
            <span class="fs-3 fw-bold" style="font-family: 'Fredoka One', cursive;">TAZ PIZZA</span>
            <!-- Agregar el mensaje de bienvenida -->
            <span id="welcomeMessage" class="ms-2 text-light" style="display: none;"></span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="#">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/Menu">Menú</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/pedidos">Pedidos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Contacto</a>
                </li>
            </ul>
            <div class="ms-3">
                <!-- Botón de Registro/Iniciar Sesión -->
                <button id="authButton" class="btn btn-outline-light me-2" data-bs-toggle="modal" data-bs-target="#authModal">
                    <i class="fas fa-user me-2"></i>Registrarse / Iniciar Sesión
                </button>
                <!-- Botón de Cerrar Sesión -->
                <button id="logoutButton" class="btn btn-outline-light me-2" style="display: none;" onclick="logoutUser()">
                    <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                </button>
                <!-- Carrito -->
                <button id="carButton" class="btn btn-outline-light">
                    <img src="{{ asset('storage/images/carritoCompras.png') }}" alt="Carrito" height="30">
                    <span id="cartCount">0</span>
                </button>
            </div>
        </div>
    </div>
</nav>

<<!-- Modal de Registro/Iniciar Sesión -->
<div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="authModalLabel">Acceso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Alerta para mensajes -->
                <div id="alertMessage" class="alert d-none" role="alert"></div>
                
                <!-- Formulario de Registro -->
<div id="registerDiv">
    <h4>Registro</h4>
    <form id="registerForm">
        <div class="mb-3">
            <label for="registerName" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="registerName" required>
        </div>
        <div class="mb-3">
            <label for="registerEmail" class="form-label">Email</label>
            <input type="email" class="form-control" id="registerEmail" required>
        </div>
        <div class="mb-3">
            <label for="registerPhone" class="form-label">Teléfono</label>
            <input type="tel" class="form-control" id="registerPhone">
        </div>
        <div class="mb-3">
            <label for="registerPassword" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="registerPassword" required>
        </div>
        <div class="mb-3">
            <label for="registerPasswordConfirm" class="form-label">Confirmar Contraseña</label>
            <input type="password" class="form-control" id="registerPasswordConfirm" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Registrarse</button>
    </form>
    <hr>
    <p class="text-center">¿Ya tienes cuenta? <a href="#" id="showLogin">Inicia sesión</a></p>
</div>

                <!-- Formulario de Login -->
                <div id="loginDiv" style="display: none;">
                    <h4>Iniciar Sesión</h4>
                    <form id="loginForm">
                        <div class="mb-3">
                            <label for="loginEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="loginEmail" required>
                        </div>
                        <div class="mb-3">
                            <label for="loginPassword" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="loginPassword" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
                    </form>
                    <hr>
                    <p class="text-center">¿No tienes cuenta? <a href="#" id="showRegister">Regístrate</a></p>
                </div>
            </div>
        </div>
    </div>
</div>


    <!-- Carrito desplegable -->
    <div id="cartSidebar" class="cart-sidebar">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="text-center">Tu Carrito</h3>
            <!-- Removemos onclick; usamos id="closeCartButton" -->
            <button class="btn btn-close" id="closeCartButton" type="button"></button>
        </div>
        <div id="cartItems" class="cart-items">
            <!-- Productos del carrito generados dinámicamente con JS -->
        </div>
        <div class="text-center">
            <h4>Total: <span id="cartTotal">$0.00</span></h4>
            <!-- Removemos onclick; usamos id="clearCartButton" -->
            <button class="btn btn-danger" id="clearCartButton" type="button">Vaciar Carrito</button>
        </div>
    </div>

    <!-- Header -->
    <header class="hero-section text-center py-5">
        <div class="container">
            <h1 class="display-4 fw-bold text-white">TAZ PIZZA</h1>
            <p class="lead text-light">Desde 1994 sirviendo las mejores pizzas</p>
            <a href="#" class="btn btn-primary btn-lg mt-3">
                <!-- RUTA AJUSTADA A asset('storage/images/repartidorLogo.png') -->
                <img src="{{ asset('storage/images/repartidorLogo.png') }}" alt="Entrega a Domicilio" height="30" class="me-2">
                Entrega a Domicilio
            </a>
            <a href="#" class="btn btn-primary btn-lg mt-3">
                <!-- RUTA AJUSTADA A asset('storage/images/localLogo.png') -->
                <img src="{{ asset('storage/images/localLogo.png') }}" alt="Recoger en Tienda" height="30" class="me-2">
                Recoger en Tienda
            </a>
        </div>
    </header>

    <!-- Sección de imágenes en cuadrícula -->
    <section class="my-5">
        <div class="container">
            <h2 class="text-center fw-bold mb-4" style="font-family: 'Fredoka One', cursive;">NUESTROS PRODUCTOS</h2>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <!-- Imagen 1 -->
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <!-- RUTA AJUSTADA -->
                        <img src="{{ asset('storage/images/comida1.jpg') }}" class="card-img-top" alt="Pizza Clásica">
                        <div class="card-body">
                            <h5 class="card-title">COMBO MIX PARA COMPARTIR</h5>
                            <p class="card-text">Disfruta de nuestras deliciosas alitas, bongless, dedos de queso que están para chuparse los dedos.</p>
                        </div>
                    </div>
                </div>
                <!-- Imagen 2 -->
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <!-- RUTA AJUSTADA -->
                        <img src="{{ asset('storage/images/comida2.jpg') }}" class="card-img-top" alt="Pizza Hawaiana">
                        <div class="card-body">
                            <h5 class="card-title">Pastas Alfredo</h5>
                            <p class="card-text">De Italia a tu mesa, la mejor pasta que puedas probar aquí en TazPizza.</p>
                        </div>
                    </div>
                </div>
                <!-- Imagen 3 -->
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <!-- RUTA AJUSTADA -->
                        <img src="{{ asset('storage/images/comida3.jpg') }}" class="card-img-top" alt="Pizza Mexicana">
                        <div class="card-body">
                            <h5 class="card-title">Hamburguesa Clásica</h5>
                            <p class="card-text">¡La hamburguesa que conquistará tu paladar!</p>
                        </div>
                    </div>
                </div>
                <!-- Imagen 4 -->
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <!-- RUTA AJUSTADA -->
                        <img src="{{ asset('storage/images/comida4.jpg') }}" class="card-img-top" alt="Pizza Pepperoni">
                        <div class="card-body">
                            <h5 class="card-title">MIX AMIGOS</h5>
                            <p class="card-text">¿Boneless? ¿Alitas? ¿Aros de cebolla? TODO en un paquete para ti.</p>
                        </div>
                    </div>
                </div>
                <!-- Imagen 5 -->
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <!-- RUTA AJUSTADA -->
                        <img src="{{ asset('storage/images/comida5.jpg') }}" class="card-img-top" alt="Pizza Vegetariana">
                        <div class="card-body">
                            <h5 class="card-title">Pizza 2 especialidades</h5>
                            <p class="card-text">¿Quieres pepperoni o hawaiana? ¡POR QUÉ NO LAS 2!</p>
                        </div>
                    </div>
                </div>
                <!-- Imagen 6 -->
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <!-- RUTA AJUSTADA -->
                        <img src="{{ asset('storage/images/comida6.jpg') }}" class="card-img-top" alt="Pizza Cuatro Quesos">
                        <div class="card-body">
                            <h5 class="card-title">Tacos de Camarón</h5>
                            <p class="card-text">Exquisitos tacos de tortilla hecha a mano con camarones a la diabla y queso.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Promociones -->
    <section class="bg-light py-5">
        <div class="container text-center">
            <h2 class="fw-bold mb-4" style="font-family: 'Fredoka One', cursive;">Promociones</h2>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card h-100 shadow hover-3d">
                        <div class="card-body">
                            <h3 class="card-title">Promo Martes y Jueves</h3>
                            <p class="card-text">Doble pizza con ajonjolí en la orilla.</p>
                            <p class="card-text">2 Medianas x $249</p>
                            <p class="card-text">2 Grandes x $299</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100 shadow hover-3d">
                        <div class="card-body">
                            <h3 class="card-title">Fantástica</h3>
                            <p class="card-text">24 rebanadas, 2 especialidades.</p>
                            <p class="card-text">$299 (Orilla rellena +$80)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-4">
        <div class="container">
            <p class="mb-0">&copy; 2023 TAZ PIZZA. Todos los derechos reservados.</p>
            <p class="mb-0">
                Síguenos en redes sociales:
                <a href="#" class="text-danger">Facebook</a> |
                <a href="#" class="text-danger">Instagram</a> |
                <a href="#" class="text-danger">Twitter</a>
            </p>
        </div>
    </footer>

    <!-- Bootstrap y tu archivo JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js"></script>
    <script>
        // Verificar el estado de autenticación al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            const token = localStorage.getItem('token');
            const userName = localStorage.getItem('userName');
            
            if (token && userName) {
                // Usuario autenticado
                document.getElementById('authButton').style.display = 'none';
                document.getElementById('logoutButton').style.display = 'inline-block';
                document.getElementById('welcomeMessage').textContent = `¡Hola ${userName}!`;
                document.getElementById('welcomeMessage').style.display = 'inline';
            } else {
                // Usuario no autenticado
                document.getElementById('authButton').style.display = 'inline-block';
                document.getElementById('logoutButton').style.display = 'none';
                document.getElementById('welcomeMessage').style.display = 'none';
            }
        });
    </script>
    <script src="{{ asset('js/Registro.js') }}"></script>
</body>
</html>