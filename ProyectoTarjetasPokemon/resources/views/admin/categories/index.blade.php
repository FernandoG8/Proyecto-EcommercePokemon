@extends('admin.layouts')

@push('scripts')
<!-- Scripts -->
<script src="{{ asset('js/fetchCategories.js') }}"></script>
@endpush

@section('content')
    <h1>Categorias</h1>
    <a href="/admin/categories/create" class="btn btn-primary mb-3">Agregar categoria</a>
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
@endsection