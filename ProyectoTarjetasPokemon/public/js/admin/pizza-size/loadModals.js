async function uploadSize(name, price_multiplier, is_active) {
    try {
        const token = localStorage.getItem("token");
        if (!token) throw new Error("No authentication token found. Please log in.");

        const formData = new FormData();
        formData.append("name", name);
        formData.append("price_multiplier", price_multiplier);
        formData.append("is_active", is_active ? 1 : 0);

        const response = await axios.post("/api/v1/sizes", formData, {
            headers: {
                "Authorization": `Bearer ${token}`,
                "Content-Type": "application/json",
            },
        });

        showAlert("Tamaño creada con éxito", "success");
        return response.data;
    } catch (error) {
        console.error("Error al subir producto:", error.response?.data || error.message);
        showAlert(`Error al subir producto: ${error.response?.data?.message || error.message}`, "danger");
        throw error;
    }
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

                setTimeout(() => {
                    const productForm = document.getElementById('create-product-form');
                    if (productForm) {
                        productForm.addEventListener('submit', async (e) => {
                            e.preventDefault();

                            const name = document.getElementById('name').value;
                            const price_multiplier = document.getElementById('price_multiplier').value;
                            const is_active = document.getElementById('is_active').checked ? 1 : 0;

                            try {
                                await uploadSize(name, price_multiplier, is_active);
                                fetchPizzaSizes();
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
