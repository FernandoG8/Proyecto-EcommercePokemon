const orderStatusTemplates = ["pending", "processing", "completed", "cancelled", "delivered"];
const paymentStatusTemplates = ["pending", "paid", "failed"];


// save current product id for edit modal
let singleOrderId = null;

function openEditModal(orderId) {
    singleOrderId = orderId;
    axios.get(`/admin/orders/${orderId}/edit`)
        .then(response => {
            document.getElementById("edit-modal-body").innerHTML = response.data;

            // Show modal after loading content
            let editModal = new bootstrap.Modal(document.getElementById("editOrderModal"));
            editModal.show();

            // Populate product data inside the loaded form
            loadOrderData(orderId);
        })
        .catch(error => console.error("Error loading edit modal:", error));
}

function loadOrderData(orderId) {
    axios.get(`/api/v1/orders/${orderId}`) // Correct API call for single order
        .then(response => {
            let order = response.data.order;

            document.getElementById("id").value = order.id;
            document.getElementById("order_number").value = order.order_number;
            document.getElementById("status").value = order.status;
            document.getElementById("payment_status").value = order.payment_status;

            // Populate the status dropdown with the order status as placeholder
            loadOrderStatus(order.status);

            // Populate the payment status dropdown with the current payment status as placeholder
            loadPaymentStatus(order.payment_status);
        })
        .catch(error => console.error("Error loading order data:", error));
}

function loadOrderStatus(currentStatus) {
    let statusSelect = document.getElementById("status");
    statusSelect.innerHTML = ""; // Clear existing options

    // Add the current status as the placeholder
    let placeholderOption = document.createElement("option");
    placeholderOption.value = "";
    placeholderOption.textContent = `Estado actual:  ${currentStatus}`; // Set current status as placeholder
    placeholderOption.disabled = true; // Make it non-selectable
    placeholderOption.selected = true; // Set as selected
    statusSelect.appendChild(placeholderOption);

    // Add predefined statuses from `orderStatus` as selectable options
    orderStatusTemplates.forEach(status => {
        let option = document.createElement("option");
        option.value = status;
        option.textContent = status;
        statusSelect.appendChild(option);
    });
}

function loadPaymentStatus(currentPaymentStatus) {
    let paymentStatusSelect = document.getElementById("payment_status");
    paymentStatusSelect.innerHTML = ""; // Clear existing options

    // Add the current payment status as the placeholder
    let placeholderOption = document.createElement("option");
    placeholderOption.value = "";
    placeholderOption.textContent = `Estado de pago actual:  ${currentPaymentStatus}`; // Set current payment status as placeholder
    placeholderOption.disabled = true; // Make it non-selectable
    placeholderOption.selected = true; // Set as selected
    paymentStatusSelect.appendChild(placeholderOption);

    // Add predefined statuses from `paymentStatus` as selectable options
    paymentStatusTemplates.forEach(status => {
        let option = document.createElement("option");
        option.value = status;
        option.textContent = status;
        paymentStatusSelect.appendChild(option);
    });
}
async function sendUpdate() {
    try {
        console.log('Updating order...');  // Log the update action for debugging

        // Get the authentication token
        const token = localStorage.getItem("token");
        if (!token) throw new Error("No authentication token found. Please log in.");

        // Get form values
        const orderId = document.getElementById("id").value; // Assuming you have an input for order ID
        const orderStatus = document.getElementById("status").value;
        const paymentStatus = document.getElementById("payment_status").value;

        // Array to store all update requests
        const updateRequests = [];

        // Update order status if it has changed
        if (orderStatus) {
            updateRequests.push(
                axios.put(`/api/v1/orders/${orderId}/status`, { status: orderStatus }, {
                    headers: {
                        "Authorization": `Bearer ${token}`,
                        "Content-Type": "application/json",
                    },
                })
            );
        }

        // Update payment status if it has changed
        if (paymentStatus) {
            updateRequests.push(
                axios.put(`/api/v1/orders/${orderId}/payment`, { payment_status: paymentStatus }, {
                    headers: {
                        "Authorization": `Bearer ${token}`,
                        "Content-Type": "application/json",
                    },
                })
            );
        }

        // Execute all update requests concurrently
        if (updateRequests.length > 0) {
            const responses = await Promise.all(updateRequests);

            // Log all responses
            responses.forEach((response, index) => {
                console.log(`Update ${index + 1} successful:`, response.data);
            });

            showAlert('Order updated successfully!', 'success');
        } else {
            showAlert('No changes detected.', 'info');
        }

        // Reload orders or update the UI
        fetchOrders();  // Assuming you have a function to reload orders
    } catch (error) {
        console.error('Error updating order:', error);
        showAlert('Error updating order. Please try again.', 'danger');
    }
}