function openEditModal(categoryId) {
    axios.get(`/admin/categories/${categoryId}/edit`)
        .then(response => {
            document.getElementById("edit-modal-body").innerHTML = response.data;

            // Show modal after loading content
            let editModal = new bootstrap.Modal(document.getElementById("editCategoryModal"));
            editModal.show();

            // Populate product data inside the loaded form
            loadOrderData(categoryId);
        })
        .catch(error => console.error("Error loading edit modal:", error));
}

function loadOrderData(categoryId) {
    axios.get(`/api/v1/categories/${categoryId}`)
        .then(response => {
            let category = response.data.category;

            document.getElementById("name").value = category.name;
            document.getElementById("slug").value = category.price;
            document.getElementById("description").value = category.description;

            loadSlugs(category.id);
        })
        .catch(error => console.error("Error loading product data:", error));
}

function loadSlugs(selectedCategoryId) {
    axios.get('/api/v1/categories')
        .then(response => {
            let categories = Array.isArray(response.data) ? response.data : response.data.categories;
            let slugSelect = document.getElementById("slug");
            slugSelect.innerHTML = ""; // Clear options

            categories.forEach(category => {
                let option = document.createElement("option");
                option.value = category.id;
                option.textContent = category.slug;
                if (category.id === selectedCategoryId) {
                    option.selected = true;
                }
                slugSelect.appendChild(option);
            });
        })
        .catch(error => console.error("Error loading categories:", error));
}

document.addEventListener("DOMContentLoaded", function () {
    // Add Product Modal
    document.getElementById("add-category-btn").addEventListener("click", function (event) {
        event.preventDefault();

        axios.get("/admin/categories/create", { responseType: 'text' })
            .then(response => {
                document.getElementById("modal-body").innerHTML = response.data;

                // Show modal after loading content
                var createModal = new bootstrap.Modal(document.getElementById("createCategoryModal"));
                createModal.show();
            })
            .catch(error => console.error("Error loading modal content:", error));
    });

    // Edit Product Modal (Event Delegation)
    document.addEventListener("click", function (event) {
        if (event.target.classList.contains("edit-category-btn")) {
            let productId = event.target.getAttribute("data-id");
            openEditModal(productId);
        }
    });
});
