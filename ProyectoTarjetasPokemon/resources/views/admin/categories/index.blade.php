@extends('admin.layouts')

@push('scripts')
<!-- Scripts -->
<script src="{{ asset('js/admin/categories/fetchCategories.js') }}"></script>
<script src="{{ asset('js/admin/categories/loadModals.js') }}"></script>
<script src="{{ asset('js/admin/categories/loadEditModal.js') }}"></script>
@endpush

@section('content')
<h1>Categorias</h1>
<button id="add-category-btn" class="btn btn-primary mb-3">Add Category</button>
<table class="table table-bordered" id="categories-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Slug</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <!-- Rows will be populated by JavaScript -->
    </tbody>
</table>

<!-- Create category Modal -->
<div class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="createCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createCategoryModalLabel">Add New category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal-body">
                <!-- Form will be loaded here dynamically -->
            </div>
        </div>
    </div>
</div>

<!-- Edit category Modal (Empty Content, Will Be Filled Dynamically) -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCategoryModalLabel">Edit category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="edit-modal-body">
                <!-- Form will be loaded here dynamically -->
            </div>
        </div>
    </div>
</div>

@endsection