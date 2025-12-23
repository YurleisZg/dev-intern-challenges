<?php 
include_once("../config.php");

$status = mysqli_query($mysqli, "SELECT * FROM task_status");
$get_tasks = mysqli_query($mysqli, "SELECT * FROM tasks");

 if($_SERVER ['REQUEST_METHOD'] === 'POST'){

    $action = $_POST['action'];

    if($action === 'create'){
        $title = $_POST['title'];
        $description = $_POST['description'];
        $due_date = $_POST['due_date'];

        if(!empty($title) && !empty($description) && !empty($due_date)){
            $create_task = $mysqli->prepare("INSERT INTO tasks (title, description, created_at, finish_on, status_id) VALUES (?, ?, CURDATE(), ?, 1)");
            $create_task->bind_param("sss", $title, $description, $due_date);
            $create_task->execute();
        }
    }elseif($action==='update'){
        $id = $_POST['id'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $due_date = $_POST['due_date'];
        $status_id = $_POST['task_status'];

        if(!empty($id) && !empty($title) && !empty($description) && !empty($due_date) && !empty($status_id)){
            $update_task = $mysqli->prepare("UPDATE tasks SET title=?, description=?, finish_on=?, status_id=? WHERE id=?");
            $update_task->bind_param("sssii", $title, $description, $due_date, $status_id, $id);
            $update_task->execute();
            $update_task->close();
        }
    }
}

if(isset($_GET['id'])){
    $id = $_GET['id'];
    if(!empty($id)){
        $delete_task = $mysqli->prepare("DELETE FROM tasks WHERE id=?");
        $delete_task->bind_param("i", $id);
        $delete_task->execute();
        $delete_task->close();
    }
}

$editTask = false;
$taskData =null;

if(isset($_GET['edit_id'])){
    $edit_id = $_GET['edit_id'];
    if(!empty($edit_id)){
        $editTask = true;
        $get_task = $mysqli->prepare("SELECT * FROM tasks WHERE id=?");
        $get_task->bind_param("i", $edit_id);
        $get_task->execute();
        $result = $get_task->get_result();
        $taskData = $result->fetch_assoc();
        $get_task->close();

        if(!$taskData){
            $editTask = false;
        }
    }
}


?>
<!Doctype html>
<html>

<head>
    <title>To Do</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container">
        <h1 class="title">Welcome ToDo Page</h1>
        <p class="description">You can add new tasks with title, description and due date, edit existing tasks including changing their status (created, in progress, completed), and delete tasks they no longer need.</p>
        <button class="btn-add" id="new_task" onclick="document.querySelector('dialog').showModal()">Add New
            Task</button>
        <table class="table-container">
            <tr>
                <th>Task</th>
                <th>Title</th>
                <th>Description</th>
                <th>Created_at</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            <tr>
                <?php while ($row = mysqli_fetch_assoc($get_tasks)) { ?>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['title']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td><?php echo $row['created_at']; ?></td>
                    <td><?php echo $row['finish_on']; ?></td>
                    <td><?php if ($row['status_id'] == 1) {
                        echo "created";
                    } elseif ($row['status_id'] == 2) {
                        echo "In Progress";
                    } elseif ($row['status_id'] == 3) {
                        echo "Completed";
                    }
                    ?>          
                    </td>
                    <td>
                        <a href="to_do.php?edit_id=<?php echo $row['id']; ?>" class="btn-edit">Edit</a>
                        <a href="to_do.php?id=<?php echo $row['id']; ?>" class="btn-delete">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </table>

        <dialog <?php echo $editTask ? 'open' : ''; ?>> 
            <form method="POST" action="" class="dialog-form">
                <h2><?php echo $editTask ? 'Edit Task': 'Add New Task'; ?></h2>

                <input type="hidden" name="action" value="<?php echo $editTask ? 'update' : 'create'; ?>">

                <label for="title">Title</label>
                <input type="text" name="title" id="title" value="<?php echo $editTask ? $taskData['title'] : ''; ?>" required>

                <label for="description">Description</label>
                <textarea name="description" id="description" required><?php echo $editTask ? $taskData['description'] : ''; ?></textarea>
                
                <label for="due_date">Due Date</label>
                <input type="date" name="due_date" id="due_date" value="<?php echo $editTask ? $taskData['finish_on'] : ''; ?>" required>   

                <?php if($editTask){ ?>
                    <input type="hidden" name="id" value="<?php echo $editTask ? $taskData['id'] : ''; ?>">
                    <label for="task_status">Status</label>
                    <select name="task_status" id="task_status" required>
                        <?php while($statusRow = mysqli_fetch_assoc($status)){ ?>
                            <option value="<?php echo $statusRow['id']; ?>" <?php echo $taskData['status_id'] == $statusRow['id'] ? 'selected' : ''; ?>>
                                <?php echo $statusRow['status']; ?>
                            </option>
                        <?php } ?>
                    </select>
                <?php } ?>

                <div class="dialog-buttons">
                    <button type="submit" class="btn-submit"><?php echo $editTask ? 'Update Task' : 'Create Task'; ?></button>
                    <button type="button" class="btn-cancel" onclick="document.querySelector('dialog').close()">Cancel</button>
                </div>
            </form>
        </dialog>
    </div>
</body>

</html>