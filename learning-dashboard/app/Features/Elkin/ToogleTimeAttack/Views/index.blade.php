<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $titulo ?? 'Página de Prueba' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 2rem;
        }
        h1 {
            color: #667eea;
            margin-bottom: 1rem;
            border-bottom: 3px solid #667eea;
            padding-bottom: 0.5rem;
        }
        h2 {
            color: #764ba2;
            margin-top: 2rem;
            margin-bottom: 1rem;
        }
        .card {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 1rem;
            margin: 1rem 0;
            border-radius: 4px;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin: 1rem 0;
        }
        .grid-item {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 8px;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0;
        }
        th, td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #667eea;
            color: white;
        }
        tr:hover {
            background: #f5f5f5;
        }
        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: bold;
        }
        .badge-success {
            background: #28a745;
            color: white;
        }
        .badge-danger {
            background: #dc3545;
            color: white;
        }
        .alert {
            padding: 1rem;
            border-radius: 4px;
            margin: 1rem 0;
        }
        .alert-info {
            background: #d1ecf1;
            border-left: 4px solid #0c5460;
            color: #0c5460;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>{{ $titulo ?? 'Blade Template de Prueba' }}</h1>

    <div class="alert alert-info">
        <strong>Fecha actual:</strong> {{ date('d/m/Y H:i:s') }}
    </div>

    <h2>Variables Blade</h2>
    <div class="card">
        <p><strong>Nombre:</strong> {{ $nombre ?? 'Usuario' }}</p>
        <p><strong>Email:</strong> {{ $email ?? 'usuario@ejemplo.com' }}</p>
        <p><strong>Edad:</strong> {{ $edad ?? 25 }}</p>
    </div>

    <h2>Condicionales</h2>
    @php
        $usuarios = [
            ['id' => 1, 'nombre' => 'Juan Pérez', 'email' => 'juan@email.com', 'activo' => true],
            ['id' => 2, 'nombre' => 'María García', 'email' => 'maria@email.com', 'activo' => true],
            ['id' => 3, 'nombre' => 'Carlos López', 'email' => 'carlos@email.com', 'activo' => false],
            ['id' => 4, 'nombre' => 'Ana Martínez', 'email' => 'ana@email.com', 'activo' => true],
        ];
    @endphp

    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Estado</th>
        </tr>
        </thead>
        <tbody>
        @forelse($usuarios as $usuario)
            <tr>
                <td>{{ $usuario['id'] }}</td>
                <td>{{ $usuario['nombre'] }}</td>
                <td>{{ $usuario['email'] }}</td>
                <td>
                    @if($usuario['activo'])
                        <span class="badge badge-success">Activo</span>
                    @else
                        <span class="badge badge-danger">Inactivo</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4">No hay usuarios registrados</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <h2>Bucles y Grid</h2>
    <div class="grid">
        @for($i = 1; $i <= 6; $i++)
            <div class="grid-item">
                <h3>Item {{ $i }}</h3>
                <p>Elemento número {{ $i }}</p>
            </div>
        @endfor
    </div>

    <h2>Switch Statement</h2>
    @php
        $rol = 'admin';
    @endphp
    <div class="card">
        <strong>Rol actual:</strong> {{ $rol }}
        <br><br>
        @switch($rol)
            @case('admin')
                <p style="color: #667eea;">✓ Acceso completo al sistema</p>
                @break
            @case('editor')
                <p style="color: #ffc107;">✓ Puede editar contenido</p>
                @break
            @case('usuario')
                <p style="color: #28a745;">✓ Acceso básico</p>
                @break
            @default
                <p style="color: #dc3545;">✗ Sin permisos asignados</p>
        @endswitch
    </div>
</div>
</body>
</html>
