@extends('admin.layouts')

@push('scripts')
<!-- Scripts -->
<script src="{{ asset('js/fetchOrders.js')}}"></script>
@endpush

@section('content')
    <h1>Pedidos</h1>
    <table class="table table-bordered" id="orders-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Order number</th>
                <th>Total</th>
                <th>Status</th>
                <th>Payment method</th>
                <th>Payment status</th>
                <th>Delivery address</th>
                <th>Contact info</th>
                <th>Notes</th>
                <th>Delivered at</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Rows will be populated by JavaScript -->
        </tbody>
    </table>
@endsection