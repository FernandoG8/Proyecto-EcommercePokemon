<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago - Pokémon</title>
    <link rel="stylesheet" href="{{ asset('css/stylesPagoForms.css') }}"> <!-- Asegúrate de tener un CSS para estilos -->
</head>
<body>
    <div class="payment-container">
        <h1>Datos de Pago</h1>

        <!-- Mostrar el total del carrito -->
        <div class="total-container">
            <h2>Total a Pagar: ${{ $total }}</h2>
        </div>

        <form id="payment-form">
            <!-- Datos personales -->
            <fieldset>
                <legend>Datos Personales</legend>
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>
                <div class="form-group">
                    <label for="apellido">Apellido:</label>
                    <input type="text" id="apellido" name="apellido" required>
                </div>
                <div class="form-group">
                    <label for="celular">Número de Celular:</label>
                    <input type="tel" id="celular" name="celular" required>
                </div>
                <div class="form-group">
                    <label for="sexo">Sexo:</label>
                    <select id="sexo" name="sexo" required>
                        <option value="">Seleccione</option>
                        <option value="masculino">Masculino</option>
                        <option value="femenino">Femenino</option>
                        <option value="otro">Otro</option>
                    </select>
                </div>
            </fieldset>

            <!-- Dirección -->
            <fieldset>
                <legend>Dirección</legend>
                <div class="form-group">
                    <label for="codigo-postal">Código Postal:</label>
                    <input type="text" id="codigo-postal" name="codigo-postal" required>
                </div>
                <div class="form-group">
                    <label for="municipio">Municipio:</label>
                    <select id="municipio" name="municipio" required>
                        <option value="">Seleccione</option>
                        <!-- Aquí se cargarán los municipios dinámicamente desde scriptPagoPokemon.js -->
                    </select>
                </div>
            </fieldset>

            <!-- Datos de la tarjeta -->
            <fieldset>
                <legend>Datos de la Tarjeta</legend>
                <div class="form-group">
                    <label for="numero-tarjeta">Número de Tarjeta:</label>
                    <input type="text" id="numero-tarjeta" name="numero-tarjeta" required>
                </div>
                <div class="form-group">
                    <label for="fecha-expiracion">Fecha de Expiración:</label>
                    <input type="month" id="fecha-expiracion" name="fecha-expiracion" required>
                </div>
                <div class="form-group">
                    <label for="cvv">CVV:</label>
                    <input type="text" id="cvv" name="cvv" required maxlength="3">
                </div>
            </fieldset>

            <!-- Botón de enviar -->
            <div class="form-group">
                <button type="submit">Pagar</button>
            </div>
        </form>
    </div>

    <script src="{{ asset('js/scriptPagoPokemon.js') }}"></script>
</body>
</html>