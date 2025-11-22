<?php
$username = null;
$display = $username ?? "Guest";
echo "Display: $display\n";

$config = [];
$timeout = $config["timeout"] ?? 30;
echo "Timeout: $timeout\n";

$isActive = true;
$hasPermission = false;

var_dump($isActive && $hasPermission);
var_dump($isActive || $hasPermission);
var_dump(!$hasPermission);

$str = "Hello " . "World";
echo "$str\n";

$greeting = "Hi";
$greeting .= " there";
echo "$greeting\n";
?>