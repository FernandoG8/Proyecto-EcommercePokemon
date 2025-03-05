@extends('admin.layouts')

@push('scripts')
<script src="{{ asset('js/fetchProducts.js') }}"></script>
@endpush
@section('content')
    <h1>Add New Product</h1>
    <form id="create-product-form" action="{{ url('/admin/products') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="slug" class="form-label">Slug</label>
            <input type="text" class="form-control" id="slug" name="slug" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" step="0.01" class="form-control" id="price" name="price" required>
        </div>
        <div class="mb-3">
            <label for="stock" class="form-label">Stock</label>
            <input type="number" step="1" class="form-control" id="stock" name="stock" required>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Image</label>
            <input type="file" class="form-control" id="image" name="image">
        </div>
        <div class="mb-3">
            <label for="is_active" class="form-label">Is active</label>
            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" tabindex="0" value="true" checked>
        </div>
        <div class="mb-3">
            <label for="category_id" class="form-label">Category</label>
            <select class="form-control" id="category_id" name="category_id" required>
                <!-- Categories will be populated by JavaScript -->
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
@endsection