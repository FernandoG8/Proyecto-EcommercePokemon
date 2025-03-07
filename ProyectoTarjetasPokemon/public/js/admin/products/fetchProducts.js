// Función para subir un producto con imagen
// async function uploadProduct(name, slug, description, price, stock, category_id, imageFile, is_active) {
//     try {
//         // Retrieve the token from localStorage
//         const token = localStorage.getItem('token');
//         if (!token) {
//             throw new Error('No authentication token found. Please log in.');
//         }

//         // Prepare the payload
//         const payload = {
//             name,
//             slug,
//             description,
//             price: parseFloat(price), // Ensure price is a number
//             stock: parseInt(stock, 10), // Ensure stock is an integer
//             category_id: parseInt(category_id, 10), // Ensure category_id is an integer
//             is_active: Boolean(is_active), // Ensure is_active is a boolean
//         };

//         // Handle image upload (if provided)
//         if (imageFile) {
//             // Convert the image file to a base64 string
//             const base64Image = await fileToBase64(imageFile);
//             payload.image = base64Image;
//         }

//         // Send the request to create a product
//         const response = await fetch("http://127.0.0.1:8000/api/v1/products", {
//             method: 'POST',
//             headers: {
//                 'Authorization': `Bearer ${token}`,
//                 'Content-Type': 'application/json', // Send as JSON
//             },
//             body: JSON.stringify(payload), // Convert payload to JSON
//         });

//         // Parse the response
//         const data = await response.json();

//         // Check if the response is not OK
//         if (!response.ok) {
//             throw new Error(data.message || 'Error al subir el producto');
//         }

//         // Show success message
//         showAlert('Producto creado con éxito', 'success');
//         return data;
//     } catch (error) {
//         // Show error message
//         alert(`Error al subir producto: ${error.message}`, 'danger');
//         console.error(error);
//         throw error;
//     }
// }

// // Helper function to convert a file to a base64 string
// function fileToBase64(file) {
//     return new Promise((resolve, reject) => {
//         const reader = new FileReader();
//         reader.readAsDataURL(file);
//         reader.onload = () => resolve(reader.result.split(',')[1]); // Remove the data URL prefix
//         reader.onerror = (error) => reject(error);
//     });
// }


// Fetch all products and populate the table
function fetchProducts() {
    axios.get('/api/v1/products')
        .then(response => {
            const products = response.data.data;
            const tableBody = document.querySelector('#products-table tbody')   ;
            tableBody.innerHTML = ' ';  

            products.forEach(product => {
                const row = `
                    <tr>
                        <td>${product.id}</td>
                        <td>${product.name}</td>
                        <td>${product.category.name}</td>
                        <td>$${product.price}</td>
                        <td>
                            <button onclick="openEditModal(${product.id})" class="btn btn-sm btn-warning">Edit</button>
                            <button onclick="deleteProduct(${product.id})" class="btn btn-sm btn-danger">Delete</button>
                        </td>
                    </tr>
                `;
                tableBody.innerHTML += row;
            });
        })
        .catch(error => console.error(error));
}

// Create a new product
// document.getElementById('create-product-form')?.addEventListener('submit', function (e) {
//     e.preventDefault();

//     // Gather form data
//     const formData = {
//         name: document.getElementById('name').value,
//         category_id: document.getElementById('category_id').value,
//         price: parseFloat(document.getElementById('price').value),
//         description: document.getElementById('description').value,
//         image: document.getElementById('image').files[0], // Handle file upload
//     };

//     // Send data to the API
//     axios.post('/api/v1/products', formData, {
//         headers: {
//             'Content-Type': 'multipart/form-data', // Required for file uploads
//         },
//     })
//     .then(response => {
//         alert('Product created successfully!');
//         window.location.href = '/admin/products'; // Redirect to the products list
//     })
//     .catch(error => {
//         console.error('Error creating product:', error);
//         alert('Failed to create product. Please check the console for details.');
//     });
// });

// // Edit a product
// document.getElementById('edit-product-form')?.addEventListener('submit', function (e) {
//     e.preventDefault();

//     const productId = window.location.pathname.split('/').pop();
//     const formData = new FormData(this);
//     axios.put(`/api/v1/products/${productId}`, formData, {
//         headers: { 'Content-Type': 'multipart/form-data' }
//     })
//     .then(response => {
//         alert('Product updated successfully!');
//         window.location.href = '/admin/products';
//     })
//     .catch(error => console.error(error));
// });

// Delete a product
function deleteProduct(productId) {
    if (confirm('Are you sure you want to delete this product?')) {
        axios.delete(`/api/v1/products/${productId}`)
            .then(response => {
                alert('Product deleted successfully!');
                fetchProducts(); // Refresh the table
            })
            .catch(error => console.error(error));
    }
}

// Fetch categories for dropdowns
function fetchCategories() {
    axios.get('/api/v1/categories')
        .then(response => {
            const categories = response.data.categories;
            const categoryDropdowns = document.querySelectorAll('#category_id');

            categoryDropdowns.forEach(dropdown => {
                dropdown.innerHTML = categories.map(category => `
                    <option value="${category.id}">${category.name}</option>
                `).join('');
            });
        })
        .catch(error => console.error(error));
}



// Initialize
document.addEventListener('DOMContentLoaded', function () {
    fetchProducts();
    fetchCategories();

    // const productForm = document.getElementById('create-product-form');

    // productForm.addEventListener('submit', async (e) => {
    //     e.preventDefault();

    //     const name = document.getElementById('name').value;
    //     const slug = document.getElementById('slug').value;
    //     const description = document.getElementById('description').value;
    //     const price = document.getElementById('price').value;
    //     const stock = document.getElementById('stock').value;
    //     const imageFile = document.getElementById('image').files[0]; // Get the image file
    //     const is_active = document.getElementById('is_active').checked;
    //     const category_id = document.getElementById('category_id').value;

    //     try {
    //         await uploadProduct(name, slug, description, price, stock, category_id, imageFile, is_active);
    //         productForm.reset(); // Reset the form
    //         fetchProducts(); // Refresh the products table
    //     } catch (error) {
    //         console.error('Error al subir producto:', error);
    //     }
    // });
});