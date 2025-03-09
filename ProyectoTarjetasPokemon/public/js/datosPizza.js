
document.addEventListener("DOMContentLoaded", function () {
    filterProducts('all');
        loadCart();
    setupEventListeners();
    generatePizzaMenu();
    generateHotdogMenu();
    generateBurgerMenu();
    generatePotatoMenu();
    generateBebidaMenu(); 
    generateTacoMenu();    
    loadCart();
        
        let cart = [];
        const userId = localStorage.getItem('userId');
        let selectedPizza = null;
    
        const closeCartButton = document.getElementById("closeCart");
        closeCartButton.addEventListener("click", toggleCart);
    
    
    
        
        // ----------------------
        // FUNCIONES PRINCIPALES
        // ----------------------
    
        // Mostrar/ocultar el carrito
       function toggleCart() {
            const cartSidebar = document.getElementById("cartSidebar");
            if (!cartSidebar) {
                console.error('Cart sidebar not found');
                return;
            }
            cartSidebar.classList.toggle("active");
        }
    
       /**
    * Configura detectores de eventos para varios botones relacionados con el carrito de la compra en la aplicación.
    * Esta función inicializa los controladores de clics para:
    * - Botón del carrito de la compra: carga y alterna la visualización del carrito de la compra
    * - Botón Cerrar carrito de la compra: cierra el carrito de la compra
    * - Botón Vaciar carrito de la compra: vacía el carrito de la compra
    * - Botón Confirmar talla: confirma la talla seleccionada
    * 
    * Nota: Para el botón del carrito de la compra, la función elimina todos los detectores existentes
    * clonando y reemplazando el elemento del botón antes de agregar el nuevo detector.
    */
        function setupEventListeners() {
            // Cart button
            const cartButton = document.getElementById("carButton");
            if (cartButton) {
                // Remove previous listeners if any
                const newCartButton = cartButton.cloneNode(true);
                cartButton.parentNode.replaceChild(newCartButton, cartButton);
                
                // Add single click handler that both loads and toggles
                newCartButton.addEventListener("click", function() {
                    loadCart();
                    toggleCart();
                });
            }
    
            // Close cart button
            const closeCartButton = document.getElementById("closeCart");
            if (closeCartButton) {
                closeCartButton.addEventListener("click", toggleCart);
            }

            const checkoutButton = document.getElementById("checkoutButton");
            if (checkoutButton) {
                checkoutButton.addEventListener("click", handleCheckout);
            }
    
            // Clear cart button
            const clearCartButton = document.getElementById("clearCartButton");
            if (clearCartButton) {
                clearCartButton.addEventListener("click", clearCart);
            }
    
            // Confirm size button
            const confirmSizeButton = document.getElementById("confirmSizeButton");
            if (confirmSizeButton) {
                confirmSizeButton.addEventListener("click", confirmSize);
            }
    
            // Filtrar productos por categoria
            const categoryFilter = document.getElementById('categoryFilter');
        if (categoryFilter) {
            categoryFilter.addEventListener('change', (e) => {
                filterProducts(e.target.value);
            });
        }
    
        }

        async function handleCheckout() {
            const token = localStorage.getItem('token');
            
            if (!token) {
                alert('Por favor inicia sesión para realizar el pedido');
                return;
            }
        
            try {
                // Verify cart contents
                const response = await fetch('/api/v1/cart', {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                });
        
                if (!response.ok) {
                    throw new Error('Error al verificar el carrito');
                }
        
                const cartData = await response.json();
        
                if (!cartData.items || cartData.items.length === 0) {
                    alert('Tu carrito está vacío');
                    return;
                }
        
                // Redirect to checkout page
                window.location.href = '/checkout';
        
            } catch (error) {
                console.error('Error:', error);
                alert('Error al procesar el pedido');
            }
        }
    
        //no sirve esto
        /*function saveCart() {
            if (userId) {
                localStorage.setItem(`cart_${userId}`, JSON.stringify(cart));
            }
        }*/
    
    
      // Cargar el carrito de la compra recuperando los datos del servidor gracias a nuestra api
      //  y actualizando la IU en consecuencia 
        async function loadCart() {
            const token = localStorage.getItem('token');
            if (!token) {
                updateCartUI([]);
                return;
            }
        
            try {
                const response = await fetch('/api/v1/cart', {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                });
        
                if (!response.ok) {
                    throw new Error('Error al cargar el carrito');
                }
        
                const data = await response.json();
                console.log('Cart data:', data); // Para debugging
                updateCartUI(data.items, data.total);
            } catch (error) {
                console.error('Error loading cart:', error);
                updateCartUI([], 0);
            }
        }
    
    
    //FILTRADO DE PRODUCTOS
    async function filterProducts(category) {
        // Define mapping between category values and menu IDs
        const categoryToMenuId = {
            'all': 'all',
            'pizza': 'pizzaMenu',
            'hotdog': 'hotdogMenu',
            'hamburguesa': 'burgerMenu',
            'papas': 'potatoMenu',
            'bebidas': 'bebidaMenu',
            'tacos': 'tacoMenu'
        };
    
        // Get all menu sections
        const sections = document.querySelectorAll('section');
        
        // First hide all sections
        sections.forEach(section => {
            const menuContainer = section.querySelector('[id$="Menu"]');
            if (menuContainer) {
                section.style.display = 'none';
            }
        });
    
        if (category === 'all') {
            // Show all sections
            sections.forEach(section => {
                const menuContainer = section.querySelector('[id$="Menu"]');
                if (menuContainer) {
                    section.style.display = 'block';
                }
            });
            return;
        }
    
        // Get the corresponding menu ID for the selected category
        const menuId = categoryToMenuId[category];
        if (!menuId) {
            console.error('Invalid category:', category);
            return;
        }
    
        // Find and show the selected section
        const selectedSection = document.querySelector(`section:has(#${menuId})`);
        if (selectedSection) {
            selectedSection.style.display = 'block';
        } else {
            console.error(`Section not found for menu ID: ${menuId}`);
        }
    }
    
    
    
        // Actualizar la IU del carrito con los elementos y el total de precio proporcionados
    
        function updateCartUI(items, total) {
            const cartItems = document.getElementById("cartItems");
            const cartTotal = document.getElementById("cartTotal");
            const cartCount = document.getElementById("cartCount");
            
            if (!cartItems || !cartTotal || !cartCount) {
                console.error('Cart elements not found');
                return;
            }
        
            cartItems.innerHTML = "";
        
            if (Array.isArray(items) && items.length > 0) {
                items.forEach(item => {
                    const sizeName = item.size ? ` (${item.size.name})` : '';
                    
                    cartItems.innerHTML += `
                        <div class="cart-item">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <span class="fw-bold">${item.product.name}${sizeName}</span>
                                    <br>
                                    <small>Cantidad: ${item.quantity}</small>
                                </div>
                                <div class="text-end">
                                    <div>$${item.unit_price.toFixed(2)}</div>
                                    <div class="text-muted">Subtotal: $${item.subtotal.toFixed(2)}</div>
                                    <button class="btn btn-danger btn-sm mt-1" 
                                        onclick="removeFromCart(${item.id})">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                });
            } else {
                cartItems.innerHTML = '<p class="text-center my-3">Tu carrito está vacío</p>';
            }
        
            cartTotal.textContent = `$${total ? total.toFixed(2) : '0.00'}`;
            cartCount.textContent = items ? items.length : 0;
        }
    
    
    
        // Abrir el modal de selección de tamaño (usado en el HTML dinámico)
        window.openSizeModal = function(pizza) {
            console.log('Pizza data:', pizza); // Para debugging
            selectedPizza = pizza;
            const sizeSelect = document.getElementById('sizeSelect');
            const priceDisplay = document.getElementById('priceDisplay');
            
            // Limpiar opciones anteriores
            sizeSelect.innerHTML = '';
            
            // Agregar opciones de tamaño
            pizza.sizes.forEach(size => {
                const option = document.createElement('option');
                option.value = size.id;
                option.textContent = size.name;
                option.dataset.multiplier = size.price_multiplier;
                sizeSelect.appendChild(option);
            });
            
            // Mostrar precio inicial con el primer tamaño
            if (pizza.sizes.length > 0) {
                const initialMultiplier = parseFloat(pizza.sizes[0].price_multiplier);
                const initialPrice = pizza.basePrice * initialMultiplier;
                priceDisplay.textContent = `Precio: $${initialPrice.toFixed(2)}`;
            }
        
            // Agregar evento change al select
            sizeSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const multiplier = parseFloat(selectedOption.dataset.multiplier);
                const finalPrice = selectedPizza.basePrice * multiplier;
                priceDisplay.textContent = `Precio: $${finalPrice.toFixed(2)}`;
            });
            
            const modal = new bootstrap.Modal(document.getElementById('sizeModal'));
            modal.show();
        };
    
    
    
    
        // agregar al carrito el producto seleccionado con el tamaño seleccionado y permitiendo la seleccion del producto
        //si es que el usuario esta logueado gracias al token 
        async function addToCartWithSize(name, price, productId, sizeId) {
            const token = localStorage.getItem('token');
            if (!token) {
                alert('Por favor inicia sesión para agregar productos al carrito');
                return;
            }
        
            try {
                const response = await fetch('/api/v1/cart', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: 1,
                        unit_price: price,
                        pizza_size_id: sizeId
                    })
                });
        
                if (!response.ok) {
                    throw new Error('Error al agregar al carrito');
                }
        
                await loadCart(); // Recargar el carrito
            } catch (error) {
                console.error('Error:', error);
                alert('Error al agregar al carrito');
            }
        }
    
        
        // Confirmar el tamaño (sin onclick en HTML; lo enganchamos abajo con un eventListener)
        function confirmSize() {
            const token = localStorage.getItem('token');
            if (!token) {
                alert('Por favor inicia sesión para agregar productos al carrito');
                return;
            }
        
            const sizeSelect = document.getElementById('sizeSelect');
            const selectedOption = sizeSelect.options[sizeSelect.selectedIndex];
            const multiplier = parseFloat(selectedOption.dataset.multiplier);
            const finalPrice = selectedPizza.basePrice * multiplier;
            const sizeId = parseInt(selectedOption.value);
        
            // Llamar a addToCart con el tamaño seleccionado
            addToCartWithSize(selectedPizza.name, finalPrice, selectedPizza.id, sizeId);
            
            const modal = bootstrap.Modal.getInstance(document.getElementById('sizeModal'));
            modal.hide();
        }
    
    
        // agregar al carrito el producto seleccionado y permitiendo la seleccion del producto 
        // si es que el usuario esta logueado gracias al token
        window.addToCart = async function(name, price, productId) {
            const token = localStorage.getItem('token');
            if (!token) {
                alert('Por favor inicia sesión para agregar productos al carrito');
                return;
            }
        
            try {
                const response = await fetch('/api/v1/cart', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: 1,
                        unit_price: price
                    })
                });
        
                if (!response.ok) {
                    throw new Error('Error al agregar al carrito');
                }
        
                await loadCart(); // Recargar el carrito
            } catch (error) {
                console.error('Error:', error);
                alert('Error al agregar al carrito');
            }
        };
    
    
        // Eliminar del carrito (usado en el HTML dinámico)
        window.removeFromCart = async function(cartItemId) {
            const token = localStorage.getItem('token');
            if (!token) return;
        
            try {
                const response = await fetch(`/api/v1/cart/${cartItemId}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                });
        
                if (!response.ok) {
                    throw new Error('Error al eliminar del carrito');
                }
        
                await loadCart(); // Recargar el carrito
            } catch (error) {
                console.error('Error:', error);
                alert('Error al eliminar del carrito');
            }
        };
        
    
        // Vaciar el carrito (sin onclick en HTML; lo enganchamos abajo con un eventListener)
        async function clearCart() {
            const token = localStorage.getItem('token');
            if (!token) return;
        
            try {
                const response = await fetch('/api/v1/cart', {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                });
        
                if (!response.ok) {
                    throw new Error('Error al vaciar el carrito');
                }
        
                await loadCart(); // Recargar el carrito
            } catch (error) {
                console.error('Error:', error);
                alert('Error al vaciar el carrito');
            }
        }
    
        // --------------------------
        // GENERAR MENÚS DINÁMICOS
        // --------------------------
    
        // Función para obtener los productos desde la API por slug de categoría
        async function fetchProducts(categorySlug) {
            try {
                const url = categorySlug 
                    ? `/api/v1/products?category_slug=${categorySlug}`
                    : '/api/v1/products';
                    
                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });
        
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                const data = await response.json();
                return data.data;
            } catch (error) {
                console.error('Error:', error);
                return [];
            }
        }
    
    
        // Generar menú de pizzas
        async function generatePizzaMenu() {
            const container = document.getElementById("pizzaMenu");
            if (!container) return;
            
            const products = await fetchProducts('pizza');
            const sizes = await fetchSizes(); // Obtener los tamaños primero
            
            products.forEach(product => {
                const pizzaData = {
                    id: product.id,
                    name: product.name,
                    basePrice: product.price,
                    sizes: sizes
                };
            
                const card = `
                    <div class="col-md-4 text-center">
                        <div class="card h-100 shadow hover-3d">
                            <div class="card-body">
                                <h3 class="card-title">${product.name}</h3>
                                <p class="card-text">${product.description}</p>
                                <p class="card-text">Desde $${product.price}</p>
                                ${product.image_url ? 
                                    `<img src="${product.image_url}" class="card-img-top mb-3" alt="${product.name}">` 
                                    : ''}
                                <button class="btn btn-primary"
                                    onclick="openSizeModal(${JSON.stringify(pizzaData).replace(/"/g, "'")})">
                                    Elegir Tamaño
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                container.innerHTML += card;
            });
        }
    
    
           // esta funcion es para generar el menu de hotdogs asincronamente al iniciar la pagina 
        async function generateHotdogMenu() {
            const container = document.getElementById("hotdogMenu");
            if (!container) return;
            
            const products = await fetchProducts('hotdog');
            
            products.forEach(product => {
                const card = `
                    <div class="col-md-4 text-center">
                        <div class="card h-100 shadow hover-3d">
                            <div class="card-body">
                                <h3 class="card-title">${product.name}</h3>
                                <p class="card-text">${product.description}</p>
                                <p class="card-text">$${product.price}</p>
                                ${product.image_url ? 
                                    `<img src="${product.image_url}" class="card-img-top mb-3" alt="${product.name}">` 
                                    : ''}
                                <button class="btn btn-primary"
            onclick="addToCart('${product.name}', ${product.price}, ${product.id})"
        >
                                    Agregar al Carrito
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                container.innerHTML += card;
            });
        }
        
        // Generar menú de hamburguesas asincronamente al iniciar la pagina
        async function generateBurgerMenu() {
            const container = document.getElementById("burgerMenu");
            if (!container) return;
            
            const products = await fetchProducts('hamburguesa');
            
            products.forEach(product => {
                const card = `
                    <div class="col-md-4 text-center">
                        <div class="card h-100 shadow hover-3d">
                            <div class="card-body">
                                <h3 class="card-title">${product.name}</h3>
                                <p class="card-text">${product.description}</p>
                                <p class="card-text">$${product.price}</p>
                                ${product.image_url ? 
                                    `<img src="${product.image_url}" class="card-img-top mb-3" alt="${product.name}">` 
                                    : ''}
                                <button class="btn btn-primary"
            onclick="addToCart('${product.name}', ${product.price}, ${product.id})"
        >
                                    Agregar al Carrito
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                container.innerHTML += card;
            });
        }
        
        // Generar menú de papas asincronamente al iniciar la pagina
        async function generatePotatoMenu() {
            const container = document.getElementById("potatoMenu");
            if (!container) return;
            
            const products = await fetchProducts('papas');
            
            products.forEach(product => {
                const card = `
                    <div class="col-md-4 text-center">
                        <div class="card h-100 shadow hover-3d">
                            <div class="card-body">
                                <h3 class="card-title">${product.name}</h3>
                                <p class="card-text">${product.description}</p>
                                <p class="card-text">$${product.price}</p>
                                ${product.image_url ? 
                                    `<img src="${product.image_url}" class="card-img-top mb-3" alt="${product.name}">` 
                                    : ''}
                                <button class="btn btn-primary"
            onclick="addToCart('${product.name}', ${product.price}, ${product.id})"
        >
                                    Agregar al Carrito
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                container.innerHTML += card;
            });
        }
    
    
        // genera las bebidas asincronamente al iniciar la pagina
        async function generateBebidaMenu() {
            const container = document.getElementById("bebidaMenu");
            if (!container) return;
            
            const products = await fetchProducts('bebidas');
            
            products.forEach(product => {
                const card = `
                    <div class="col-md-4 text-center">
                        <div class="card h-100 shadow hover-3d">
                            <div class="card-body">
                                <h3 class="card-title">${product.name}</h3>
                                <p class="card-text">${product.description}</p>
                                <p class="card-text">$${product.price}</p>
                                ${product.image_url ? 
                                    `<img src="${product.image_url}" class="card-img-top mb-3" alt="${product.name}">` 
                                    : ''}
                                <button class="btn btn-primary"
            onclick="addToCart('${product.name}', ${product.price}, ${product.id})"
        >
                                    Agregar al Carrito
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                container.innerHTML += card;
            });
        }
    
    // Generar menú de tacos asincronamente al iniciar la pagina
    async function generateTacoMenu() {
        const container = document.getElementById("tacoMenu");
        if (!container) return;
        
        const products = await fetchProducts('tacos');
        
        products.forEach(product => {
            const card = `
                <div class="col-md-4 text-center">
                    <div class="card h-100 shadow hover-3d">
                        <div class="card-body">
                            <h3 class="card-title">${product.name}</h3>
                            <p class="card-text">${product.description}</p>
                            <p class="card-text">$${product.price}</p>
                            ${product.image_url ? 
                                `<img src="${product.image_url}" class="card-img-top mb-3" alt="${product.name}">` 
                                : ''}
                            <button class="btn btn-primary"
            onclick="addToCart('${product.name}', ${product.price}, ${product.id})"
        >
                                Agregar al Carrito
                            </button>
                        </div>
                    </div>
                </div>
            `;
            container.innerHTML += card;
        });
    }
    
        
    // Función para obtener los tamaños de las pizzas desde la API y asi modificar el precio de la pizza al seleccionar un tamaño
    async function fetchSizes() {
        try {
            const response = await fetch('/api/v1/sizes', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });
    
            if (!response.ok) {
                throw new Error('Error al obtener los tamaños');
            }
    
            const result = await response.json();
            console.log('Tamaños obtenidos:', result.sizes); // Para depuración
            return result.sizes;
        } catch (error) {
            console.error('Error:', error);
            return [];
        }
    }
    
        // -------------------------------
        // INICIALIZACIÓN DE LA APLICACIÓN
        // -------------------------------
    
        // Botón del carrito
        const cartButton = document.getElementById("carButton");
        if (cartButton) {
            cartButton.addEventListener("click", function() {
                loadCart();
                toggleCart();
            });
        }
    
        cartButton.addEventListener("click", toggleCart);
    
        // Botón "Vaciar Carrito"
        const clearCartButton = document.getElementById("clearCartButton");
        clearCartButton.addEventListener("click", clearCart);
    
        // Botón "Confirmar" en el modal
        const confirmSizeButton = document.getElementById("confirmSizeButton");
        confirmSizeButton.addEventListener("click", confirmSize);
    
        // Generar menús
    
    
        console.log('Archivo datosPizza.js cargado correctamente');
    });