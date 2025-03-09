let singleSizeId = null;

function openEditModal(sizeId) {
    singleSizeId = sizeId;
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


async function sendUpdate() {
    try {
        console.log('Updating size...');  
        
        // Ensure categoryId is set
        if (!singleSizeId) {
            throw new Error("Size ID is not set.");
        }
        console.log("Updating size ID:", singleSizeId);

        // Get the authentication token
        const token = localStorage.getItem("token");
        if (!token) throw new Error("No authentication token found. Please log in.");

        // Get form values
        const name = document.getElementById("name").value;
        const price_multiplier = document.getElementById("price_multiplier").value;
        const is_active = document.getElementById("is_active").checked ? 1 : 0;

        // Create FormData object and append fields
        const formData = new FormData();
        formData.append("name", name);
        formData.append("price_multiplier", price_multiplier);
        formData.append("is_active", is_active);

        // Send the request to update the category
        const response = await axios.put(`/api/v1/sizes/${singleSizeId}`, formData, {
            headers: {
                "Authorization": `Bearer ${token}`,
                "Content-Type": "application/json",
            },
            timeout: 2000, // Optional timeout to prevent request from hanging
        });

        console.log('Size updated successfully:', response.data);
        showAlert("Size updated successfully!", "success");
        fetchPizzaSizes(); // Refresh category list
    } catch (error) {
        console.error('Error updating category:', error);
        showAlert(`Error updating category: ${error.response?.data?.message || error.message}`, 'danger');
    }
}