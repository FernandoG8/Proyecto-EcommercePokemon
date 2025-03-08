<h1>Edit Category</h1>
<form id="edit-product-form">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="mb-3">
        <label for="slug" class="form-label">Slug</label>
        <select class="form-control" id="slug" name="slug" required>
            <!-- Categories will be populated by JavaScript -->
        </select>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
</form>