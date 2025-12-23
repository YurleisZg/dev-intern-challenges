
<?php
include '../../../back_button.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Victory!</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="game-container">
    <h1>Congratulations!</h1>
    <div class="card card-success">
        <h2>You have completed all 5 levels</h2>
        <p>
            You have memorized and correctly repeated all patterns
            within the time allowed. Excellent!
        </p>
        <a href="start.php?reset=1" class="btn btn-primary">Play again</a>
    </div>
</div>
</body>
</html>
