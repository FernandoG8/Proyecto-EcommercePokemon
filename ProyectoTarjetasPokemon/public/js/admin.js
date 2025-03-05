// Fetch all products and populate the table
function fetchProducts() {
    axios.get('/api/v1/products')
        .then(response => {
            const products = response.data.data;
            const tableBody = document.querySelector('#products-table tbody');
            tableBody.innerHTML = ' ';

            products.forEach(product => {
                const row = `
                    <tr>
                        <td>${product.id}</td>
                        <td>${product.name}</td>
                        <td>${product.category.name}</td>
                        <td>$${product.price}</td>
                        <td>
                            <a href="/admin/products/${product.id}/edit" class="btn btn-sm btn-warning">Edit</a>
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
document.getElementById('create-product-form')?.addEventListener('submit', function (e) {
    e.preventDefault();

    // Gather form data
    const formData = {
        name: document.getElementById('name').value,
        category_id: document.getElementById('category_id').value,
        price: parseFloat(document.getElementById('price').value),
        description: document.getElementById('description').value,
        image: document.getElementById('image').files[0], // Handle file upload
    };

    // Send data to the API
    axios.post('/api/v1/products', formData, {
        headers: {
            'Content-Type': 'multipart/form-data', // Required for file uploads
        },
    })
    .then(response => {
        alert('Product created successfully!');
        window.location.href = '/admin/products'; // Redirect to the products list
    })
    .catch(error => {
        console.error('Error creating product:', error);
        alert('Failed to create product. Please check the console for details.');
    });
});

// Edit a product
document.getElementById('edit-product-form')?.addEventListener('submit', function (e) {
    e.preventDefault();

    const productId = window.location.pathname.split('/').pop();
    const formData = new FormData(this);
    axios.post(`/api/v1/products/${productId}`, formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
    })
    .then(response => {
        alert('Product updated successfully!');
        window.location.href = '/admin/products';
    })
    .catch(error => console.error(error));
});

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
});