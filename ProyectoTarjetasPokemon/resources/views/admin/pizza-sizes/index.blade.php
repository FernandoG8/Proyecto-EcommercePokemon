@extends('admin.layouts')

@section('content')
    <h1>Tamaños de pizzas</h1>
    <a href="/admin/pizza-sizes/create" class="btn btn-primary mb-3">Agregar tamaño</a>
    <table class="table table-bordered" id="orders-table">
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