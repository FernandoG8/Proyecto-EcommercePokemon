function fetchProducts(page = 1) {
    axios.get(`/api/v1/products?page=${page}`)
        .then(response => {
            console.log(response.data); // Log the API response for debugging
            const { data, meta } = response.data; // Extract data and meta
            const { current_page, last_page } = meta.pagination; // Extract pagination data
            const tableBody = document.querySelector('#products-table tbody');
            tableBody.innerHTML = ''; // Clear existing rows

            // Populate the table with products
            data.forEach(product => {
                console.log(product.stock); // Log the price for debugging
                const row = `
                    <tr>
                        <td>${product.id}</td>
                        <td>${product.name}</td>
                        <td>${product.category ? product.category.name : 'N/A'}</td>
                        <td>$${parseFloat(product.price)}</td> 
                        <td>${product.is_active ? 'Active' : 'Inactive'}</td>
                        <td>
                           <button onclick="openEditModal(${product.id})" class="btn btn-sm btn-warning">Edit</button>
                            <button onclick="deleteProduct(${product.id})" class="btn btn-sm btn-danger">Delete</button>
                        </td>
                    </tr>
                `;
                tableBody.innerHTML += row;
            });            

            // Render pagination buttons
            renderPagination(current_page, last_page);
        })
        .catch(error => console.error(error));
}

// Render pagination buttons
function renderPagination(currentPage, lastPage) {
    const paginationContainer = document.querySelector('#pagination');
    if (!paginationContainer) return;

    paginationContainer.innerHTML = ''; // Clear existing buttons

    // Create Previous Button
    const prevButton = document.createElement('button');
    prevButton.className = 'btn btn-primary me-2';
    prevButton.textContent = 'Previous';
    prevButton.disabled = currentPage === 1;
    prevButton.addEventListener('click', () => {
        fetchProducts(currentPage - 1);
    });
    paginationContainer.appendChild(prevButton);

    // Create Next Button
    const nextButton = document.createElement('button');
    nextButton.className = 'btn btn-primary';
    nextButton.textContent = 'Next';
    nextButton.disabled = currentPage === lastPage;
    nextButton.addEventListener('click', () => {
        fetchProducts(currentPage + 1);
    });
    paginationContainer.appendChild(nextButton);
}

// Delete a product
function deleteProduct(productId) {
    if (confirm('Are you sure you want to delete this product?')) {
        axios.delete(`/api/v1/products/${productId}`)
            .then(() => {
                showAlert("Product deleted successfully!", "success"); // Show a simple alert
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
            document.querySelectorAll('#category_id').forEach(dropdown => {
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