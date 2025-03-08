function fetchCategories() {
    axios.get('/api/v1/categories')
        .then(response => {
            const categories = response.data.categories; // Access categories under a key
            const tableBody = document.querySelector('#categories-table tbody');
            tableBody.innerHTML = ''; // Clear existing rows

            // Populate the table with categories
            categories.forEach(category => {
                const row = `
                    <tr>
                        <td>${category.id}</td>
                        <td>${category.name}</td>
                        <td>${category.slug}</td>
                        <td>${category.description}</td>
                        <td>
                             <button onclick="openEditModal(${category.id})" class="btn btn-sm btn-warning">Edit</button>
                            <button onclick="deleteCategory(${category.id})" class="btn btn-sm btn-danger">Delete</button>
                        </td>
                    </tr>
                `;
                tableBody.innerHTML += row;
            });
        })
        .catch(error => console.error('Error fetching categories:', error));
}

// Function to delete a category
function deleteCategory(categoryId) {
    if (confirm('Are you sure you want to delete this category?')) {
        axios.delete(`/api/v1/categories/${categoryId}`)
            .then(response => {
                alert('Category deleted successfully!');
                fetchCategories(); // Refresh the table
            })
            .catch(error => console.error(error));
    }
}

// Initialize the categories table when the page loads
document.addEventListener('DOMContentLoaded', function () {
    fetchCategories();
});