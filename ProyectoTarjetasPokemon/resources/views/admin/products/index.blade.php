@extends('admin.layouts')

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/admin/products/fetchProducts.js') }}"></script>
<script src="{{ asset('js/admin/products/loadModals.js') }}"></script>
@endpush

@section('content')
<h1>Products</h1>
<button id="add-product-btn" class="btn btn-primary mb-3">Add Product</button>

<table class="table table-bordered" id="products-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Category</th>
            <th>Price</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <!-- Rows will be populated by JavaScript -->
    </tbody>
</table>

<!-- Create product Modal -->
<div class="modal fade" id="createProductModal" tabindex="-1" aria-labelledby="createProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createProductModalLabel">Add New Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal-body">
                <!-- Form will be loaded here dynamically -->
            </div>
        </div>
    </div>
</div>

<!-- Edit Product Modal (Empty Content, Will Be Filled Dynamically) -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModalLabel">Edit product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="edit-modal-body">
                <!-- Form will be loaded here dynamically -->
            </div>
        </div>
    </div>
</div>

@endsection