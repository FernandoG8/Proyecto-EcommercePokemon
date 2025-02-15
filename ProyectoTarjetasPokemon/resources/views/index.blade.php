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

    <div class="search-bar">
        <input type="text" id="searchInput" placeholder="Busca tu Pokémon">
        <button class="login-button" onclick="IniciarSesion()">Iniciar Sesión</button>
       
        <select id="typeFilter">
            <option value="">Tipo de Pokémon</option>
            <option>Agua</option>
            <option>Fuego</option>
            <option>Planta</option>
            <option>Eléctrico</option>
            <option>Psíquico</option>
            <option>Volador</option>
            <option>Dragón</option>
            <option>Acero</option>
            <option>Normal</option>
            <option>Veneno</option>
            <option>Tierra</option>
            <option>Roca</option>
            <option>Bicho</option>
            <option>Hada</option>
            <option>Lucha</option>
            <option>Siniestro</option>
            <option>Hielo</option>
            <option>Fantasma</option>
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
    
    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>
