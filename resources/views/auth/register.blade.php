```blade
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Registro - Distribuidora Mapocho</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>

<body class="bg-dark">

<div class="container">

    <div class="row justify-content-center py-5">

        <div class="col-md-8">

            <div class="card shadow-lg border-0">

                <div class="card-body p-5">

                    <div class="text-center mb-4">

                        <h2 class="fw-bold">
                            Crear cuenta
                        </h2>

                        <p class="text-muted">
                            Distribuidora Mapocho
                        </p>

                    </div>


                    @if($errors->any())

                        <div class="alert alert-danger">

                            <ul class="mb-0">

                                @foreach($errors->all() as $error)

                                    <li>
                                        {{ $error }}
                                    </li>

                                @endforeach

                            </ul>

                        </div>

                    @endif


                    <form
                        action="{{ route('register') }}"
                        method="POST">

                        @csrf


                        <div class="row">


                            {{-- NOMBRE --}}

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Nombre
                                </label>

                                <input
                                    type="text"
                                    name="nombre"
                                    class="form-control"
                                    value="{{ old('nombre') }}"
                                    required>

                            </div>


                            {{-- APELLIDO --}}

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Apellido
                                </label>

                                <input
                                    type="text"
                                    name="apellido"
                                    class="form-control"
                                    value="{{ old('apellido') }}"
                                    required>

                            </div>


                            {{-- CI --}}

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    CI
                                </label>

                                <input
                                    type="text"
                                    name="ci"
                                    class="form-control"
                                    value="{{ old('ci') }}"
                                    required>

                            </div>


                            {{-- TELEFONO --}}

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Teléfono
                                </label>

                                <input
                                    type="text"
                                    name="telefono"
                                    class="form-control"
                                    value="{{ old('telefono') }}">

                            </div>


                            {{-- CORREO --}}

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Correo
                                </label>

                                <input
                                    type="email"
                                    name="correo"
                                    class="form-control"
                                    value="{{ old('correo') }}"
                                    required>

                            </div>


                            {{-- USUARIO --}}

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Usuario
                                </label>

                                <input
                                    type="text"
                                    name="usuario"
                                    class="form-control"
                                    value="{{ old('usuario') }}"
                                    required>

                            </div>


                            {{-- ROL --}}

                            <div class="col-md-12 mb-3">

                                <label class="form-label">
                                    Rol
                                </label>

                                <select
                                    name="idRol"
                                    class="form-select"
                                    required>

                                    <option value="">
                                        -- Seleccione un rol --
                                    </option>

                                    @foreach($roles as $rol)

                                        <option
                                            value="{{ $rol->idRol }}"
                                            {{ old('idRol') == $rol->idRol ? 'selected' : '' }}>

                                            {{ $rol->nombre }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- CONTRASEÑA --}}

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Contraseña
                                </label>

                                <input
                                    type="password"
                                    name="contrasena"
                                    class="form-control"
                                    required>

                            </div>


                            {{-- CONFIRMAR CONTRASEÑA --}}

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Confirmar contraseña
                                </label>

                                <input
                                    type="password"
                                    name="contrasena_confirmation"
                                    class="form-control"
                                    required>

                            </div>


                        </div>


                        <button
                            type="submit"
                            class="btn btn-primary w-100 mt-3">

                            Crear cuenta

                        </button>


                    </form>


                    <div class="text-center mt-4">

                        <span class="text-muted">
                            ¿Ya tienes una cuenta?
                        </span>

                        <a href="{{ route('login') }}">
                            Iniciar sesión
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
