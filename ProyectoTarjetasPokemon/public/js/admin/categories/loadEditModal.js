let singleCategoryId = null;
console.log('Edit modal script loaded!');

function openEditModal(categoryId) {
    singleSizeId = categoryId;
    axios.get(`/admin/categories/${categoryId}/edit`)
        .then(response => {
            document.getElementById("edit-modal-body").innerHTML = response.data;

            // Show modal after loading content
            let editModal = new bootstrap.Modal(document.getElementById("editCategoryModal"));
            editModal.show();

            // Populate product data inside the loaded form
            loadCategoryData(categoryId);
        })
        .catch(error => console.error("Error loading edit modal:", error));
}

function loadCategoryData(categoryId) {
    axios.get(`/api/v1/categories/${categoryId}`)
        .then(response => {
            let category = response.data.category;

            document.getElementById("name").value = category.name;
            document.getElementById("description").value = category.description;

        })
        .catch(error => console.error("Error loading product data:", error));
}

async function sendUpdate() {
    try {
        console.log('Updating category...');  
        
        // Ensure categoryId is set
        if (!singleSizeId) {
            throw new Error("Category ID is not set.");
        }
        console.log("Updating category ID:", singleSizeId);

        // Get the authentication token
        const token = localStorage.getItem("token");
        if (!token) throw new Error("No authentication token found. Please log in.");

        // Get form values
        const name = document.getElementById("name").value;
        const description = document.getElementById("description").value;

        // Create FormData object and append fields
        const formData = new FormData();
        formData.append("name", name);
        formData.append("description", description);

        // Send the request to update the category
        const response = await axios.put(`/api/v1/categories/${singleSizeId}`, formData, {
            headers: {
                "Authorization": `Bearer ${token}`,
                "Content-Type": "application/json",
            },
            timeout: 5000, // Optional timeout to prevent request from hanging
        });

        console.log("Category updated successfully:", response.data);
        showAlert("Category updated successfully!", "success");
        fetchCategories(); // Refresh category list
    } catch (error) {
        console.error('Error updating category:', error);
        showAlert(`Error updating category: ${error.response?.data?.message || error.message}`, 'danger');
    }
}
