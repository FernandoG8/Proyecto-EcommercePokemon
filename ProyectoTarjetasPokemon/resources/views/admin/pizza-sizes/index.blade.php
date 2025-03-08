@extends('admin.layouts')

<!-- push scripts to script stack in header -->
@push('scripts')
<script src="{{ asset('js/admin/pizza-size/fetchPizzaSizes.js') }}"></script>
<script src="{{ asset('js/admin/pizza-size/loadModals.js') }}"></script>
@endpush

@section('content')
<h1>Tamaños de pizzas</h1>
<button id="add-size-btn" class="btn btn-primary mb-3">Agregar tamaño</button>
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

<!-- Create size Modal -->
<div class="modal fade" id="createSizeModal" tabindex="-1" aria-labelledby="createSizeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createSizeModalLabel">Add New Size</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal-body">
                <!-- Form will be loaded here dynamically -->
            </div>
        </div>
    </div>
</div>

<!-- Edit Product Modal (Empty Content, Will Be Filled Dynamically) -->
<div class="modal fade" id="editSizeModal" tabindex="-1" aria-labelledby="editSizeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSizeModalLabel">Edit size</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="edit-modal-body">
                <!-- Form will be loaded here dynamically -->
            </div>
        </div>
    </div>
</div>
@endsection