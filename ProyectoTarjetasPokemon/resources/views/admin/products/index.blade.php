@extends('admin.layouts')

@section('content')
    <h1>Products</h1>
    <a href="/admin/products/create" class="btn btn-primary mb-3">Add Product</a>
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
@endsection