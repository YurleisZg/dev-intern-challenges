<?php
include '../config/DatabaseConn.php';
include '../includes/templates.php';

$db_conn = DatabaseConn::getInstance();
$pdo = $db_conn->getConnection();
$msg = '';

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare('SELECT * FROM todos WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $todo = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$todo) {
        exit('Task doesn\'t exist with that ID!');
    }

    // Confirmación antes de borrar
    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'yes') {
            $stmt = $pdo->prepare('DELETE FROM todos WHERE id = ?');
            $stmt->execute([$_GET['id']]);
            $msg = 'You have deleted the task!';
        } else {
            sleep(1);
            header('Location: index.php');
            exit;
        }
    }
}
?>

<?=template_header('Delete')?>

<div class="content delete">
    <h2>Delete Task #<?=$todo['id']?></h2>
    <?php if ($msg): ?>
        <p><?=$msg?></p>
        <meta http-equiv="refresh" content="1;url=index.php">
    <?php else: ?>
        <p>Are you sure you want to delete task #<?=$todo['id']?>?</p>
        <div class="yesno">
            <a href="delete.php?id=<?=$todo['id']?>&confirm=yes">Yes</a>
            <a href="delete.php?id=<?=$todo['id']?>&confirm=no">No</a>
        </div>
    <?php endif; ?>

</div>

<?=template_footer()?>
