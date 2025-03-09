<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TAZ PIZZA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tu archivo de estilos -->
    <link rel="stylesheet" href="{{ asset('css/paginaIndex.css') }}">
    <!-- Fuentes -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&family=Fredoka+One&display=swap"
        rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
        <head>
</head>
<div class="container my-4 position-sticky" style="top: 0; z-index: 1000;">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="d-flex align-items-center justify-content-center">
                <label for="categoryFilter" class="me-3 fw-bold" style="font-family: 'Fredoka One', cursive;">Filtrar por categoría:</label>
                <select id="categoryFilter" class="form-select form-select-lg w-50">
                    <option value="all">Todos los productos</option>
                    <option value="pizza">Pizzas</option>
                    <option value="hotdog">Hot Dogs</option>
                    <option value="hamburguesa">Hamburguesas</option>
                    <option value="papas">Papas</option>
                    <option value="bebidas">Bebidas</option>
                    <option value="tacos">Tacos</option>
                </select>
            </div>
        </div>
    </div>
</div>
<body>
       <!-- Navbar -->
       <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="{{ asset('storage/images/pizzaLogopreview.png') }}" alt="TAZ PIZZA" height="50" class="me-2">
                <span class="fs-3 fw-bold" style="font-family: 'Fredoka One', cursive;">TAZ PIZZA</span>
                <!-- Mensaje de bienvenida -->
                <span id="welcomeMessage" class="ms-2 text-light" style="display: none;"></span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="Inicio">Inicio</a>
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



</div>





  <!-- Carrito desplegable -->
<div id="cartSidebar" class="cart-sidebar">
    <div class="cart-header">
        <h3 class="text-center">Tu Carrito</h3>
        <button type="button" class="btn-close" aria-label="Close" id="closeCart"></button>
    </div>
    <div class="cart-items" id="cartItems">
        <!-- Los productos del carrito se generarán dinámicamente con JS -->
    </div>
    <div class="cart-footer">
        <h4 class="text-center mb-3">Total: <span id="cartTotal">$0.00</span></h4>
        <div class="d-grid gap-2">
<button id="checkoutButton" class="btn btn-success mb-2">Realizar Pedido</button>
            <button id="clearCartButton" class="btn btn-danger">Vaciar Carrito</button>
        </div>
    </div>
</div>


<!-- Modal para selección de tamaño -->
<div class="modal fade" id="sizeModal" tabindex="-1" aria-labelledby="sizeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sizeModalLabel">Selecciona el tamaño</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <select class="form-select mb-3" id="sizeSelect">
                    <!-- Las opciones se llenarán dinámicamente -->
                </select>
                <div id="priceDisplay" class="h4 text-center">
                    <!-- El precio se actualizará dinámicamente -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="confirmSizeButton">Agregar al Carrito</button>
            </div>
        </div>
    </div>
</div>

    <!-- Header -->
    <header class="hero-section text-center py-5">
        <div class="container">
            <h1 class="display-4 fw-bold text-white">Bienvenido a TAZ PIZZA</h1>
            <p class="lead text-light">Desde 1994 sirviendo las mejores pizzas</p>
            <a href="#" class="btn btn-primary btn-lg mt-3">
                <!-- Cambiado a asset('storage/images/repartidorLogo.png') -->
                <img src="{{ asset('storage/images/repartidorLogo.png') }}" alt="Entrega a Domicilio" height="30"
                    class="me-2">
                Entrega a Domicilio
            </a>
            <a href="#" class="btn btn-primary btn-lg mt-3">
                <!-- Cambiado a asset('storage/images/localLogo.png') -->
                <img src="{{ asset('storage/images/localLogo.png') }}" alt="Recoger en Tienda" height="30" class="me-2">
                Recoger en Tienda
            </a>
        </div>
    </header>

    <!-- Menú de Pizzas -->
    <section class="container my-5">
        <h2 class="text-center mb-4 fw-bold" style="font-family: 'Fredoka One', cursive;">Nuestras Pizzas</h2>
        <div class="row g-4" id="pizzaMenu">
            <!-- Las pizzas se generarán dinámicamente con JS -->
        </div>
    </section>
    

    <!-- Menú de Hotdogs -->
    <section class="container my-5">
        <h2 class="text-center mb-4 fw-bold" style="font-family: 'Fredoka One', cursive;">Hotdogs</h2>
        <div class="row g-4" id="hotdogMenu">
            <!-- Los hotdogs se generarán dinámicamente con JS -->
        </div>
    </section>

    <!-- Menú de Hamburguesas -->
    <section class="container my-5">
        <h2 class="text-center mb-4 fw-bold" style="font-family: 'Fredoka One', cursive;">Hamburguesas</h2>
        <div class="row g-4" id="burgerMenu">
            <!-- Las hamburguesas se generarán dinámicamente con JS -->
        </div>
    </section>

    <!-- Menú de Papas -->
    <section class="container my-5">
        <h2 class="text-center mb-4 fw-bold" style="font-family: 'Fredoka One', cursive;">Papas</h2>
        <div class="row g-4" id="potatoMenu">
            <!-- Las papas se generarán dinámicamente con JS -->
        </div>
    </section>

    <!-- Menú de Bebidas -->
<section class="container my-5">
    <h2 class="text-center mb-4 fw-bold" style="font-family: 'Fredoka One', cursive;">Bebidas</h2>
    <div class="row g-4" id="bebidaMenu">
        <!-- Las bebidas se generarán dinámicamente con JS -->
    </div>
</section>

<!-- Menú de Tacos -->
<section class="container my-5">
    <h2 class="text-center mb-4 fw-bold" style="font-family: 'Fredoka One', cursive;">Tacos</h2>
    <div class="row g-4" id="tacoMenu">
        <!-- Los tacos se generarán dinámicamente con JS -->
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
<script src="{{ asset('js/datosPizza.js') }}"></script>
<script src="{{ asset('js/Registro.js') }}"></script> 

</body>

</html>