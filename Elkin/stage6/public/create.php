<?php
include '../config/DatabaseConn.php';
include '../includes/templates.php';
$db_conn = DatabaseConn::getInstance();
$pdo = $db_conn->getConnection();
$msg = '';
// Check if POST data is not empty
if (!empty($_POST)) {

    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $state = $_POST['state'] ?? '';
    $due_date = $_POST['due_date'] ?? '';
    $priority = $_POST['priority'] ?? '';
    // Insert new record into the contacts table
    $stmt = $pdo->prepare('INSERT INTO todos (title, description, state, due_date, priority)
    VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$title, $description, $state, $due_date, $priority]);
    // Output message
    $msg = 'Created Successfully!';

}
?>
<?=template_header('Create')?>

<div class="content update">
    <h2>Create Task</h2>
    <form action="create.php" method="post" class="todo-form">

        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title"  required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" rows="3" ></textarea>
        </div>

        <!-- Estado -->
        <div class="form-group">
            <label for="state">Estado</label>
            <select name="state" id="state" required>
                <option value="todo">todo</option>
                <option value="doing">doing</option>
                <option value="done">done</option>
            </select>
        </div>

        <div class="form-group">
            <label for="state">Priority</label>
            <select name="priority" id="priority" required>
                <option value="high">high</option>
                <option value="mid">mid</option>
                <option value="low">low</option>
            </select>
        </div>

        <div class="form-group">
            <label for="due_date">Due Date</label>
            <input type="date" name="due_date" id="due_date">
        </div>

        <div class="form-group">
            <button type="submit">Create task</button>
        </div>
    </form>

    <?php if ($msg): ?>
        <p><?=$msg?></p>
        <meta http-equiv="refresh" content="1;url=index.php">
    <?php endif; ?>
</div>

<?=template_footer()?>
