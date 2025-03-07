@extends('admin.layouts')

<!-- push scripts to script stack in header -->
@push('scripts')
    <script src="{{ asset('js/fetchPizzaSizes.js') }}"></script>
@endpush

@section('content')
    <h1>Tamaños de pizzas</h1>
    <a href="/admin/pizza-sizes/create" class="btn btn-primary mb-3">Agregar tamaño</a>
    <table class="table table-bordered" id="sizes-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price multiplier</th>
                <th>Is active</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Rows will be populated by JavaScript -->
        </tbody>
    </table>
@endsection