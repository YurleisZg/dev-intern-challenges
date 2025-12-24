<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fruit Set Logic</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 2rem;
            background: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 2px solid #667eea;
            padding-bottom: 0.5rem;
        }
        .info {
            background: #e7f3ff;
            border-left: 4px solid #667eea;
            padding: 1rem;
            margin: 1rem 0;
            border-radius: 4px;
        }
        .btn {
            background: #667eea;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 1rem;
        }
        .btn:hover {
            background: #764ba2;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>🍎 Fruit Set Logic</h1>

    <div class="info">
        <strong>Componente activo:</strong> Fruit Set Logic
        <br>
        <strong>Ruta:</strong> app/Features/FruitSetLogic
        <br>
        <strong>Hora:</strong> {{ date('H:i:s') }}
    </div>

    <h2>Estado del Componente</h2>
    <p><strong>Vista:</strong> Cargada correctamente ✓</p>
    <p><strong>Namespace:</strong> fruit::index</p>
    <p><strong>Controller:</strong> FruitSetLogic\Http\Controllers</p>

    <a href="{{ route('elkin.dashboard')}}" class="btn">Volver al Dashboard</a>
</div>
</body>
</html>
