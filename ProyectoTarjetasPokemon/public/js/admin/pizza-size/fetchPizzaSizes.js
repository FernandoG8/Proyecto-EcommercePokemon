function fetchPizzaSizes() {
    axios.get('/api/v1/sizes')
        .then(response => {
            const sizes = response.data.sizes; // Access categories under a key
            const tableBody = document.querySelector('#sizes-table tbody');
            tableBody.innerHTML = ''; // Clear existing rows

            // Populate the table with categories
            sizes.forEach(size => {
                const row = `
                    <tr>
                        <td>${size.id}</td>
                        <td>${size.name}</td>
                        <td>${size.price_multiplier}</td>
                        <td>${size.is_active ? 'Si' : 'No'}</td>

                        <td>
                            <button onclick="openEditModal(${size.id})" class="btn btn-sm btn-warning">Edit</button>
                            <button onclick="deleteCategory(${size.id})" class="btn btn-sm btn-danger">Delete</button>
                        </td>
                    </tr>
                `;
                tableBody.innerHTML += row;
            });
        })
        .catch(error => console.error('Error fetching categories:', error));
}

// Function to delete a category
function deleteSize(sizeId) {
    if (confirm('Are you sure you want to delete this category?')) {
        axios.delete(`/api/v1/sizes/${sizeId}`)
            .then(response => {
                alert('Size deleted successfully!');
                fetchCategories(); // Refresh the table
            })
            .catch(error => console.error(error));
    }
}

// Initialize the categories table when the page loads
document.addEventListener('DOMContentLoaded', function () {
    fetchPizzaSizes();
});