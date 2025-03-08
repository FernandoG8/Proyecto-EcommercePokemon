@extends('admin.layouts')

@push('scripts')
<!-- Scripts -->
<script src="{{ asset('js/admin/orders/fetchOrders.js')}}"></script>
<script src="{{ asset('js/admin/orders/loadModals.js')}}"></script>
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

<!-- Edit order Modal (Empty Content, Will Be Filled Dynamically) -->
<div class="modal fade" id="editOrderModal" tabindex="-1" aria-labelledby="editOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editOrderModalLabel">Edit order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="edit-modal-body">
                <!-- Form will be loaded here dynamically -->
            </div>
        </div>
    </div>
</div>
@endsection