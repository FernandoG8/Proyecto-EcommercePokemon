async function uploadProduct(name, description, price, stock, category_id, imageFile, is_active) {
    try {
        const token = localStorage.getItem("token");
        if (!token) throw new Error("No authentication token found. Please log in.");

        const formData = new FormData();
        formData.append("name", name);
        formData.append("description", description);
        formData.append("price", parseFloat(price).toFixed(2));
        formData.append("stock", parseInt(stock, 10));
        formData.append("category_id", parseInt(category_id, 10));

        if (imageFile instanceof File) {
            formData.append("image", imageFile);
        }

        formData.append("is_active", is_active ? 1 : 0);

        const response = await axios.post("/api/v1/products", formData, {
            headers: {
                "Authorization": `Bearer ${token}`,
                "Content-Type": "multipart/form-data",
            },
        });

        showAlert("Producto creado con éxito", "success");
        return response.data;
    } catch (error) {
        console.error("Error al subir producto:", error.response?.data || error.message);
        showAlert(`Error al subir producto: ${error.response?.data?.message || error.message}`, "danger");
        throw error;
    }
}

function openEditModal(productId) {
    axios.get(`/admin/products/${productId}/edit`, { responseType: 'text' })
        .then(response => {
            document.getElementById("edit-modal-body").innerHTML = response.data;

            // Show modal after loading content
            let editModal = new bootstrap.Modal(document.getElementById("editProductModal"));
            editModal.show();

            // Populate product data inside the loaded form
            loadCategoryData(productId);
        })
        .catch(error => console.error("Error loading edit modal:", error));
}

function loadCategoryData(productId) {
    axios.get(`/api/v1/products/${productId}`)
        .then(response => {

            let product = response.data.product;

            document.getElementById("name").value = product.name;
            document.getElementById("price").value = product.price;
            document.getElementById("description").value = product.description;
            document.getElementById("stock").value = product.stock;
            document.getElementById("is_active").checked = product.is_active;

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


// DOM loaded event
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("add-product-btn").addEventListener("click", function (event) {
        event.preventDefault();

        axios.get("/admin/products/create", { responseType: 'text' })
            .then(response => {
                document.getElementById("modal-body").innerHTML = response.data;

                var createModal = new bootstrap.Modal(document.getElementById("createProductModal"));
                createModal.show();

                // Cargar categorías dinámicamente
                loadCategories();

                // Agregar evento al formulario después de que se cargue en el modal
                setTimeout(() => {
                    const productForm = document.getElementById('create-product-form');
                    if (productForm) {
                        productForm.addEventListener('submit', async (e) => {
                            e.preventDefault();

                            const name = document.getElementById('name').value;
                            const description = document.getElementById('description').value;
                            const price = document.getElementById('price').value;
                            const stock = document.getElementById('stock').value;
                            const category_id = document.getElementById('category_id').value;
                            const imageFile = document.getElementById('image').files[0];
                            const is_active = document.getElementById('is_active').checked ? 1 : 0;

                            try {
                                await uploadProduct(name, description, price, stock, category_id, imageFile, is_active);
                                fetchProducts();
                            } catch (error) {
                                console.error('Error al subir producto:', error);
                            }
                        });
                    }
                }, 100); // Pequeño retraso para asegurarse de que el DOM ha sido actualizado
            })
            .catch(error => console.error("Error loading modal content:", error));
    });

});

