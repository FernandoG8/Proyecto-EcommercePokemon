function openEditModal(sizeId) {
    axios.get(`/admin/sizes/${sizeId}/edit`)
        .then(response => {
            document.getElementById("edit-modal-body").innerHTML = response.data;

            // Show modal after loading content
            let editModal = new bootstrap.Modal(document.getElementById("editSizeModal"));
            editModal.show();

            // Populate product data inside the loaded form
            loadOrderData(sizeId);
        })
        .catch(error => console.error("Error loading edit modal:", error));
}

function loadOrderData(sizeId) {
    axios.get(`/api/v1/sizes/${sizeId}`)
        .then(response => {
            let size = response.data.size;

            document.getElementById("name").value = size.name;
            document.getElementById("price_multiplier").value = size.price_multiplier;

            // Set checkbox checked/unchecked based on is_active
            document.getElementById("is_active").checked = size.is_active === 1 || size.is_active === true;
        })
        .catch(error => console.error("Error loading size data:", error));
}

document.addEventListener("DOMContentLoaded", function () {
    // Add Product Modal
    document.getElementById("add-size-btn").addEventListener("click", function (event) {
        event.preventDefault();

        axios.get("/admin/sizes/create", { responseType: 'text' })
            .then(response => {
                document.getElementById("modal-body").innerHTML = response.data;

                // Show modal after loading content
                var createModal = new bootstrap.Modal(document.getElementById("createSizeModal"));
                createModal.show();
            })
            .catch(error => console.error("Error loading modal content:", error));
    });

    // Edit Product Modal (Event Delegation)
    document.addEventListener("click", function (event) {
        if (event.target.classList.contains("edit-size-btn")) {
            let productId = event.target.getAttribute("data-id");
            openEditModal(productId);
        }
    });
});
