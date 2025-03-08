<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="auth-check" content="{{ auth()->check() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @auth
        <meta name="user-id" content="{{ auth()->id() }}">
    @endauth
    <title>Checkout - Pizzería</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <!-- Resumen del Pedido -->
    <div class="card mb-4">
        <div class="card-header">
            <h3>Resumen del Pedido</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Producto y Tamaño</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody id="cartItemsList">
                        <!-- Items will be populated by JavaScript -->
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3">Total:</th>
                            <td id="cartTotal">$0.00</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Formulario de Checkout -->
    <div class="card">
        <div class="card-body">
            <h4 class="card-title mb-4">Datos del Pedido</h4>
            
            <form id="checkoutForm" class="needs-validation" novalidate>
                <!-- Hidden fields -->
                <input type="hidden" id="user_id">
                <input type="hidden" id="order_number">
                <input type="hidden" id="total_amount">

                <!-- Dirección -->
                <div class="mb-3">
                    <label class="form-label">Dirección de Entrega *</label>
                    <textarea id="delivery_address" class="form-control" required 
                        placeholder="Ingrese la dirección completa de entrega"></textarea>
                    <div class="invalid-feedback">
                        La dirección de entrega es requerida
                    </div>
                </div>

                <!-- Teléfono -->
                <div class="mb-3">
                    <label class="form-label">Teléfono de Contacto *</label>
                    <input type="tel" id="contact_phone" class="form-control" required 
                        pattern="[0-9]{10}" placeholder="Ejemplo: 1234567890">
                    <div class="invalid-feedback">
                        Ingrese un número de teléfono válido (10 dígitos)
                    </div>
                </div>

                <!-- Notas -->
                <div class="mb-3">
                    <label class="form-label">Notas Especiales</label>
                    <textarea id="notes" class="form-control" 
                        placeholder="Instrucciones especiales para su pedido (opcional)"></textarea>
                </div>

                <!-- Método de Pago -->
                <div class="mb-3">
                    <label class="form-label">Método de pago *</label>
                    <select id="payment_method" class="form-select" required>
             <option value="">Seleccione un método de pago</option>
                <option value="efectivo">Pago en Efectivo</option>
             <option value="tarjeta">Tarjeta de Crédito/Débito</option>
                </select>
                    <div class="invalid-feedback">
                        Por favor seleccione un método de pago
                    </div>
                </div>

                <!-- Formulario de tarjeta -->
                <div id="formTarjeta" class="d-none">
                    <div class="mb-3">
                        <label class="form-label">Número de Tarjeta *</label>
                        <input type="text" class="form-control" id="card_number" 
                            pattern="[0-9]{16}" placeholder="1234 5678 9012 3456">
                        <div class="invalid-feedback">
                            Ingrese un número de tarjeta válido
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Fecha de Vencimiento *</label>
                                <input type="text" class="form-control" id="card_expiry" 
                                    placeholder="MM/YY">
                                <div class="invalid-feedback">
                                    Ingrese una fecha válida
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">CVV *</label>
                                <input type="text" class="form-control" id="card_cvv" 
                                    pattern="[0-9]{3,4}">
                                <div class="invalid-feedback">
                                    Ingrese un CVV válido
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mensaje para pago en efectivo -->
                <div id="mensajeEfectivo" class="alert alert-info d-none">
                    <p>Podrá realizar el pago en efectivo al momento de recibir su pedido.</p>
                </div>

                <!-- Botón de confirmar -->
                <button type="submit" class="btn btn-primary w-100 mt-3">Confirmar Pedido</button>
            </form>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/procesoPago.js') }}"></script>

</body>
</html>