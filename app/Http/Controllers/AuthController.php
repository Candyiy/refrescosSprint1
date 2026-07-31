<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'usuario' => 'required|string',
            'contrasena' => 'required|string',
        ], [
            'usuario.required' => 'Ingrese su usuario.',
            'contrasena.required' => 'Ingrese su contraseña.',
        ]);

        $usuario = Usuario::where('usuario', $request->usuario)
            ->where('estado', true)
            ->first();

        if (!$usuario || !Hash::check($request->contrasena, $usuario->contrasena)) {

            return back()
                ->withInput($request->only('usuario'))
                ->withErrors([
                    'usuario' => 'Usuario o contraseña incorrectos.'
                ]);
        }

        Auth::login($usuario);

        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }


    /*
    |--------------------------------------------------------------------------
    | REGISTRO
    |--------------------------------------------------------------------------
    */

    public function showRegister()
    {
        $roles = Rol::orderBy('nombre')->get();

        return view('auth.register', compact('roles'));
    }

    public function register(Request $request)
    {
        $request->validate([

            'idRol' => 'required|exists:roles,idRol',

            'nombre' => 'required|string|max:255',

            'apellido' => 'required|string|max:255',

            'ci' => 'required|string|max:255|unique:usuarios,ci',

            'telefono' => 'nullable|string|max:255',

            'correo' => 'required|email|max:255|unique:usuarios,correo',

            'usuario' => 'required|string|max:255|unique:usuarios,usuario',

            'contrasena' => 'required|string|min:6|confirmed',

        ], [

            'idRol.required' => 'Seleccione un rol.',

            'idRol.exists' => 'El rol seleccionado no existe.',

            'nombre.required' => 'Ingrese su nombre.',

            'apellido.required' => 'Ingrese su apellido.',

            'ci.required' => 'Ingrese su CI.',

            'ci.unique' => 'Este CI ya está registrado.',

            'correo.required' => 'Ingrese su correo.',

            'correo.email' => 'Ingrese un correo válido.',

            'correo.unique' => 'Este correo ya está registrado.',

            'usuario.required' => 'Ingrese un nombre de usuario.',

            'usuario.unique' => 'Este usuario ya está registrado.',

            'contrasena.min' => 'La contraseña debe tener al menos 6 caracteres.',

            'contrasena.confirmed' => 'Las contraseñas no coinciden.',

        ]);


        Usuario::create([

            'idRol' => $request->idRol,

            'nombre' => $request->nombre,

            'apellido' => $request->apellido,

            'ci' => $request->ci,

            'telefono' => $request->telefono,

            'correo' => $request->correo,

            'usuario' => $request->usuario,

            'contrasena' => Hash::make($request->contrasena),

            'estado' => true,

        ]);


        return redirect()
            ->route('login')
            ->with(
                'success',
                'Cuenta creada correctamente. Ahora puede iniciar sesión.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with(
                'success',
                'Sesión cerrada correctamente.'
            );
    }
}
