console.log('Edit modal script loaded!');  // Log the script load for debugging

let singleProductId = null;

function openEditModal(productId) {
    singleProductId = productId;  // Set the global variable for the product ID
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
        .then(response => {  // Set the global variable for the product ID
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

async function sendUpdate() {
    try {
        console.log('Updating product...');  // Log the update action for debugging

        // Get the authentication token
        const token = localStorage.getItem("token");
        if (!token) throw new Error("No authentication token found. Please log in.");

        // Get form values
        const name = document.getElementById("name").value;
        const description = document.getElementById("description").value;
        const price = document.getElementById("price").value;
        const stock = document.getElementById("stock").value;
        const category_id = document.getElementById("category_id").value;
        const is_active = document.getElementById("is_active").checked;

        // Create FormData object and append fields
        const formData = new FormData();

        formData.append("name", name);
        formData.append("description", description);
        formData.append("price", parseFloat(price).toFixed(2));  // Ensure price is formatted correctly
        formData.append("stock", parseInt(stock, 10));  // Ensure stock is an integer
        formData.append("category_id", parseInt(category_id, 10));  // Ensure category_id is an integer

        // Append is_active value
        formData.append("is_active", is_active ? 1 : 0);

        for (let [key, value] of formData.entries()) {
            console.log(key, value);
        }
        // Send the request to update the product
        const response = await axios.put(`/api/v1/products/${singleProductId}`, formData, {
            headers: {
                "Authorization": `Bearer ${token}`,
                "Content-Type": "application/json",  // Use multipart/form-data for FormData
            },
        });

        console.log('Product updated successfully:', response.data);
        showAlert("Product updated successfully!", "success");
        fetchProducts(page = 1);  // Reload the products
        // You can redirect the user or update the UI here
    } catch (error) {
        console.error('Error updating product:', error);
        showAlert('Error updating product. Please try again.', 'danger');
    }
}