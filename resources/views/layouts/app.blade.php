<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Sistema de Preventas')</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
    <!-- Iconos de Bootstrap (Soluciona las flechas e iconos gigantes) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <span class="navbar-brand">Distribuidora de Refrescos</span>
            <div>
                <a href="{{ route('preventas.index') }}" class="btn btn-outline-light btn-sm me-2">Preventas</a>
                <a href="{{ route('ofertas.index') }}" class="btn btn-outline-light btn-sm me-2">Ofertas</a>
                <a href="{{ route('camiones.index') }}" class="btn btn-outline-light btn-sm me-2">Camiones</a>
                <a href="{{ route('rutas.index') }}" class="btn btn-outline-light btn-sm me-2">Rutas</a>
                <a href="{{ route('devoluciones.index') }}" class="btn btn-outline-light btn-sm">Devoluciones</a>
                <a href="{{ route('reportes.index') }}" class="btn btn-outline-warning">Reportes</a>
            </div>
        </div>
    </nav>
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>