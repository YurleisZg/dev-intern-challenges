<?php
include './operations.php';
include '../../../back_button.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Fruit Set Challenge</title>
</head>

<body>
    <h2 class="title">Fruit Set Challenge</h2>
    <p class="description">
            Select fruits to add to Basket A and Basket B. Then, perform various set operations
            like Union, Intersection, Difference, Subset check, and Jaccard similarity.
    </p>

    <div class="row">
        <div class="card basket-card">
            <h3 class="card-title">Basket A</h3>
            <div class="basket-columns">

                <div class="basket-column">
                    <h4 class="column-title">Available</h4>

                    <?php foreach ($fruits as $fruit): ?>
                        <label class="checkbox-row">
                            <input
                                type="checkbox"
                                name="basketA[]"
                                value="<?php echo htmlspecialchars($fruit); ?>"
                                form="basketA-form"
                                <?php if (in_array($fruit, $basketA)) echo 'checked'; ?>
                            >
                            <span><?php echo htmlspecialchars($fruit); ?></span>
                        </label>
                    <?php endforeach; ?>

                    <div class="basket-actions">
                        <!-- Add basket-->
                        <form method="post" id="basketA-form" class="basket-form">
                            <?php foreach ($basketB as $fruitB): ?>
                                <input type="hidden" name="basketB[]" value="<?php echo htmlspecialchars($fruitB); ?>">
                            <?php endforeach; ?>
                            <button type="submit" class="btn-primary">Add basket</button>
                        </form>

                        <!-- Add alL -->
                        <form method="post" class="basket-bulk-form">
                            <?php foreach ($fruits as $fruit): ?>
                                <input type="hidden" name="basketA[]" value="<?php echo htmlspecialchars($fruit); ?>">
                            <?php endforeach; ?>
                            <?php foreach ($basketB as $fruitB): ?>
                                <input type="hidden" name="basketB[]" value="<?php echo htmlspecialchars($fruitB); ?>">
                            <?php endforeach; ?>
                            <button type="submit" class="secondary-btn secondary-btn--sm">Add all</button>
                        </form>
                    </div>
                </div>

                <!-- IN BASKET A -->
                <div class="basket-column">
                    <h4 class="column-title">In Basket A</h4>
                    <div class="basket-list">
                        <?php if (!empty($basketA)): ?>
                            <?php foreach ($basketA as $index => $fruit): ?>
                                <span class="basket-item">
                                    <?php echo htmlspecialchars($fruit); ?>
                                    <?php if ($index < count($basketA) - 1) echo ','; ?>
                                </span>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="empty-text">No fruits in Basket A.</div>
                        <?php endif; ?>
                    </div>

                    <form method="post" class="clear-form">
                        <?php foreach ($basketB as $fruitB): ?>
                            <input type="hidden" name="basketB[]" value="<?php echo htmlspecialchars($fruitB); ?>">
                        <?php endforeach; ?>
                        <input type="hidden" name="clear" value="A">
                        <button type="submit" class="secondary-btn">Remove A</button>
                    </form>
                </div>

            </div>
        </div>

        <div class="card basket-card">
            <h3 class="card-title">Basket B</h3>
            <div class="basket-columns">

                <div class="basket-column">
                    <h4 class="column-title">Available</h4>

                    <?php foreach ($fruits as $fruit): ?>
                        <label class="checkbox-row">
                            <input
                                type="checkbox"
                                name="basketB[]"
                                value="<?php echo htmlspecialchars($fruit); ?>"
                                form="basketB-form"
                                <?php if (in_array($fruit, $basketB)) echo 'checked'; ?>
                            >
                            <span><?php echo htmlspecialchars($fruit); ?></span>
                        </label>
                    <?php endforeach; ?>

                    <div class="basket-actions">
                        <!-- Add basket (seleccionados) -->
                        <form method="post" id="basketB-form" class="basket-form">
                            <?php foreach ($basketA as $fruitA): ?>
                                <input type="hidden" name="basketA[]" value="<?php echo htmlspecialchars($fruitA); ?>">
                            <?php endforeach; ?>
                            <button type="submit" class="btn-primary">Add basket</button>
                        </form>

                        <!-- Add all (todas las frutas) -->
                        <form method="post" class="basket-bulk-form">
                            <?php foreach ($fruits as $fruit): ?>
                                <input type="hidden" name="basketB[]" value="<?php echo htmlspecialchars($fruit); ?>">
                            <?php endforeach; ?>
                            <?php foreach ($basketA as $fruitA): ?>
                                <input type="hidden" name="basketA[]" value="<?php echo htmlspecialchars($fruitA); ?>">
                            <?php endforeach; ?>
                            <button type="submit" class="secondary-btn secondary-btn--sm">Add all</button>
                        </form>
                    </div>
                </div>

                <!-- IN BASKET B -->
                <div class="basket-column">
                    <h4 class="column-title">In Basket B</h4>
                    <div class="basket-list">
                        <?php if (!empty($basketB)): ?>
                            <?php foreach ($basketB as $index => $fruit): ?>
                                <span class="basket-item">
                                    <?php echo htmlspecialchars($fruit); ?>
                                    <?php if ($index < count($basketB) - 1) echo ','; ?>
                                </span>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="empty-text">No fruits in Basket B.</div>
                        <?php endif; ?>
                    </div>

                    <form method="post" class="clear-form">
                        <?php foreach ($basketA as $fruitA): ?>
                            <input type="hidden" name="basketA[]" value="<?php echo htmlspecialchars($fruitA); ?>">
                        <?php endforeach; ?>
                        <input type="hidden" name="clear" value="B">
                        <button type="submit" class="secondary-btn">Remove B</button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- ================= OPERATIONS & RESULTS ================= -->
    <div class="row">
        <div class="card">
            <h3 class="card-title">Operations</h3>
            <div class="operations">
                <?php
                function op_form($opValue, $label, $basketA, $basketB) {
                    ?>
                    <form method="post" class="op-form">
                        <?php foreach ($basketA as $fruitA): ?>
                            <input type="hidden" name="basketA[]" value="<?php echo htmlspecialchars($fruitA); ?>">
                        <?php endforeach; ?>
                        <?php foreach ($basketB as $fruitB): ?>
                            <input type="hidden" name="basketB[]" value="<?php echo htmlspecialchars($fruitB); ?>">
                        <?php endforeach; ?>
                        <input type="hidden" name="op" value="<?php echo htmlspecialchars($opValue); ?>">
                        <button type="submit"><?php echo htmlspecialchars($label); ?></button>
                    </form>
                    <?php
                }

                op_form('union', 'Union (A ∪ B)', $basketA, $basketB);
                op_form('intersection', 'Intersection (A ∩ B)', $basketA, $basketB);
                op_form('diffAB', 'Difference (A - B)', $basketA, $basketB);
                op_form('diffBA', 'Difference (B - A)', $basketA, $basketB);
                op_form('subset', 'Is Subset? (A ⊆ B)', $basketA, $basketB);
                op_form('jaccard', 'Similarity % (Jaccard)', $basketA, $basketB);
                op_form('symdiff', 'Symmetric Difference (A XOR B)', $basketA, $basketB);
                ?>
            </div>
        </div>

        <div class="card">
            <h3 class="card-title">Results</h3>
            <div class="results-box">
                <?php if ($result !== ''): ?>
                    <p><strong>Result:</strong> <?php echo htmlspecialchars($result); ?></p>
                    <p><strong>Explanation:</strong> <?php echo htmlspecialchars($result_explanation); ?></p>
                <?php else: ?>
                    <p>No operation selected yet.</p>
                    <p>Select fruits in Basket A and Basket B, then click an operation.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
