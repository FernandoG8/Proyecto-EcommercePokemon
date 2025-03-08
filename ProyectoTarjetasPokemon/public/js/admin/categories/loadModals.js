async function uploadSize(name, description) {
    try {
        const token = localStorage.getItem("token");
        if (!token) throw new Error("No authentication token found. Please log in.");

        const formData = new FormData();
        formData.append("name", name);
        formData.append("description", description);

        const response = await axios.post("/api/v1/categories", formData, {
            headers: {
                "Authorization": `Bearer ${token}`,
                "Content-Type": "multipart/form-data",
            },
        });

        showAlert("Categoria creada con éxito", "success");
        return response.data;
    } catch (error) {
        console.error("Error al subir producto:", error.response?.data || error.message);
        showAlert(`Error al subir producto: ${error.response?.data?.message || error.message}`, "danger");
        throw error;
    }
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

                setTimeout(() => {
                    const categoryForm = document.getElementById('create-category-form');
                    if (categoryForm) {
                        categoryForm.addEventListener('submit', async (e) => {
                            e.preventDefault();
                            const name = document.getElementById('name').value;
                            const description = document.getElementById('description').value;

                            try {
                                await uploadSize(name, description);
                                fetchCategories();
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
