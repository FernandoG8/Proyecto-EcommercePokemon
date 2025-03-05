// Función para subir un producto con imagen
async function uploadProduct(name, description, price, stock, category_id, imageFile) {
    const formData = new FormData();
    formData.append('name', name);
    formData.append('description', description);
    formData.append('price', price);
    formData.append('stock', stock);
    formData.append('category_id', category_id);
    formData.append('is_active', 1);

    // Asegurarse de que el archivo se está adjuntando
    if (imageFile) {
        formData.append('image', imageFile);
    } else {
        showAlert('Por favor, selecciona una imagen.', 'warning');
        return;
    }

    try {
        localStorage.setItem('token', '34wb9FsAkPkoL0RQFFS8dFvSfKmXro4H76RGNA2G1dfb6fe4')
        const response = await fetch(`${API_BASE_URL}/products`, {
            method: 'POST',
            headers: {
                'Authorization': Bearer 34wb9FsAkPkoL0RQFFS8dFvSfKmXro4H76RGNA2G1dfb6fe4 // NO agregar 'Content-Type'
            },
            body: formData // Enviar como FormData para soportar archivos
        });

        const data = await response.json();
        
        if (!response.ok) {
            throw new Error(data.message || 'Error al subir el producto');
        }

        showAlert('Producto creado con éxito', 'success');
        return data;
    } catch (error) {
        showAlert(`Error al subir producto: ${error.message}`, 'danger');
        console.error(error);
        throw error;
    }
}

// Evento para manejar el envío del formulario de producto
document.addEventListener('DOMContentLoaded', () => {
    const productForm = document.getElementById('productForm');

    productForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const name = document.getElementById('productName').value;
        const description = document.getElementById('productDescription').value;
        const price = document.getElementById('productPrice').value;
        const stock = document.getElementById('productStock').value;
        const category_id = document.getElementById('productCategory').value;
        const imageFile = document.getElementById('productImage').files[0]; // Obtener el archivo

        try {
            await uploadProduct(name, description, price, stock, category_id, imageFile);
            productForm.reset();
        } catch (error) {
            console.error('Error al subir producto:', error);
        }
    });
});
