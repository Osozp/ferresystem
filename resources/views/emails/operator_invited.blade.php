<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f7f6; color: #333; margin: 0; padding: 20px; }
        .card { background-color: #ffffff; border-radius: 8px; padding: 30px; max-width: 500px; margin: 0 auto; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .btn { display: inline-block; background-color: #2563eb; color: #ffffff; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: bold; margin-top: 20px; }
        .credentials { background-color: #f1f5f9; padding: 15px; border-radius: 6px; margin: 15px 0; font-family: monospace; }
    </style>
</head>
<body>
    <div class="card">
        <h2>¡Hola, {{ $user->name }}! 👋</h2>
        <p>Has sido invitado a colaborar como operario en el negocio <strong>{{ $commerce->name }}</strong>.</p>

        <p>Tus credenciales temporales de acceso son:</p>
        <div class="credentials">
            <strong>Correo:</strong> {{ $user->email }}<br>
            <strong>Contraseña temporal:</strong> {{ $temporaryPassword }}
        </div>

        <p>Te recomendamos cambiar tu contraseña una vez que inicies sesión.</p>

        <a href="{{ route('login') }}" class="btn">Acceder al Sistema</a>
    </div>
</body>
</html>
