<form id="productForm" enctype="multipart/form-data">
    <div>
        <label>Nombre del producto:</label>
        <input type="text" id="productName" required>
    </div>
    <div>
        <label>Descripción:</label>
        <textarea id="productDescription" required></textarea>
    </div>
    <div>
        <label>Precio:</label>
        <input type="number" id="productPrice" step="0.01" required>
    </div>
    <div>
        <label>Stock:</label>
        <input type="number" id="productStock" required>
    </div>
    <div>
        <label>Categoría:</label>
        <select id="productCategory" required>
            <option value="1">Categoría 1</option>
            <option value="2">Categoría 2</option>
        </select>
    </div>
    <div>
        <label>Imagen:</label>
        <input type="file" id="productImage" accept="image/*" required>
    </div>
    <button type="submit">Subir Producto</button>
</form>
