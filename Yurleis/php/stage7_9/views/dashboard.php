<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To Do</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>

    <div class="app-container">

        <aside class="sidebar">
            <div class="sidebar-header">
                <h1>ToDo Pro</h1>
            </div>

            <nav class="sidebar-nav">
                <a href="#" class="nav-item active">
                    <span class="icon">✓</span> Tasks
                </a>
                <a href="#" class="nav-item">
                    <span class="icon"></span> Calendar
                </a>
            </nav>

            <div class="sidebar-footer">
                <a href="../../../index.php" class="nav-item">
                Back to Home
                </a>
                <a href="logout.php" class="nav-item">
                   Log Out
                </a>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main-content">

            <!-- TOP BAR -->
            <header class="top-bar">
                <div class="search-box">
                    <span class="icon">🔍</span>
                    <input type="text" placeholder="Search for tasks...">
                </div>

                <div class="user-menu">
                    <span class="user-name"><?= htmlspecialchars($_SESSION['user_name']) ?></span>
                    <span class="user-email"><?= htmlspecialchars($_SESSION['user_email']) ?></span>
                </div>
            </header>

            <!-- PAGE -->
            <div class="content-area">

                <div class="content-header">
                    <h2>My Tasks</h2>
                    <button class="btn btn-primary" onclick="openTaskModal()">+ New Task</button>
                </div>

                <!-- TASK SECTIONS -->
                <?php
                    function renderTaskTable($title, $tasks, $statusId) {
                        $colors = [
                            1 => "status-todo",
                            2 => "status-progress",
                            3 => "status-done"
                        ];

                        echo "<div class='task-section'>";
                        echo "<div class='section-header'>";
                        echo "<h3>$title</h3>";
                        echo "</div>";

                        echo "<table class='task-table'>
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Due Date</th>
                                        <th>Change Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>";

                        foreach ($tasks as $task):
                            $id = $task->getId();
                            $finish = $task->getFinish_on() ? $task->getFinish_on()->format('Y-m-d') : "";
                            $taskJson = htmlspecialchars(json_encode([
                                "id" => $id,
                                "title" => $task->getTitle(),
                                "description" => $task->getDescription(),
                                "finish_on" => $finish
                            ]));
                            
                            echo "<tr>
                                    <td>{$task->getTitle()}</td>
                                    <td>{$task->getDescription()}</td>
                                    <td><span class='date-badge'>{$finish}</span></td>

                                    <td>";
                                    
                            if ($statusId == 1) {
                                echo "<button class='btn-move status-progress' onclick='updateStatus($id,2)'>→ In Progress</button>";
                                echo "<button class='btn-move status-done' onclick='updateStatus($id,3)'>→ Done</button>";
                            } elseif ($statusId == 2) {
                                echo "<button class='btn-move status-todo' onclick='updateStatus($id,1)'>← To Do</button>";
                                echo "<button class='btn-move status-done' onclick='updateStatus($id,3)'>→ Done</button>";
                            } else {
                                echo "<button class='btn-move status-todo' onclick='updateStatus($id,1)'>← To Do</button>";
                                echo "<button class='btn-move status-progress' onclick='updateStatus($id,2)'>→ In Progress</button>";
                            }

                            echo "</td>
                                    <td>
                                        <button class='btn-edit' onclick='openTaskModal($taskJson)'>Edit</button>
                                        <button class='btn-delete' onclick='deleteTask($id)'>Delete</button>
                                    </td>
                                </tr>";
                        endforeach;

                        echo "</tbody></table></div>";
                    }

                    renderTaskTable("To Do", $todoTasks, 1);
                    renderTaskTable("In Progress", $inProgressTasks, 2);
                    renderTaskTable("Done", $doneTasks, 3);
                ?>

            </div>
        </main>
    </div>

    <!-- MODAL (CREATE OR EDIT) -->
    <div id="taskModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeTaskModal()">&times;</span>

            <h2 id="modalTitle">New Task</h2>

            <form id="taskForm">
                <input type="hidden" id="task-id">

                <div class="form-group">
                    <label>Title</label>
                    <input type="text" id="task-title" required>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea id="task-description" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label>Due Date</label>
                    <input type="date" id="task-date">
                </div>

                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>

    <!-- JS FUNCTIONS -->
    <script>
        let editing = false;

        function openTaskModal(task = null) {
            const modal = document.getElementById("taskModal");
            modal.style.display = "block";

            if (task) {
                editing = true;
                document.getElementById("modalTitle").innerText = "Edit Task";

                document.getElementById("task-id").value = task.id;
                document.getElementById("task-title").value = task.title;
                document.getElementById("task-description").value = task.description;
                document.getElementById("task-date").value = task.finish_on;
            } else {
                editing = false;
                document.getElementById("modalTitle").innerText = "New Task";

                document.getElementById("task-id").value = "";
                document.getElementById("task-title").value = "";
                document.getElementById("task-description").value = "";
                document.getElementById("task-date").value = "";
            }
        }

        function closeTaskModal() {
            document.getElementById("taskModal").style.display = "none";
        }

        // Submit for add/edit
        document.getElementById("taskForm").addEventListener("submit", async e => {
            e.preventDefault();

            let data = {
                id: document.getElementById("task-id").value,
                title: document.getElementById("task-title").value,
                description: document.getElementById("task-description").value,
                finish_on: document.getElementById("task-date").value
            };

            const action = editing ? "update" : "create";

            const response = await fetch(`api/tasks.php?action=${action}`, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.success) {
                location.reload();
            } else {
                alert(result.message);
            }
        });

        async function updateStatus(id, status) {
            const res = await fetch("api/tasks.php?action=update-status", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ task_id: id, status_id: status })
            });

            const json = await res.json();

            if (json.success) location.reload();
            else alert(json.message);
        }

        async function deleteTask(id) {
            if (!confirm("Delete this task?")) return;

            const res = await fetch("api/tasks.php?action=delete", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ task_id: id })
            });

            const json = await res.json();

            if (json.success) location.reload();
            else alert(json.message);
        }
    </script>

</body>
</html>
