document.addEventListener("DOMContentLoaded", function () {
    // Datos de las pizzas
    const pizzas = [
        { name: "Queso", prices: { chica: 60, mediana: 130, grande: 160 }, description: "Deliciosa pizza con extra quesito derretido, perfecta para los amantes del queso." },
        { name: "Hawaiana", prices: { chica: 70, mediana: 140, grande: 160 }, description: "Jugoso jamón, dulce piña y mucho quesito. ¡Una combinación tropical!" },
        { name: "Choriqueso", prices: { chica: 70, mediana: 140, grande: 170 }, description: "Chorizo asadito y extra quesito derretido. ¡Un sabor intenso!" },
        { name: "Doggo", prices: { chica: 70, mediana: 140, grande: 170 }, description: "Salchicha, tocino crujiente, tomate, jalapeño, cebolla, catsup, mostaza y quesito. ¡Explosión de sabores!" },
        { name: "Chicago", prices: { chica: 70, mediana: 145, grande: 185 }, description: "Salchicha, chorizo, salami y mucho quesito. ¡Para los más atrevidos!" },
        { name: "Mister Pep", prices: { chica: 70, mediana: 145, grande: 190 }, description: "Peperoni, champiñones frescos y quesito derretido. ¡Un clásico irresistible!" },
        { name: "Jarocha", prices: { chica: 70, mediana: 145, grande: 190 }, description: "Atún, champiñones, cebolla, jalapeño, tomate y quesito. ¡Un sabor único!" },
        { name: "Napolitana", prices: { chica: 70, mediana: 145, grande: 190 }, description: "Jamón, salchicha, tocino, pimiento morrón, champiñones y quesito. ¡Una fiesta de sabores!" },
        { name: "Vegetariana", prices: { chica: 70, mediana: 145, grande: 185 }, description: "Piña, champiñones, aceitunas, pimiento, cebolla, jitomate, elote y quesito. ¡Saludable y deliciosa!" },
        { name: "Meat", prices: { chica: 70, mediana: 145, grande: 190 }, description: "Jamón, salchicha, tocino, chorizo y quesito. ¡Para los amantes de la carne!" },
        { name: "Peperoni", prices: { chica: 70, mediana: 145, grande: 190 }, description: "Peperoni picante y mucho quesito. ¡Un clásico que nunca falla!" },
        { name: "Mexicana", prices: { chica: 70, mediana: 150, grande: 190 }, description: "Jamón, frijoles, chorizo, elote, cebolla, jitomate, jalapeños, aguacate y quesito. ¡Un sabor bien mexicano!" },
        { name: "Al Pastor", prices: { chica: 70, mediana: 150, grande: 190 }, description: "Carne al pastor, pimiento, cebolla, piña, jalapeño y quesito. ¡Un toque de sabor único!" },
        { name: "Maximo", prices: { chica: 75, mediana: 150, grande: 195 }, description: "Jamón, pepperoni, pimiento, cebolla, champiñones y quesito. ¡El máximo sabor en cada bocado!" },
        { name: "Chocking", prices: { chica: 75, mediana: 160, grande: 199 }, description: "Jamón, salchicha, chorizo, champiñones, cebolla, aceitunas, pimiento y quesito. ¡Te dejará sin palabras!" }
    ];

    // Datos de hotdogs, hamburguesas y papas
    const hotdogs = [
        { name: "Clásico", price: 59, description: "Salchicha super jumbo envuelta en tocino, con tomate, cebolla, chile en vinagre y un toque de mostaza y catsup. ¡Un clásico que nunca pasa de moda!" },
        { name: "Peperoni", price: 76, description: "Salchicha super jumbo envuelta en tocino, con peperoni y queso mozzarella derretido. ¡Ideal para los amantes del queso!" },
        { name: "BBQ", price: 76, description: "Salchicha super jumbo con cebolla caramelizada, salsa BBQ y queso mozzarella. ¡Un sabor ahumado y dulce!" },
        { name: "El Gobernante", price: 76, description: "Salchicha super jumbo envuelta en tocino, con cebolla caramelizada, aguacate y queso mozzarella. ¡Un manjar digno de un rey!" },
        { name: "Tamtoc", price: 69, description: "Salchicha super jumbo con piña asada, cebolla caramelizada y rodajas de jalapeño natural. ¡Un toque tropical y picante!" }
    ];

    const hamburguesas = [
        { name: "Clásica", price: 85, description: "Hamburguesa de arracherra con tocino crujiente, queso derretido, tomate, lechuga y cebolla. ¡Un clásico que nunca falla!" },
        { name: "Hawaiana", price: 85, description: "Hamburguesa de arracherra con tocino, piña, jamón, tomate, lechuga y cebolla. ¡Un toque dulce y salado!" },
        { name: "Mexicana", price: 85, description: "Hamburguesa de arracherra con tocino, piña, jamón, tomate, lechuga y cebolla. ¡Un sabor bien mexicano!" }
    ];

    const papas = [
        { name: "A la Francesa", price: 45, description: "Papas a la francesa crujientes y doradas. ¡Perfectas para acompañar cualquier platillo!" },
        { name: "Gajo", price: 50, description: "Papas gajo bien sazonadas y horneadas. ¡Un toque rústico y delicioso!" }
    ];

    // Carrito
    let cart = [];
    // Variable para la pizza seleccionada
    let selectedPizza = null;

    // ----------------------
    // FUNCIONES PRINCIPALES
    // ----------------------

    // Mostrar/ocultar el carrito
    function toggleCart() {
        const cartSidebar = document.getElementById("cartSidebar");
        cartSidebar.classList.toggle("active");
    }

    // Abrir el modal de selección de tamaño (usado en el HTML dinámico)
    window.openSizeModal = function (pizza) {
        selectedPizza = pizza;
        const modal = new bootstrap.Modal(document.getElementById('sizeModal'));
        modal.show();
    };

    // Confirmar el tamaño (sin onclick en HTML; lo enganchamos abajo con un eventListener)
    function confirmSize() {
        const size = document.getElementById('sizeSelect').value;
        const price = selectedPizza.prices[size];
        cart.push({ name: `${selectedPizza.name} (${size})`, price });
        updateCart();
        const modal = bootstrap.Modal.getInstance(document.getElementById('sizeModal'));
        modal.hide();
    }

    // Agregar al carrito para hotdogs, hamburguesas, papas (usado en el HTML dinámico)
    window.addToCart = function (name, price) {
        cart.push({ name, price });
        updateCart();
    };

    // Eliminar del carrito (usado en el HTML dinámico)
    window.removeFromCart = function (index) {
        cart.splice(index, 1);
        updateCart();
    };

    // Vaciar el carrito (sin onclick en HTML; lo enganchamos abajo con un eventListener)
    function clearCart() {
        cart = [];
        updateCart();
    }

    // Actualizar el carrito
    function updateCart() {
        const cartItems = document.getElementById("cartItems");
        const cartTotal = document.getElementById("cartTotal");
        const cartCount = document.getElementById("cartCount");
        cartItems.innerHTML = "";
        let total = 0;

        cart.forEach((item, index) => {
            cartItems.innerHTML += `
                <div class="cart-item">
                    <span>${item.name}</span>
                    <span>$${item.price}</span>
                    <!-- removeFromCart se llama dinámicamente, por eso sigue en window -->
                    <button class="btn btn-danger btn-sm" onclick="removeFromCart(${index})">Eliminar</button>
                </div>
            `;
            total += item.price;
        });

        cartTotal.textContent = `$${total.toFixed(2)}`;
        cartCount.textContent = cart.length;
    }

    // --------------------------
    // GENERAR MENÚS DINÁMICOS
    // --------------------------

    // Generar menú de pizzas
    function generatePizzaMenu() {
        const container = document.getElementById("pizzaMenu");
        if (!container) {
            console.error("No se encontró el contenedor de pizzas");
            return;
        }
        pizzas.forEach(pizza => {
            const card = `
                <div class="col-md-4 text-center">
                    <div class="card h-100 shadow hover-3d">
                        <div class="card-body">
                            <h3 class="card-title">${pizza.name}</h3>
                            <p class="card-text">${pizza.description}</p>
                            <p class="card-text">
                                Chica: $${pizza.prices.chica} | 
                                Mediana: $${pizza.prices.mediana} | 
                                Grande: $${pizza.prices.grande}
                            </p>
                            <!-- openSizeModal se llama dinámicamente con la pizza -->
                            <button class="btn btn-primary"
                                onclick="openSizeModal(${JSON.stringify(pizza).replace(/"/g, '&quot;')})">
                                Agregar al Carrito
                            </button>
                        </div>
                    </div>
                </div>
            `;
            container.innerHTML += card;
        });
    }

    // Generar menú genérico (hotdogs, hamburguesas, papas)
    function generateMenu(items, containerId) {
        const container = document.getElementById(containerId);
        if (!container) {
            console.error(`No se encontró el contenedor con ID: ${containerId}`);
            return;
        }
        items.forEach(item => {
            const card = `
                <div class="col-md-4 text-center">
                    <div class="card h-100 shadow hover-3d">
                        <div class="card-body">
                            <h3 class="card-title">${item.name}</h3>
                            <p class="card-text">${item.description}</p>
                            <p class="card-text">$${item.price}</p>
                            <!-- addToCart se llama dinámicamente con nombre y precio -->
                            <button class="btn btn-primary" 
                                onclick="addToCart('${item.name}', ${item.price})">
                                Agregar al Carrito
                            </button>
                        </div>
                    </div>
                </div>
            `;
            container.innerHTML += card;
        });
    }

    // -------------------------------
    // INICIALIZACIÓN DE LA APLICACIÓN
    // -------------------------------

    // Botón del carrito
    const cartButton = document.getElementById("carButton");
    cartButton.addEventListener("click", toggleCart);

    // Botón "Vaciar Carrito"
    const clearCartButton = document.getElementById("clearCartButton");
    clearCartButton.addEventListener("click", clearCart);

    // Botón "Confirmar" en el modal
    const confirmSizeButton = document.getElementById("confirmSizeButton");
    confirmSizeButton.addEventListener("click", confirmSize);

    // Generar menús
    generatePizzaMenu();
    generateMenu(hotdogs, "hotdogMenu");
    generateMenu(hamburguesas, "burgerMenu");
    generateMenu(papas, "potatoMenu");

    console.log('Archivo datosPizza.js cargado correctamente');
});