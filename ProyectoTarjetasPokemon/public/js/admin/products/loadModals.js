function openEditModal(productId) {
    axios.get(`/admin/products/${productId}/edit`, { responseType: 'text' })
        .then(response => {
            document.getElementById("edit-modal-body").innerHTML = response.data;

            // Show modal after loading content
            let editModal = new bootstrap.Modal(document.getElementById("editProductModal"));
            editModal.show();

            // Populate product data inside the loaded form
            loadOrderData(productId);
        })
        .catch(error => console.error("Error loading edit modal:", error));
}

function loadOrderData(productId) {
    axios.get(`/api/v1/products/${productId}`)
        .then(response => {

            let product = response.data.product;

            document.getElementById("name").value = product.name;
            document.getElementById("price").value = product.price;
            document.getElementById("description").value = product.description;

            // Load categories dynamically with the selected one
            loadEditCategories(product.category_id);
        })
        .catch(error => console.error("Error loading product data:", error));
}

function loadEditCategories(selectedCategoryId) {
    axios.get('/api/v1/categories')
        .then(response => {

            let categories = Array.isArray(response.data) ? response.data : response.data.categories;
            let categorySelect = document.getElementById("category_id");
            categorySelect.innerHTML = ""; // Clear options

            categories.forEach(category => {
                let option = document.createElement("option");
                option.value = category.id;
                option.textContent = category.name;
                if (category.id === selectedCategoryId) {
                    option.selected = true;
                }
                categorySelect.appendChild(option);
            });
        })
        .catch(error => console.error("Error loading categories:", error));
}

function loadCategories(orderId) {
    axios.get('/api/v1/categories')
        .then(response => {
            let categories = response.data.categories;
            let categorySelect = document.getElementById("category_id");
            categorySelect.innerHTML = ""; // Clear existing options

            categories.forEach(category => {
                let option = document.createElement("option");
                option.value = category.id;
                option.textContent = category.name;
                categorySelect.appendChild(option);
            });
        })
        .catch(error => console.error("Error loading categories:", error));
}

document.addEventListener("DOMContentLoaded", function () {
    // Add Product Modal
    document.getElementById("add-product-btn").addEventListener("click", function (event) {
        event.preventDefault();

        axios.get("/admin/products/create", { responseType: 'text' })
            .then(response => {
                document.getElementById("modal-body").innerHTML = response.data;

                // Show modal after loading content
                var createModal = new bootstrap.Modal(document.getElementById("createProductModal"));
                createModal.show();

                // Load categories dynamically
                loadCategories();
            })
            .catch(error => console.error("Error loading modal content:", error));
    });

    // Edit Product Modal (Event Delegation)
    document.addEventListener("click", function (event) {
        if (event.target.classList.contains("edit-product-btn")) {
            let productId = event.target.getAttribute("data-id");
            openEditModal(productId);
        }
    });
});
