<h1>Edit Order</h1>
<form id="edit-order-form">
<div class="mb-3">
        <label for="id" class="form-label">Id</label>
        <input type="text" class="form-control" id="id" name="id" disabled>
    </div>
    <div class="mb-3">
        <label for="order_number" class="form-label">Order number</label>
        <input type="text" class="form-control" id="order_number" name="order_number" disabled>
    </div>
    <div class="mb-3">
        <label for="status" class="form-label">Order status</label>
        <select class="form-control" id="status" name="status" required>
            <!-- Categories will be populated by JavaScript -->
        </select>
    </div>
    <div class="mb-3">
        <label for="payment_status" class="form-label">Payment status</label>
        <select class="form-control" id="payment_status" name="payment_status" required>
            <!-- Categories will be populated by JavaScript -->
        </select>
    </div>
    <button type="button" onclick="sendUpdate()" class="btn btn-primary">Save</button>
</form>