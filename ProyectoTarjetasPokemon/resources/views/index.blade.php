<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Pokedex')</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fondoParticulas.css') }}"> <!-- Asegúrate de que la ruta sea correcta -->
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="pokemon-background">

    <div class="header">
        <img class="logoPokemon" src="{{ asset('resources/imagenes/pokemonlogo.png') }}" alt="Pokémon Logo"> 
    </div>

    <!-- Agregar el ícono del carrito en la cabecera -->
<div class="header">
    <button class="cart-button" onclick="toggleCart()">
        <img src="{{ asset('resources\imagenes\carrito-de-compras.png') }}" alt="Carrito">  
        <span id="cart-count">0</span> <!-- Número de productos en el carrito -->
    </button>
</div>


    <div class="search-bar">
    <input type="text" id="searchInput" placeholder="Buscar Pokémon por nombre...">


        <button class="login-button" onclick="IniciarSesion()">Iniciar Sesión</button>
       
        <select id="typeFilter">
            <option value="">Todos</option>
            <option value="fire">Fuego</option>
            <option value="water">Agua</option>
            <option value="grass">Planta</option>
            <option value="electric">Eléctrico</option>
            <option value="psychic">Psíquico</option>
            <option value="flying">Volador</option>
            <option value="dragon">Dragón</option>
            <option value="steel">Acero</option>
            <option value="normal">Normal</option>
            <option value="poison">Veneno</option>
            <option value="ground">Tierra</option>
            <option value="rock">Roca</option>
            <option value="bug">Bicho</option>
            <option value="fairy">Hada</option>
            <option value="fighting">Lucha</option>
            <option value="dark">Siniestro</option>
            <option value="ice">Hielo</option>
            <option value="ghost">Fantasma</option>
        </select>
    </div>

    <div class="pokemon-container"></div>

    <!-- Modal de Bootstrap -->
    <div class="modal fade" id="statsModal" tabindex="-1" aria-labelledby="statsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statsModalLabel">Estadísticas de Pokémon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center position-relative">
                <!-- Contenedor de partículas -->
                <div class="particles-container"></div>
                <img id="modal-image" src="" alt="Pokémon" class="img-fluid mb-3">
                <h3 id="modal-title"></h3>
                <p id="modal-price"></p>
                <ul id="modal-stats" class="list-unstyled"></ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

    
<!-- Modal del carrito -->
<div class="cart-modal" id="cart-modal">
    <div class="cart-modal-content">
        <h2>Tu Carrito</h2>
        <div id="cart-list">
            <!-- Los productos del carrito se agregarán aquí dinámicamente -->
        </div>
        <button onclick="toggleCart()">Cerrar</button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>
