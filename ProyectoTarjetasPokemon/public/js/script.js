const pokemonContainer = document.querySelector('.pokemon-container');
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

// Almacenar todos los Pokémon para la búsqueda
let allPokemons = [];

// Obtener datos de Pokémon desde la API
async function fetchPokemon(id, pokemonType) {
    try {
        const response = await fetch(`https://pokeapi.co/api/v2/pokemon/${id}`);
        if (!response.ok) throw new Error("No se encontró el Pokémon");
        const data = await response.json();

        // Guardar el Pokémon para la búsqueda
        allPokemons.push(data);

        // Si el Pokémon tiene el tipo seleccionado, creamos su tarjeta
        const types = data.types.map(type => type.type.name);
        if (pokemonType === "" || types.includes(pokemonType)) {
            createPokemonCard(data, pokemonType);
        }
    } catch (error) {
        console.error("Error al obtener Pokémon:", error);
    }
}

// Crear la tarjeta del Pokémon
// Crear la tarjeta del Pokémon
// Crear la tarjeta del Pokémon
function createPokemonCard(pokemon, pokemonType) {
    const card = document.createElement("div");
    card.classList.add("pokemon-card");
    card.setAttribute("data-type", pokemon.types[0].type.name); // Asignar el tipo de Pokémon

    // Contenedor de partículas
    const particles = document.createElement("div");
    particles.classList.add("particles");

    // Obtener todos los tipos del Pokémon
    const types = pokemon.types.map(type => type.type.name.toUpperCase()).join(" / ");
    const price = priceMap[pokemon.types[0].type.name] || 400;

    // Contenido de la carta
    card.innerHTML = `
        <img src="${pokemon.sprites.front_default}" alt="${pokemon.name}">
        <h3>#${pokemon.id} ${pokemon.name.charAt(0).toUpperCase() + pokemon.name.slice(1)}</h3>
        <p>Tipo: ${types}</p>
        <p>Precio: $${price}</p>
        <button onclick="showStats(${pokemon.id})">Ver Estadísticas</button>
        <button onclick="addToCart(${pokemon.id}, '${pokemon.name}', '${pokemon.sprites.front_default}', ${price})">Agregar al Carrito</button>
    `;

    // Agregar el contenedor de partículas a la carta
    card.prepend(particles);

    // Agregar la carta al contenedor principal
    pokemonContainer.appendChild(card);

    // Agregar eventos de hover para las partículas
    card.addEventListener('mouseenter', () => {
        for (let i = 0; i < 30; i++) { // Genera 30 partículas
            const particle = document.createElement('div');
            particle.classList.add('particle');
            particle.style.left = `${Math.random() * 100}%`;
            particle.style.top = `${Math.random() * 100}%`;
            particle.style.animationDuration = `${Math.random() * 2 + 1}s`; // Duración aleatoria
            particle.style.animationDelay = `${Math.random() * 1}s`; // Retardo aleatorio
            particles.appendChild(particle);
        }
    });

    card.addEventListener('mouseleave', () => {
        particles.innerHTML = ''; // Limpia las partículas al salir
    });
}


function addToCart(id, name, sprite, price) {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];  // Recuperamos el carrito de localStorage
    const product = { id, name, sprite, price };

    // Verificamos si el Pokémon ya está en el carrito
    const existingProductIndex = cart.findIndex(item => item.id === id);
    if (existingProductIndex !== -1) {
        cart[existingProductIndex].quantity += 1;  // Aumentamos la cantidad si ya está en el carrito
    } else {
        cart.push({ ...product, quantity: 1 });  // Si no está en el carrito, lo agregamos con cantidad 1
    }

    localStorage.setItem("cart", JSON.stringify(cart));  // Guardamos el carrito actualizado en localStorage
    updateCartCount();  // Actualizamos el contador del carrito
}

function toggleCart() {
    const cartModal = document.getElementById("cart-modal");
    cartModal.style.display = (cartModal.style.display === "block") ? "none" : "block";  // Mostrar u ocultar el modal

    // Cargar los productos del carrito al abrir el modal
    loadCartItems();
}

function loadCartItems() {
    const cart = JSON.parse(localStorage.getItem("cart")) || [];
    const cartList = document.getElementById("cart-list");
    cartList.innerHTML = ""; // Limpiar la lista de productos del carrito

    cart.forEach((item, index) => {
        const cartItem = document.createElement("div");
        cartItem.classList.add("cart-item");
        cartItem.innerHTML = `
            <img src="${item.sprite}" alt="${item.name}">
            <span>${item.name}</span>
            <span>Precio: $${item.price}</span>
            <div class="quantity-control">
                <span>Cantidad: ${item.quantity}</span>
                <div>
                    <button class="increase-btn" onclick="increaseQuantity(${index})">➕</button>
                    <button class="decrease-btn" onclick="decreaseQuantity(${index})" ${item.quantity === 1 ? 'disabled' : ''}>➖</button>
                </div>
            </div>
        `;
        cartList.appendChild(cartItem);
    });
}

function increaseQuantity(index) {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    if (cart[index]) {
        cart[index].quantity += 1; // Aumentar la cantidad
        localStorage.setItem("cart", JSON.stringify(cart)); // Actualizar el carrito
        loadCartItems(); // Recargar los items del carrito
        updateCartCount(); // Actualizar el contador del carrito
    }
}

function decreaseQuantity(index) {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    if (cart[index] && cart[index].quantity > 1) {
        cart[index].quantity -= 1; // Disminuir la cantidad
        localStorage.setItem("cart", JSON.stringify(cart)); // Actualizar el carrito
        loadCartItems(); // Recargar los items del carrito
        updateCartCount(); // Actualizar el contador del carrito
    }
}

function updateCartCount() {
    const cart = JSON.parse(localStorage.getItem("cart")) || [];
    const cartCount = cart.reduce((acc, item) => acc + item.quantity, 0);  // Sumar todas las cantidades
    document.getElementById("cart-count").textContent = cartCount;  // Actualizar el contador en el botón
}


// Cargar Pokémon de acuerdo al tipo seleccionado
async function loadPokemons(pokemonType) {
    if (isLoading) return;
    isLoading = true;

    let start = offset;
    let end = Math.min(offset + limit, 899);

    // Limpiar contenedor antes de cargar nuevos Pokémon
    pokemonContainer.innerHTML = '';

    // Limpiar el arreglo de todos los Pokémon para evitar repeticiones
    allPokemons = [];

    for (let i = start; i < end; i++) {
        await fetchPokemon(i, pokemonType);  // Llamamos a fetchPokemon con el tipo seleccionado
    }

    offset = end;
    isLoading = false;
}

// Función que se ejecuta cuando el tipo de Pokémon se selecciona
document.getElementById('typeFilter').addEventListener('change', (event) => {
    const selectedType = event.target.value.toLowerCase();
    offset = 1;  // Reiniciar el offset al cambiar el tipo
    pokemonContainer.innerHTML = '';  // Limpiar la vista de los Pokémon antes de cargar los nuevos
    loadPokemons(selectedType); // Recargar los Pokémon filtrados
});

document.getElementById('searchInput').addEventListener('input', function () {
    searchPokemon(); // Llamar a la función de búsqueda cada vez que el usuario escriba
}); //esto creo que no va probar

// Función para buscar Pokémon por nombre o similitud
function searchPokemon() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase(); // Obtener el término de búsqueda

    // Limpiar el contenedor antes de mostrar los resultados
    pokemonContainer.innerHTML = '';

    // Filtrar Pokémon que contengan el término de búsqueda en su nombre
    const filteredPokemons = allPokemons.filter(pokemon => 
        pokemon.name.toLowerCase().includes(searchTerm)
    );

    // Crear tarjetas para los Pokémon filtrados
    filteredPokemons.forEach(pokemon => {
        createPokemonCard(pokemon, ""); // Mostrar todos los tipos, sin filtrar por tipo
    });
}

// Mostrar estadísticas de un Pokémon en el modal
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
        ).join(" ");

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

// Cargar Pokémon iniciales (sin filtrar)
document.addEventListener('DOMContentLoaded', () => {
    loadPokemons("");  // Cargar Pokémon sin filtro al inicio
});
document.addEventListener("DOMContentLoaded", function () {
    const cartButton = document.querySelector(".cart-button");
    const cartModal = document.querySelector(".cart-modal");
    const closeButton = document.querySelector(".close-cart");

    // Función para abrir el carrito
    function openCart(event) {
        event.stopPropagation(); // Evita que el evento se propague
        cartModal.classList.add("show"); // Abre el modal
    }

    // Función para cerrar el carrito
    function closeCart(event) {
        event.stopPropagation();
        cartModal.classList.remove("show"); // Cierra el modal
    }

    // Evento para abrir el carrito
    cartButton.addEventListener("click", openCart);

    // Evento para cerrar el carrito
    closeButton.addEventListener("click", closeCart);
});

document.querySelectorAll('.pokemon-card').forEach(card => {
    card.addEventListener('mouseenter', () => {
        const particles = card.querySelector('.particles');
        for (let i = 0; i < 30; i++) { // Genera 30 partículas
            const particle = document.createElement('div');
            particle.classList.add('particle');
            particle.style.left = `${Math.random() * 100}%`;
            particle.style.top = `${Math.random() * 100}%`;
            particle.style.animationDuration = `${Math.random() * 2 + 1}s`; // Duración aleatoria
            particle.style.animationDelay = `${Math.random() * 1}s`; // Retardo aleatorio
            particles.appendChild(particle);
        }
    });

    card.addEventListener('mouseleave', () => {
        const particles = card.querySelector('.particles');
        particles.innerHTML = ''; // Limpia las partículas al salir
    });
});