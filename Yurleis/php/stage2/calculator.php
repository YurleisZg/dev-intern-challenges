<?php
$num1 = $_GET["n1"];
$num2 = $_GET["n2"];
$operation = $_GET["op"];

function calculate($n1, $n2, $op)
{
    switch ($op) {
        case 'add':
            return $n1 + $n2;
        case 'subtract':
            return $n1 - $n2;
        case 'multi':
            return $n1 * $n2;
        case 'divide':
            if ($n2 == 0) {
                echo "Error: Division by zero.";
                return null;
            }
            return $n1 / $n2;
    }
}

if ($num1 !== null && $num2 !== null && $operation !== null) {
    $result = calculate($num1, $num2, $operation);
}

$result = calculate($num1, $num2, $operation);
?>

<!DOCTYPE html>
<hmtl>
<head>
    <title>Calculator</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1 class="title">Calculator</h1>
    <form method="GET" action="" class="container">
        <label>Number 1: </label>
        <input type="number" name="n1" value="<?php echo $num1; ?>" required><br><br>
        
        <label>Number 2: </label>
        <input type="number" name="n2" value="<?php echo $num2; ?>" required><br><br>
        
        <label>Operation: </label>
        <select name="op" required>
            <option value="add" <?php if ($operation == 'add') echo 'selected'; ?>>Add</option>
            <option value="subtract" <?php if ($operation == 'subtract') echo 'selected'; ?>>Subtract</option>
            <option value="multi" <?php if ($operation == 'multi') echo 'selected'; ?>>Multiply</option>
            <option value="divide" <?php if ($operation == 'divide') echo 'selected'; ?>>Divide</option>
        </select><br><br>
        
        <input type="submit" value="Calculate"><br><br>
        
        <?php
        if (isset($result)) {
            echo "<h2>Result: " .$result . "</h2>";
        }
        ?>

    </form>
</html>