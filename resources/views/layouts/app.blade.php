
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Sistema de Preventas')</title>

    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css"
        rel="stylesheet">

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>

<body class="bg-light">


{{-- NAVBAR --}}

<nav class="navbar navbar-dark bg-dark mb-4">

    <div class="container">

        {{-- NOMBRE DEL SISTEMA --}}

        <a href="{{ route('dashboard') }}"
           class="navbar-brand fw-bold">

            🥤 Distribuidora de Refrescos Mapocho

        </a>


        <div class="d-flex align-items-center">


            {{-- MENÚ PRINCIPAL --}}

            @auth

                <a href="{{ route('preventas.index') }}"
                   class="btn btn-outline-light btn-sm me-2">

                    Preventas

                </a>


                <a href="{{ route('ofertas.index') }}"
                   class="btn btn-outline-light btn-sm me-2">

                    Ofertas

                </a>


                <a href="{{ route('productos.index') }}"
                   class="btn btn-outline-info btn-sm me-2">

                    Productos

                </a>


                <a href="{{ route('camiones.index') }}"
                   class="btn btn-outline-light btn-sm me-2">

                    Camiones

                </a>


                <a href="{{ route('rutas.index') }}"
                   class="btn btn-outline-light btn-sm me-2">

                    Rutas

                </a>


                <a href="{{ route('devoluciones.index') }}"
                   class="btn btn-outline-light btn-sm me-2">

                    Devoluciones

                </a>


                <a href="{{ route('reportes.index') }}"
                   class="btn btn-outline-warning btn-sm me-3">

                    Reportes

                </a>


                {{-- USUARIO LOGUEADO --}}

                <div class="dropdown">

                    <button
                        class="btn btn-light btn-sm dropdown-toggle"
                        type="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">

                        <i class="bi bi-person-circle"></i>

                        {{ auth()->user()->nombre }}

                    </button>


                    <ul class="dropdown-menu dropdown-menu-end">


                        {{-- INFORMACIÓN DEL USUARIO --}}

                        <li>

                            <div class="dropdown-item-text">

                                <strong>
                                    {{ auth()->user()->nombre }}
                                    {{ auth()->user()->apellido }}
                                </strong>

                                <br>

                                <small class="text-muted">

                                    @if(auth()->user()->rol)
                                        {{ auth()->user()->rol->nombre }}
                                    @else
                                        Sin rol
                                    @endif

                                </small>

                            </div>

                        </li>


                        <li>
                            <hr class="dropdown-divider">
                        </li>


                        {{-- LOGOUT --}}

                        <li>

                            <form
                                action="{{ route('logout') }}"
                                method="POST">

                                @csrf

                                <button
                                    type="submit"
                                    class="dropdown-item text-danger">

                                    <i class="bi bi-box-arrow-right"></i>

                                    Cerrar sesión

                                </button>

                            </form>

                        </li>

                    </ul>

                </div>

            @endauth

        </div>

    </div>

</nav>


{{-- CONTENIDO --}}

<div class="container">

    {{-- MENSAJE SUCCESS --}}

    @if (session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

    @endif


    {{-- MENSAJE ERROR --}}

    @if (session('error'))

        <div class="alert alert-danger">

            {{ session('error') }}

        </div>

    @endif


    {{-- ERRORES DE VALIDACIÓN --}}

    @if ($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach ($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    @yield('content')

</div>


<script
    src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js">
</script>

</body>

</html>
