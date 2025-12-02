<?php
// Simple debug endpoint to inspect the pattern posted from the timer demo.
// You can replace this with your own persistence or validation logic.

header('Content-Type: text/plain; charset=UTF-8');
echo "Received pattern data:\n\n";
print_r($_POST);


