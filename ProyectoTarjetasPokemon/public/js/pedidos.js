document.addEventListener('DOMContentLoaded', function() {
    loadOrders();
    setupFilters();
});

async function loadOrders() {
    const token = localStorage.getItem('token');
    if (!token) {
        window.location.href = '/login';
        return;
    }

    try {
        document.querySelector('.spinner-border').style.display = 'block';
        
        const response = await fetch('/api/v1/orders', {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const result = await response.json();
        
        if (!response.ok) {
            throw new Error(result.message || 'Error al cargar pedidos');
        }

        document.querySelector('.spinner-border').style.display = 'none';
        
        console.log('Pedidos recibidos:', result); // Para debug
        displayOrders(result.data);

    } catch (error) {
        console.error('Error detallado:', error);
        showError('No se pudieron cargar los pedidos: ' + error.message);
        document.querySelector('.spinner-border').style.display = 'none';
    }
}


function displayOrders(orders) {
    const ordersList = document.getElementById('ordersList');
    const template = document.getElementById('orderTemplate');
    ordersList.innerHTML = '';

    if (!orders || orders.length === 0) {
        ordersList.innerHTML = `
            <div class="col-12 text-center">
                <p class="text-muted">No hay pedidos para mostrar</p>
            </div>`;
        return;
    }

    orders.forEach(order => {
        const orderElement = template.content.cloneNode(true);
        
        orderElement.querySelector('.order-number').textContent = order.order_number;
        orderElement.querySelector('.order-status').textContent = getStatusText(order.status);
        orderElement.querySelector('.order-status').classList.add(getStatusClass(order.status));
        orderElement.querySelector('.order-total').textContent = `$${parseFloat(order.total_amount).toFixed(2)}`;
        orderElement.querySelector('.order-date').textContent = new Date(order.created_at).toLocaleDateString();
        
        const itemsContainer = orderElement.querySelector('.order-items');
        if (order.items && order.items.length > 0) {
            order.items.forEach(item => {
                const itemElement = document.createElement('div');
                itemElement.classList.add('mb-2');
                itemElement.innerHTML = `
                    <div class="d-flex justify-content-between">
                        <span>${item.quantity}x ${item.product_name}</span>
                        <span>$${(item.unit_price * item.quantity).toFixed(2)}</span>
                    </div>
                    ${item.size_name ? `<small class="text-muted">Tamaño: ${item.size_name}</small>` : ''}
                `;
                itemsContainer.appendChild(itemElement);
            });
        }

        ordersList.appendChild(orderElement);
    });
}

function getStatusText(status) {
    const statusMap = {
        'pending': 'Pendiente',
        'processing': 'En proceso',
        'completed': 'Completado',
        'cancelled': 'Cancelado',
        'delivered': 'Entregado'
    };
    return statusMap[status] || status;
}

function getStatusClass(status) {
    const classMap = {
        'pending': 'bg-warning',     // Amarillo
        'processing': 'bg-info',     // Azul claro
        'completed': 'bg-success',   // Verde
        'cancelled': 'bg-danger',    // Rojo
        'delivered': 'bg-primary'    // Azul
    };
    return classMap[status] || 'bg-secondary';
}

function setupFilters() {
    const statusFilter = document.getElementById('orderStatusFilter');
    const dateFilter = document.getElementById('orderDateFilter');

    statusFilter.addEventListener('change', filterOrders);
    dateFilter.addEventListener('change', filterOrders);
}

async function filterOrders() {
    const status = document.getElementById('orderStatusFilter').value;
    const date = document.getElementById('orderDateFilter').value;

    const token = localStorage.getItem('token');
    if (!token) return;

    try {
        document.querySelector('.spinner-border').style.display = 'block';
        
        // Construir URL con los filtros
        let url = '/api/v1/orders';
        const params = new URLSearchParams();
        
        if (status && status !== 'all') {
            params.append('status', status);
        }
        if (date) {
            params.append('date', date);
        }

        // Agregar los parámetros a la URL si existen
        if (params.toString()) {
            url += '?' + params.toString();
        }

        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) {
            throw new Error('Error al filtrar los pedidos');
        }

        const result = await response.json();
        displayOrders(result.data);

    } catch (error) {
        console.error('Error:', error);
        showError('Error al filtrar los pedidos: ' + error.message);
    } finally {
        document.querySelector('.spinner-border').style.display = 'none';
    }
}


function showError(message) {
    const alertElement = document.getElementById('alertMessage');
    alertElement.textContent = message;
    alertElement.style.display = 'block';
    
    // Ocultar después de 5 segundos
    setTimeout(() => {
        alertElement.style.display = 'none';
    }, 5000);
}