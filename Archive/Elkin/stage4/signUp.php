<?php
$errores = [];
$nombre = $_POST['nombre'] ?? '';
$email = $_POST['email'] ?? '';
$edad = $_POST['edad'] ?? '';
$metodo = $_SERVER['REQUEST_METHOD']; // para mostrar el método

if ($metodo === 'POST') {

    // Validaciones
    if (empty($nombre)) {
        $errores['nombre'] = "El nombre es obligatorio.";
    } elseif (strlen($nombre) < 3) {
        $errores['nombre'] = "El nombre debe tener al menos 3 caracteres.";
    }

    if (empty($email)) {
        $errores['email'] = "El email es obligatorio.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores['email'] = "El formato del email no es válido.";
    }

    if (empty($edad)) {
        $errores['edad'] = "La edad es obligatoria.";
    } elseif (!is_numeric($edad)) {
        $errores['edad'] = "La edad debe ser un número.";
    }

    
    if (empty($errores)) {
        echo "<h2>Datos recibidos correctamente</h2>";
        echo "Nombre: $nombre <br>";
        echo "Email: $email <br>";
        echo "Edad: $edad <br>";
        echo "<p>Método utilizado: <strong>$metodo</strong></p>";
        exit;
    }
}
?>