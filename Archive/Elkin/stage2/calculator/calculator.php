<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Calculator</title>
</head>
<body>

<?php


// Validar parámetros
if (!isset($_GET["number_1"], $_GET["number_2"])) {
    echo "<h3>Por favor pasa number_1 y number_2 por GET. Ejemplo:</h3>";
    echo "<p>?number_1=10&number_2=5</p>";
    
}

// Funciones

function calculate($a, $b, $operation) {

    switch ($operation) {
        case "sum":
            return $a + $b;

        case "subtract":
            return $a - $b;

        case "multiply":
            return $a * $b;

        case "divide":
            return $b == 0 ? "Error: división por cero" : $a / $b;

        default:
            return "Not valid operation";
    }
}



// Convertir a float
$num1 = (float) $_GET["number_1"];
$num2 = (float) $_GET["number_2"];

?>

<h1>Resultados</h1>

<ul>
    <li><strong>Suma:</strong> <?= calculate($num1, $num2,"sum") ?></li>
    <li><strong>Resta:</strong> <?= calculate($num1, $num2,"subtract") ?></li>
    <li><strong>Multiplicación:</strong> <?= calculate($num1, $num2,"multiply") ?></li>
    <li><strong>División:</strong> <?= calculate($num1, $num2,"divide") ?></li>
</ul>

</body>
</html>
