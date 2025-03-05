function fetchOrders() {
    axios.get('/api/orders')
        .then(response => {
            const orders = response.data.data; // Access categories under a key
            const tableBody = document.querySelector('#orders-table tbody');
            tableBody.innerHTML = ''; // Clear existing rows

            // Populate the table with categories
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
                            <a href="/admin/categories/${order.id}/edit" class="btn btn-sm btn-warning">Edit</a>
                            <button onclick="deleteCategory(${order.id})" class="btn btn-sm btn-danger">Delete</button>
                        </td>
                    </tr>
                `;
                tableBody.innerHTML += row;
            });
        })
        .catch(error => console.error('Error fetching categories:', error));
}

// Function to delete a category
function deleteOrder(orderId) {
    if (confirm('Are you sure you want to delete this order?')) {
        axios.delete(`/api/v1/orders/${categoryId}`)
            .then(response => {
                alert('Order deleted successfully!');
                fetchOrders(); // Refresh the table
            })
            .catch(error => console.error(error));
    }
}

// Initialize the categories table when the page loads
document.addEventListener('DOMContentLoaded', function () {
    fetchOrders();
});