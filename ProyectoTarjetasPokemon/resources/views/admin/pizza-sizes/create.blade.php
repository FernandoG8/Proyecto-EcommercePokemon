<form id="create-product-form">
    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="mb-3">
        <label for="price-multiplier" class="form-label">Price multiplier</label>
        <input type="number" step="0.5" class="form-control" id="price_multiplier" name="price_multiplier" required>
    </div>
    <div class="mb-3">
        <label for="is_active" class="form-label">Is active</label>
        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="true" checked>
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
</form>