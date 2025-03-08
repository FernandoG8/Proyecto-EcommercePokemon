document.addEventListener('DOMContentLoaded', async function() {
    const token = localStorage.getItem('token');
    
    if (!token) {
        Swal.fire({
            title: 'Error',
            text: 'Sesión no válida. Por favor, inicie sesión nuevamente.',
            icon: 'error',
            confirmButtonText: 'Aceptar'
        }).then(() => {
            window.location.href = '/login';
        });
        return;
    }

    try {
        const response = await fetch('/api/v1/user', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error('Sesión no válida');
        }

        const userData = await response.json();
        await loadCheckoutData(userData.id);
        setupEventListeners();

    } catch (error) {
        localStorage.removeItem('token');
        window.location.href = '/login';
    }
});

async function loadCheckoutData(userId) {
    try {
        const token = localStorage.getItem('token');
        
        // First get user data
        const userResponse = await fetch('/api/v1/user', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        if (!userResponse.ok) {
            throw new Error('Error cargando datos del usuario');
        }

        const userData = await userResponse.json();
        
        // Then fetch cart data
        const cartResponse = await fetch('/api/v1/cart', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        if (!cartResponse.ok) {
            throw new Error('Error cargando el carrito');
        }
        
        const cartData = await cartResponse.json();
        
        if (!cartData.items || cartData.items.length === 0) {
            alert('Tu carrito está vacío');
            window.location.href = '/Menu';
            return;
        }

        displayCartItems(cartData.items);
        updateTotal(cartData.total);
        populateUserData(userData);

    } catch (error) {
        console.error('Error:', error);
        alert('Error cargando los datos: ' + error.message);
        window.location.href = '/Menu';
    }
}


function displayCartItems(items) {
    const cartItemsList = document.getElementById('cartItemsList');
    cartItemsList.innerHTML = '';

    items.forEach(item => {
        const row = document.createElement('tr');
        // Crear el nombre del producto con el tamaño si existe
        const productName = item.product.name + (item.size ? ` - ${item.size.name}` : '');
        
        row.innerHTML = `
            <td>${productName}</td>
            <td>${item.quantity}</td>
            <td>$${parseFloat(item.unit_price).toFixed(2)}</td>
            <td>$${item.subtotal.toFixed(2)}</td>
        `;
        cartItemsList.appendChild(row);
    });
}

function updateTotal(total) {
    const cartTotal = document.getElementById('cartTotal');
    if (cartTotal) {
        cartTotal.textContent = `$${parseFloat(total).toFixed(2)}`;
    }
}

function populateUserData(userData) {
    document.getElementById('user_id').value = userData.id;
    document.getElementById('contact_phone').value = userData.phone || '';
    // Pre-fill delivery address if user has one saved
    if (userData.address) {
        document.getElementById('delivery_address').value = userData.address;
    }
}

function setupEventListeners() {
    const checkoutForm = document.getElementById('checkoutForm');
    const paymentMethod = document.getElementById('payment_method');

    paymentMethod.addEventListener('change', mostrarFormularioPago);
    checkoutForm.addEventListener('submit', handleSubmit);
}

function mostrarFormularioPago() {
    const metodo = document.getElementById("payment_method").value;
    const formTarjeta = document.getElementById("formTarjeta");
    const mensajeEfectivo = document.getElementById("mensajeEfectivo");

    // Check if elements exist before accessing classList
    if (formTarjeta && mensajeEfectivo) {
        if (metodo === "tarjeta") {
            formTarjeta.classList.remove("d-none");
            mensajeEfectivo.classList.add("d-none");
        } else if (metodo === "efectivo") {
            formTarjeta.classList.add("d-none");
            mensajeEfectivo.classList.remove("d-none");
        }
    }
}

async function handleSubmit(e) {
    e.preventDefault();
    
    // Form validation
    const form = e.target;
    if (!form.checkValidity()) {
        e.stopPropagation();
        form.classList.add('was-validated');
        return;
    }

    const loading = showLoadingSpinner();
    const token = localStorage.getItem('token');

    try {
        // Get form values
        const paymentMethod = document.getElementById('payment_method').value;
        const deliveryAddress = document.getElementById('delivery_address').value.trim();
        const contactPhone = document.getElementById('contact_phone').value.trim();
        const notes = document.getElementById('notes')?.value.trim() || '';

        // Validate required fields
        if (!paymentMethod || !deliveryAddress || !contactPhone) {
            throw new Error('Por favor complete todos los campos requeridos');
        }

        // Validate payment method value matches backend expectations
        if (!['efectivo', 'tarjeta'].includes(paymentMethod)) {
            throw new Error('Método de pago inválido');
        }

        // Prepare order data
        const orderData = {
            payment_method: paymentMethod,
            delivery_address: deliveryAddress,
            contact_phone: contactPhone,
            notes: notes
        };

        console.log('Sending order data:', orderData); // Debug log

        // Submit order
        const orderResponse = await fetch('/api/v1/orders', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`,
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(orderData)
        });

        const responseData = await orderResponse.json();
        console.log('Server response:', responseData); // Debug log

        if (!orderResponse.ok) {
            throw new Error(responseData.error || responseData.message || 'Error al procesar el pedido');
        }

        // Clear cart after successful order
        await clearCart(token);
        
        hideLoadingSpinner(loading);

        await Swal.fire({
            title: '¡Pedido Realizado!',
            text: `Su número de orden es: ${responseData.order.order_number}`,
            icon: 'success',
            confirmButtonText: 'Aceptar'
        });

        window.location.href = '/Menu';

    } catch (error) {
        hideLoadingSpinner(loading);
        console.error('Error details:', error);
        
        await Swal.fire({
            title: 'Error',
            text: error.message || 'Error al procesar el pedido',
            icon: 'error',
            confirmButtonText: 'Aceptar'
        });
    }
}


async function getCartItems(token) {  // Asegúrate de recibir el parámetro `token`
    try {
        const response = await fetch('/api/v1/cart', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error('Error al obtener items del carrito');
        }

        const cartData = await response.json();
        return cartData.items;
    } catch (error) {
        console.error('Error getting cart items:', error);
        throw error;
    }
}

async function clearCart(token) {
    const response = await fetch('/api/v1/cart', {
        method: 'DELETE',
        headers: {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    });

    if (!response.ok) {
        throw new Error('Error al limpiar el carrito');
    }
}




function showLoadingSpinner() {
    const spinner = document.createElement('div');
    spinner.innerHTML = `
        <div class="position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center bg-dark bg-opacity-50" style="z-index: 1050;">
            <div class="spinner-border text-light" role="status">
                <span class="visually-hidden">Procesando...</span>
            </div>
        </div>
    `;
    document.body.appendChild(spinner);
    return spinner;
}

function hideLoadingSpinner(spinner) {
    spinner.remove();
}

function showSuccessMessage(orderNumber) {
    Swal.fire({
        title: '¡Pedido Realizado!',
        text: `Su número de orden es: ${orderNumber}`,
        icon: 'success',
        confirmButtonText: 'Aceptar'
    }).then((result) => {
        window.location.href = '/menu';
    });
}