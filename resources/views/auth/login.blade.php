```blade
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Iniciar sesión - Distribuidora Mapocho</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>

<body class="bg-dark">

<div class="container">

    <div class="row justify-content-center align-items-center"
         style="min-height: 100vh;">

        <div class="col-md-5">

            <div class="card shadow-lg border-0">

                <div class="card-body p-5">

                    <div class="text-center mb-4">

                        <h2 class="fw-bold">
                            Distribuidora Mapocho
                        </h2>

                        <p class="text-muted">
                            Sistema de gestión de preventas
                        </p>

                    </div>


                    @if(session('success'))

                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>

                    @endif


                    @if($errors->any())

                        <div class="alert alert-danger">

                            {{ $errors->first() }}

                        </div>

                    @endif


                    <form
                        action="{{ route('login') }}"
                        method="POST">

                        @csrf


                        <div class="mb-3">

                            <label class="form-label">
                                Usuario
                            </label>

                            <input
                                type="text"
                                name="usuario"
                                class="form-control"
                                value="{{ old('usuario') }}"
                                required
                                autofocus>

                        </div>


                        <div class="mb-3">

                            <label class="form-label">
                                Contraseña
                            </label>

                            <input
                                type="password"
                                name="contrasena"
                                class="form-control"
                                required>

                        </div>


                        <button
                            type="submit"
                            class="btn btn-primary w-100">

                            Iniciar sesión

                        </button>

                    </form>


                    <div class="text-center mt-4">

                        <span class="text-muted">
                            ¿No tienes una cuenta?
                        </span>

                        <a href="{{ route('register') }}">
                            Registrarse
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

</body>

</html>
```
