<?php

$fruits = ["apple", "banana", "orange"];

foreach ($fruits as $fruit) {
    echo "$fruit\n";
}

foreach ($fruits as $index => $fruit) {
    echo "$index: $fruit\n";
}

for ($i = 1; $i <= 5; $i++) {
    echo "Count: $i\n";
}

$counter = 0;
while ($counter < 3) {
    echo "While loop: $counter\n";
    $counter++;
}
?>