const orderStatus = ["Preparando", "Enviando", "Entregado", "Cancelado"];
const paymentStatus = ["Pendiente", "Pagado", "Reembolsado"];


// save current product id for edit modal
let currentProductId = null;

function openEditModal(orderId) {
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
            document.getElementById("order_number").value = order.order_number;
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

document.addEventListener("DOMContentLoaded", function () {
    // Edit Order Modal (Event Delegation)
    document.addEventListener("click", function (event) {
        if (event.target.classList.contains("edit-order-btn")) {
            let orderId = event.target.getAttribute("data-id");
            openEditModal(orderId);
        }
    });
});
