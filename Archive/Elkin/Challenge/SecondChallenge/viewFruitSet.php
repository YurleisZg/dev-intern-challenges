<?php
session_start();


// Initialize baskets
if (!isset($_SESSION['A'])) $_SESSION['A'] = [];
if (!isset($_SESSION['B'])) $_SESSION['B'] = [];

// Available fruits (capitalized)
$fruits = ["Apple", "Banana", "Cherry", "Lemon", "Grape"];

// Helper: redirect to clean URL (same script without query string)
function redirect_clean() {
    $clean = strtok($_SERVER['REQUEST_URI'], '?'); // keeps path, removes query
    header("Location: " . $clean);
    exit;
}

// Process add action
if (isset($_GET['add']) && isset($_GET['basket'])) {
    $fruit = $_GET['add'];
    $basket = $_GET['basket'];

    // Validate inputs
    if (in_array($fruit, $fruits, true) && ($basket === 'A' || $basket === 'B')) {
        // append fruit
        $_SESSION[$basket][] = $fruit;
        // keep order but remove any accidental empty entries
        $_SESSION[$basket] = array_values($_SESSION[$basket]);
    }
    // Redirect clean to avoid duplicate add on refresh
    redirect_clean();
}

// Process reset actions
if (isset($_GET['reset']) && in_array($_GET['reset'], ['A','B','all'])) {
    if ($_GET['reset'] === 'A') $_SESSION['A'] = [];
    if ($_GET['reset'] === 'B') $_SESSION['B'] = [];
    if ($_GET['reset'] === 'all') { $_SESSION['A'] = []; $_SESSION['B'] = []; }
    redirect_clean();
}

// Operation result (only set when op is provided)
$result = "";
$explanation = "";

// If an operation was requested via GET (op)
if (isset($_GET['op'])) {
    $A = $_SESSION['A'];
    $B = $_SESSION['B'];

    switch ($_GET['op']) {
        case 'union':
            $result = array_values(array_unique(array_merge($A, $B)));
            $explanation = "A ∪ B = All fruits in A or B";
            break;
        case 'intersect':
            $result = array_values(array_intersect($A, $B));
            $explanation = "A ∩ B = Fruits present in both";
            break;
        case 'diffAB':
            $result = array_values(array_diff($A, $B));
            $explanation = "A - B = Fruits in A that are NOT in B";
            break;
        case 'diffBA':
            $result = array_values(array_diff($B, $A));
            $explanation = "B - A = Fruits in B that are NOT in A";
            break;
        case 'xor':
            $result = array_values(array_merge(array_diff($A, $B), array_diff($B, $A)));
            $explanation = "(A XOR B) = Unique fruits in each basket";
            break;
        case 'subset':
            $isSubset = empty(array_diff($A, $B));
            $result = $isSubset ? "Yes" : "No";
            $explanation = "A ⊆ B = Are all elements of A inside B?";
            break;
        case 'jaccard':
            $intersection = array_intersect($A, $B);
            $union = array_unique(array_merge($A, $B));
            $ratio = count($union) > 0 ? (count($intersection) / count($union)) * 100 : 0;
            $result = round($ratio, 2) . "%";
            $explanation = "Similarity = |A ∩ B| / |A ∪ B| * 100";
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Fruit Set Logic</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<header>
    <h1>Fruit Set Logic</h1>
    <div>
        <a class="btn" href="../index.php">Home</a>
        <a class="btn" href="?reset=all" onclick="return confirm('Reset both baskets?')">Reset All</a>
    </div>
</header>

<div class="container">
    <!-- Basket A -->
    <div class="card">
        <h2>Basket A <span class="small">(<a href="?reset=A" class="small">reset</a>)</span></h2>
        <div class="fruit-list"><?= empty($_SESSION['A']) ? "<em>Empty</em>" : htmlspecialchars(implode(", ", $_SESSION['A'])) ?></div>

        <?php foreach ($fruits as $f): 
            $url = '?add=' . rawurlencode($f) . '&basket=A';
        ?>
            <a class="btn" href="<?= htmlspecialchars($url) ?>">Add <?= htmlspecialchars($f) ?></a>
        <?php endforeach; ?>
    </div>

    <!-- Basket B -->
    <div class="card">
        <h2>Basket B <span class="small">(<a href="?reset=B" class="small">reset</a>)</span></h2>
        <div class="fruit-list"><?= empty($_SESSION['B']) ? "<em>Empty</em>" : htmlspecialchars(implode(", ", $_SESSION['B'])) ?></div>

        <?php foreach ($fruits as $f): 
            $url = '?add=' . rawurlencode($f) . '&basket=B';
        ?>
            <a class="btn" href="<?= htmlspecialchars($url) ?>">Add <?= htmlspecialchars($f) ?></a>
        <?php endforeach; ?>
    </div>

    <!-- Operations panel -->
    <div class="panel">
        <h2>Set Operations</h2>
        <div class="small">Click an operation to compute. Results appear below.</div>
        <div style="margin-top:12px;">
            <a class="btn" href="?op=union">Union (A ∪ B)</a>
            <a class="btn" href="?op=intersect">Intersection (A ∩ B)</a>
            <a class="btn" href="?op=diffAB">A - B</a>
            <a class="btn" href="?op=diffBA">B - A</a>
            <a class="btn" href="?op=xor">Symmetric Difference</a>
            <a class="btn" href="?op=subset">Is A ⊆ B?</a>
            <a class="btn" href="?op=jaccard">Similarity % (Jaccard)</a>
        </div>
    </div>

    <!-- Result -->
    <?php if ($result !== ""): ?>
    <div class="result">
        <h2>Result</h2>
        <p><strong>Operation:</strong> <?= htmlspecialchars($explanation) ?></p>
        <p><strong>Output:</strong>
            <?php
                if (is_array($result)) {
                    echo "[" . htmlspecialchars(implode(", ", $result)) . "]";
                } else {
                    echo htmlspecialchars((string)$result);
                }
            ?>
        </p>
        <?php
            // Extra: show internal A and B for clarity
            echo "<p class='small'><strong>Basket A:</strong> " . (empty($_SESSION['A'])? "<em>Empty</em>" : htmlspecialchars(implode(", ", $_SESSION['A']))) . "</p>";
            echo "<p class='small'><strong>Basket B:</strong> " . (empty($_SESSION['B'])? "<em>Empty</em>" : htmlspecialchars(implode(", ", $_SESSION['B']))) . "</p>";
        ?>
    </div>
    <?php endif; ?>

</div>
</body>
</html>
