<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TAZ PIZZA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/paginaIndex.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&family=Fredoka+One&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="resources/imagenes/pizzaLogopreview.png" alt="TAZ PIZZA" height="50" class="me-2">
                <span class="fs-3 fw-bold" style="font-family: 'Fredoka One', cursive;">TAZ PIZZA</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Menú</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Pedidos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contacto</a>
                    </li>
                </ul>
                <div class="ms-3">
                    <button class="btn btn-outline-light" onclick="toggleCart()">
                        <img src="resources/imagenes/carritoLogo.png" alt="Carrito" height="30">
                        <span id="cartCount">0</span>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Carrito desplegable -->
    <div id="cartSidebar" class="cart-sidebar">
        <h3 class="text-center">Tu Carrito</h3>
        <div id="cartItems" class="cart-items">
            <!-- Los productos del carrito se generarán dinámicamente con JS -->
        </div>
        <div class="text-center">
            <h4>Total: <span id="cartTotal">$0.00</span></h4>
            <button class="btn btn-danger" onclick="clearCart()">Vaciar Carrito</button>
        </div>
    </div>

    <!-- Modal para seleccionar tamaño -->
    <div class="modal fade" id="sizeModal" tabindex="-1" aria-labelledby="sizeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sizeModalLabel">Selecciona el tamaño</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <select class="form-select" id="sizeSelect">
                        <option value="chica">Chica</option>
                        <option value="mediana">Mediana</option>
                        <option value="grande">Grande</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="confirmSize()">Confirmar</button>
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
                <img src="resources/imagenes/repartidorLogo.png" alt="Entrega a Domicilio" height="30" class="me-2">
                Entrega a Domicilio
            </a>
            <a href="#" class="btn btn-primary btn-lg mt-3">
                <img src="resources/imagenes/localLogo.png" alt="Recoger en Tienda" height="30" class="me-2">
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
            <p class="mb-0">Síguenos en redes sociales: 
                <a href="#" class="text-danger">Facebook</a> | 
                <a href="#" class="text-danger">Instagram</a> | 
                <a href="#" class="text-danger">Twitter</a>
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/datosPizza.js') }}"></script>
</body>
</html>
