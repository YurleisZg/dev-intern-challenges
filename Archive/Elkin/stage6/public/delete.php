<?php
session_start();

include '../config/DatabaseConn.php';
include '../includes/templates.php';
require_once __DIR__ . '/../includes/models.php';
require_once __DIR__ . '/../config/path.php';
$css_path = PUBLIC_CSS;
$db_conn = DatabaseConn::getInstance();
$pdo = $db_conn->getConnection();
$msg = '';

if (!isset($_SESSION['isAuth']) || $_SESSION['isAuth'] !== true) {
    header('Location: ./login/login.php');
    exit;
}

$userId = $_SESSION['user_id'] ?? null;
if ($userId === null) {
    header('Location: ./login/login.php');
    exit;
}

if (isset($_GET['id'])) {
    $todo = Task::findByIdForUser((int)$_GET['id'], (int)$userId);

    if (!$todo) {
        exit('Task doesn\'t exist with that ID!');
    }

    // Confirmación antes de borrar
    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'yes') {
            Task::deleteForUser((int)$_GET['id'], (int)$userId);
            $msg = 'You have deleted the task!';
        } else {
            sleep(1);
            header('Location: index.php');
            exit;
        }
    }
}
?>

<?=template_header('Delete', $css_path)?>

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
