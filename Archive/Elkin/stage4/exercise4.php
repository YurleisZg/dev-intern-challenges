<?php
$nombre = $_POST['nombre'] ?? '';
$email  = $_POST['email'] ?? '';
$edad   = $_POST['edad'] ?? '';
$errores = $errores ?? []; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
</head>
<body>
    <h1>Registro</h1>

    <form action="signUp.php" method="POST"> 
        
        Nombre: <br>
        <input type="text" name="nombre" value="<?= htmlspecialchars($nombre) ?>">
        <span style="color:red"><?= $errores['nombre'] ?? '' ?></span>
        <br><br>

        Email: <br>
        <input type="email" name="email" value="<?= htmlspecialchars($email) ?>">
        <span style="color:red"><?= $errores['email'] ?? '' ?></span>
        <br><br>

        Edad: <br>
        <input type="number" name="edad" value="<?= htmlspecialchars($edad) ?>">
        <span style="color:red"><?= $errores['edad'] ?? '' ?></span>
        <br><br>

        <button type="submit">Registrarme</button>
    </form>

</body>
</html>
