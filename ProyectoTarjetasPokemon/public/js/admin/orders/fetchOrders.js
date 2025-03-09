async function fetchOrders() {
    const token = localStorage.getItem('token'); // Retrieve the token from localStorage

    if (!token) {
        console.error('No token found. Please log in.');
        return;
    }

    try {
        const response = await axios.get('/api/v1/orders', {
            headers: {
                'Authorization': `Bearer ${token}` // Include the token in the request headers
            }
        });

        const orders = response.data.data; // Access orders under a key
        const tableBody = document.querySelector('#orders-table tbody');
        tableBody.innerHTML = ''; // Clear existing rows

        // Populate the table with orders
        orders.forEach(order => {
            const row = `
                <tr>
                    <td>${order.id}</td>
                    <td>${order.order_number}</td>
                    <td>${order.total_amount}</td>
                    <td>${order.status}</td>
                    <td>${order.payment_method}</td>
                    <td>${order.payment_status}</td>
                    <td>${order.delivery_address}</td>
                    <td>${order.contact_phone}</td>
                    <td>${order.notes}</td>
                    <td>${order.delivered_at}</td>
                    <td>
                        <button onclick="openEditModal(${order.id})" class="btn btn-sm btn-warning">Edit</button>
                    </td>
                </tr>
            `;
            tableBody.innerHTML += row;
        });
    } catch (error) {
        console.error('Error fetching orders:', error);

        // Handle unauthorized access (e.g., token expired or invalid)
        if (error.response && error.response.status === 401) {
            console.log('Unauthorized: Please log in again.', 'error');
            localStorage.removeItem('token'); // Clear the invalid token
            localStorage.removeItem('user'); // Clear the user data
            // Redirect to the login page or update the UI
            updateUserInterface();
        } else {
            console.log(`Error fetching orders: ${error.message}`, 'error');
        }
    }
}

// Function to delete a category
function deleteOrder(orderId) {
    if (confirm('Are you sure you want to delete this order?')) {
        axios.delete(`/api/v1/orders/${orderId}`)
            .then(response => {
                showAlert('Order deleted successfully!', 'success');
                fetchOrders(); // Refresh the table
            })
            .catch(error => console.error(error));
    }
}

// Initialize the orders table when the page loads
document.addEventListener('DOMContentLoaded', function () {
    fetchOrders();
});