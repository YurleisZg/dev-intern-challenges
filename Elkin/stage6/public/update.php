<?php
session_start();

include '../config/DatabaseConn.php';
include '../includes/templates.php';
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

if(isset($_GET['id'])){
    if(!empty($_POST)){
        $id = $_POST['id'] ?? '';
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $state = $_POST['state'] ?? '';
        $due_date = $_POST['due_date'] ?? '';
        $priority = $_POST['priority'] ?? '';

        $stmt = $pdo->prepare('UPDATE todos SET  title = ?, description = ?, state = ?, due_date = ?, priority = ? WHERE id = ? AND user_id = ?');
        $stmt->execute([$title, $description, $state, $due_date, $priority, $_GET['id'], $userId]);
        if ($stmt->rowCount() === 0) {
            exit('Task not found or you do not have permission to update it.');
        }
        $msg = 'Updated Successfully!';
    }
    // Get the task from the To Do table
    $stmt = $pdo->prepare('SELECT * FROM todos WHERE id = ? AND user_id = ?');
    $stmt->execute([$_GET['id'], $userId]);
    $todo = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$todo) {
        exit('Task doesn\'t exist with that ID!');
    }
}else {
    exit('No ID specified!');
}
?>
<?=template_header('Create', $css_path)?>

<div class="content update">
    <h2>Update Task #<?=$todo['id']?></h2>
    <form action="update.php?id=<?=$todo['id']?>" method="post" class="todo-form">

        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" value="<?=$todo['title']?>"  required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" rows="3" ><?= htmlspecialchars($todo['description']) ?>
            </textarea>
        </div>

        <!-- Estado -->
        <div class="form-group">
            <label for="state">Estado</label>
            <select name="state" id="state" required>
                <option value="todo" <?= $todo['state'] == 'todo' ? 'selected' : '' ?>>todo</option>
                <option value="doing" <?= $todo['state'] == 'doing' ? 'selected' : '' ?>>doing</option>
                <option value="done" <?= $todo['state'] == 'done' ? 'selected' : '' ?>>done</option>
            </select>
        </div>

        <div class="form-group">
            <label for="priority">Priority</label>
            <select name="priority" id="priority" required>
                <option value="high" <?= $todo['priority'] == 'high' ? 'selected' : '' ?>>High</option>
                <option value="mid"  <?= $todo['priority'] == 'mid'  ? 'selected' : '' ?>>Mid</option>
                <option value="low"  <?= $todo['priority'] == 'low'  ? 'selected' : '' ?>>Low</option>
            </select>
        </div>

        <div class="form-group">
            <label for="due_date">Due Date</label>
            <input type="date" name="due_date" id="due_date" value="<?=$todo['due_date']?>">
        </div>

        <div class="form-group">
            <button type="submit">Update task</button>
        </div>
    </form>

    <?php if ($msg): ?>
        <p><?=$msg?></p>
        <meta http-equiv="refresh" content="1;url=index.php">
    <?php endif; ?>
</div>

<?=template_footer()?>
