<?php
session_start();

include '../config/DatabaseConn.php';
include '../includes/templates.php';
include '../includes/functions.php';
require_once __DIR__ . '/../config/path.php';

$css_path = PUBLIC_CSS;
$db_conn = DatabaseConn::getInstance();


if (!isset($_SESSION['isAuth']) || $_SESSION['isAuth'] !== true) {
    header('Location: ./login/login.php');
    exit;
}


$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1); // nunca permitir 0 o negativo

$records_per_page = 5;


$userId = $_SESSION['user_id'] ?? null;
if ($userId === null) {
    header('Location: ./login/login.php');
    exit;
}

$stmt = $db_conn->getConnection()->prepare("SELECT COUNT(*) FROM todos WHERE user_id = ?");
$stmt->execute([$userId]);
$total_todos = (int)$stmt->fetchColumn();

// Offset
$start = ($page - 1) * $records_per_page;


$todos = read($db_conn, $userId, $start, $records_per_page);

?>

<?= template_header('Tasks', $css_path) ?>

<div class="content read">
    <a href="create.php" class="create-contact">Create Task</a>

    <!-- TABLE -->
    <table class="task-table">
        <thead>
        <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Status</th>
            <th>Due Date</th>
            <th>Created</th>
            <th>Actions</th>
        </tr>
        </thead>

        <tbody>
        <?php if (!empty($todos)): ?>
            <?php foreach ($todos as $todo): ?>
                <tr>
                    <td><?= htmlspecialchars($todo['title'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($todo['description'], ENT_QUOTES, 'UTF-8') ?></td>

                    <td>
                        <span class="task-status <?= getStatusClass($todo['state']) ?>">
                            <?= htmlspecialchars($todo['state'], ENT_QUOTES, 'UTF-8') ?>
                        </span>
                    </td>

                    <td><?= htmlspecialchars($todo['due_date'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($todo['creation_date'], ENT_QUOTES, 'UTF-8') ?></td>

                    <td class="actions">
                        <a href="update.php?id=<?= urlencode($todo['id']) ?>" class="edit">
                            <i class="fas fa-pen fa-xs"></i>
                        </a>
                        <a href="delete.php?id=<?= urlencode($todo['id']) ?>" class="trash">
                            <i class="fas fa-trash fa-xs"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>

        <?php else: ?>
            <tr>
                <td colspan="6" style="text-align:center;">No hay tareas registradas</td>
            </tr>
        <?php endif; ?>
        </tbody>

    </table>

    <!-- PAGINATION -->
    <nav class="pagination">

        <?php if ($page > 1): ?>
            <a href="index.php?page=<?= $page - 1 ?>">
                <i class="fas fa-angle-double-left"></i>
            </a>
        <?php endif; ?>

        <!-- Página actual -->
        <span class="current-page"><?= $page ?></span>

        <?php if ($page * $records_per_page < $total_todos): ?>
            <a href="index.php?page=<?= $page + 1 ?>">
                <i class="fas fa-angle-double-right"></i>
            </a>
        <?php endif; ?>

    </nav>
</div>

<?= template_footer() ?>
