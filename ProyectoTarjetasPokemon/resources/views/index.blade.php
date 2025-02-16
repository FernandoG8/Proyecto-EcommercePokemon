<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Pokedex')</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>

    <div class="header">
        <img class="logoPokemon" src="{{ asset('resources/imagenes/pokemonlogo.png') }}" alt="Pokémon Logo"> 
    </div>

    <!-- Agregar el ícono del carrito en la cabecera -->
<div class="header">
    <button class="cart-button" onclick="toggleCart()">
        <img src="{{ asset('resources/imagenes/carrito.png') }}" alt="Carrito"> <!-- Cambia este src por el de la imagen que deseas usar -->
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

    <div class="modal" id="modal">
        <div class="modal-content">
            <img src="{{ asset('recursos/imagenes/pokebola.png') }}" alt="Pokébola" id="modal-image" class="modal-image">
            <h2 id="modal-title">Título del Modal</h2>
            <p id="modal-price">Texto dentro del modal</p>
            <ul id="modal-stats"></ul> <!-- Aquí se muestran las estadísticas -->
            <span class="close" onclick="cerrarModal()">&times;</span>
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

    
    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>
