const pokemonContainer = document.querySelector(".pokemon-container");
let offset = 1; // Desde qué Pokémon empezar
const limit = 1000; // Cantidad de Pokémon por carga
let isLoading = false; // Evita múltiples ejecuciones de loadPokemons()

// Diccionario de precios según el tipo de Pokémon
const priceMap = {
    fire: 500,
    water: 450,
    grass: 400,
    electric: 550,
    ground: 480,
    psychic: 600,
    rock: 530,
    ice: 520,
    bug: 300,
    dragon: 1000,
    fairy: 700,
    fighting: 650,
    ghost: 750,
    steel: 580,
    poison: 350,
    flying: 420,
    normal: 320,
    dark: 670
};

// Obtener datos de Pokémon desde la API
async function fetchPokemon(id) {
    try {
        const response = await fetch(`https://pokeapi.co/api/v2/pokemon/${id}`);
        if (!response.ok) throw new Error("No se encontró el Pokémon");
        const data = await response.json();
        createPokemonCard(data);
    } catch (error) {
        console.error("Error al obtener Pokémon:", error);
    }
}

// Crear la tarjeta del Pokémon
function createPokemonCard(pokemon) {
    const card = document.createElement("div");
    card.classList.add("pokemon-card");

    // Obtener el primer tipo para asignar precio
    const pokemonType = pokemon.types[0].type.name;
    const price = priceMap[pokemonType] || 400; // Si no está en el mapa, asignar un precio genérico

    card.innerHTML = `
        <img src="${pokemon.sprites.front_default}" alt="${pokemon.name}">
        <h3>#${pokemon.id} ${pokemon.name.charAt(0).toUpperCase() + pokemon.name.slice(1)}</h3>
        <p>Tipo: ${pokemonType.toUpperCase()}</p>
        <p>Precio: $${price}</p>
        <button onclick="showStats(${pokemon.id})">Ver Gráfica</button>
    `;

    pokemonContainer.appendChild(card);
}

// Cargar un lote de Pokémon con control de ejecución
async function loadPokemons() {
    if (isLoading) return;
    isLoading = true;

    let start = offset;
    let end = Math.min(offset + limit, 899);

    for (let i = start; i < end; i++) {
        await fetchPokemon(i);
    }

    offset = end;
    isLoading = false;
}

async function showStats(pokemonId) {
    try {
        const response = await fetch(`https://pokeapi.co/api/v2/pokemon/${pokemonId}`);
        if (!response.ok) throw new Error("No se encontró el Pokémon");
        const pokemon = await response.json();

        // Obtener el tipo y precio
        const pokemonType = pokemon.types[0].type.name;
        const price = priceMap[pokemonType] || 400;

        // Construir lista de estadísticas
        const statsHTML = pokemon.stats.map(stat => 
            `<li>${stat.stat.name.toUpperCase()}: ${stat.base_stat}</li>`
        ).join("");

        // Llenar el modal con datos
        document.getElementById("modal-title").textContent = `#${pokemon.id} ${pokemon.name.charAt(0).toUpperCase() + pokemon.name.slice(1)}`;
        document.getElementById("modal-price").textContent = `Precio: $${price}`;
        document.getElementById("modal-stats").innerHTML = statsHTML;
        document.querySelector("#modal #modal-image").src = pokemon.sprites.front_default; // Aseguramos que la imagen del Pokémon se muestre

        // Mostrar el modal
        document.getElementById("modal").style.display = "block";
    } catch (error) {
        console.error("Error al obtener estadísticas:", error);
    }
}

// Cerrar el modal
function cerrarModal() {
    document.getElementById("modal").style.display = "none";
}

// Iniciar sesión
function IniciarSesion() {
    alert("Inicia sesión para seguir");
}

// Cargar Pokémon iniciales
loadPokemons();
