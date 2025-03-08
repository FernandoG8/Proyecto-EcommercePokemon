<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mis Pedidos - TAZ PIZZA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/paginaIndex.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&family=Fredoka+One&display=swap" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/">TAZ PIZZA</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/Menu">Menú</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/pedidos">Pedidos</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="container mt-5">
        <!-- Alert para mensajes de error -->
        <div id="alertMessage" class="alert alert-danger" style="display: none;"></div>

        <h1 class="text-center mb-4" style="font-family: 'Fredoka One', cursive;">Mis Pedidos</h1>
        
        
        <!-- Filtros -->
        <div class="row mb-4">
            <!-- Actualiza la sección de filtros -->
                <div class="col-md-6">
                  <select id="orderStatusFilter" class="form-select">
                 <option value="all">Todos los estados</option>
                   <option value="pending">Pendiente</option>
                    <option value="processing">En proceso</option>
                    <option value="completed">Completado</option>
              <option value="cancelled">Cancelado</option>
             <option value="delivered">Entregado</option>
                </select>
            </div>
            <div class="col-md-6">
                <input type="date" id="orderDateFilter" class="form-control">
            </div>
        </div>

        <!-- Lista de Pedidos -->
        <div id="ordersList" class="row">
            <!-- Los pedidos se cargarán dinámicamente aquí -->
        </div>

        <!-- Template para cada pedido -->
        <!-- Template para cada pedido -->
        <template id="orderTemplate">
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Pedido #<span class="order-number"></span></h5>
                        <span class="badge order-status"></span>
                    </div>
                    <div class="card-body">
                        <div class="order-items">
                            <!-- Items del pedido -->
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center">
                            <strong>Total:</strong>
                            <span class="order-total fw-bold"></span>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">Fecha: <span class="order-date"></span></small>
                        </div>
                    </div>
                </div>
            </div>
        </template>
           <!-- Lista de Pedidos -->
           <div id="ordersList" class="row">
            <!-- Loading spinner -->
            <div class="col-12 text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
            </div>
        </div>
    </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/pedidos.js') }}"></script>
</body>
</html>