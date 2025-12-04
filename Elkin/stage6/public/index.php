<?php
include '../config/DatabaseConn.php';
include '../includes/templates.php';
include '../includes/functions.php';

$db_conn = DatabaseConn::getInstance();

// Page and limits
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$records_per_page = 5;

// Real total count
$stmt = $db_conn->getConnection()->query("SELECT COUNT(*) FROM todos");
$total_todos = $stmt->fetchColumn();

// Offset
$start = ($page - 1) * $records_per_page;

// Get only the records for this page
$todos = read($db_conn, $start, $records_per_page);
?>

<?= template_header('Tasks') ?>

<div class="content read">

    <header class="page-header">
        <h2>Tasks</h2>
        <a href="./create.php" class="create-contact">+ Add Task</a>
    </header>

    <!-- TABLE -->
    <table class="task-table">
        <thead>
        <tr>
            <th>#</th>
            <th>Title</th>
            <th>Description</th>
            <th>Status</th>
            <th>Due Date</th>
            <th>Created</th>
            <th>Actions</th>
        </tr>
        </thead>

        <tbody>
        <?php foreach ($todos as $todo): ?>
            <tr>
                <td><?= $todo['id'] ?></td>
                <td><?= $todo['title'] ?></td>
                <td><?= $todo['description'] ?></td>

                <!-- colored state -->
                <td>
                    <span class="task-status <?= getStatusClass($todo['state']) ?>">
                        <?= $todo['state'] ?>
                    </span>
                </td>

                <td><?= $todo['due_date'] ?></td>
                <td><?= $todo['creation_date'] ?></td>

                <td class="actions">
                    <a href="update.php?id=<?= $todo['id'] ?>" class="edit">
                        <i class="fas fa-pen fa-xs"></i>
                    </a>
                    <a href="delete.php?id=<?= $todo['id'] ?>" class="trash">
                        <i class="fas fa-trash fa-xs"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- PAGINATION -->
    <nav class="pagination">
        <?php if ($page > 1): ?>
            <a href="index.php?page=<?= $page - 1 ?>">
                <i class="fas fa-angle-double-left"></i>
            </a>
        <?php endif; ?>

        <?php if ($page * $records_per_page < $total_todos): ?>
            <a href="index.php?page=<?= $page + 1 ?>">
                <i class="fas fa-angle-double-right"></i>
            </a>
        <?php endif; ?>
    </nav>


</div>

<?= template_footer() ?>
