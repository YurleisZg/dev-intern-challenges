<?php

function greet($name = "World") {
    return "Hello, $name!";
}

echo greet() . "\n";
echo greet("Alice") . "\n";

$add = function($a, $b) {
    return $a + $b;
};

echo $add(10, 5) . "\n";

function calculate($x, $y, $operation) {
    return $operation($x, $y);
}

$multiply = fn($a, $b) => $a * $b;
echo calculate(6, 7, $multiply) . "\n";
?>