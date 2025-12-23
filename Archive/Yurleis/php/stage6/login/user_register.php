<?php
include '../config.php';

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $name = $_POST['name'];
    $username = $_POST['username'];
    $age = $_POST['age'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $register_user = $mysqli->prepare("INSERT INTO user (name, address, age, email, password, registered_at, phone_number, username)
    VALUES (?, ?, ?, ?, ?, CURDATE(), ?, ?)");

    $register_user->bind_param("ssissss", $name, $address, $age, $email, $password, $phone, $username);
    $register_user->execute();
    $register_user->close();
}

$get_users = $mysqli->prepare("SELECT * FROM user");
$get_users->execute();
$users = $get_users->get_result();
$get_users->close();

$id = $_GET['id'];
$delete_user = $mysqli->prepare("DELETE FROM user WHERE id = ?");
$delete_user->bind_param("i", $id);
$delete_user->execute();
$delete_user->close();

?>

<!DOCTYPE html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Register</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1 class="title">User Registration</h1>
    <p class="description">
    Manage user registrations below.
    </p>
    <div class="header-actions">
        <button class="dialog-button" onclick="document.getElementById('form-register').parentElement.showModal()">New User</button>
    </div>
    <dialog class="dialog">
        <h2>Register New User</h2>
        <form method="POST" action="" id="form-register">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="age">Age:</label>
            <input type="number" id="age" name="age" required>

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>

            <label for="phone">Phone Number:</label>
            <input type="tel" id="phone" name="phone" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit" name="register">Register</button>
            <button type="button" onclick="document.getElementById('form-register').parentElement.close()">Cancel</button>
        </form>
    </dialog>

    <table class="table-container">
        <tr>
            <th>Name</th>
            <th>Username</th>
            <th>Age</th>
            <th>Address</th>
            <th>Phone Number</th>
            <th>Email</th>
            <th>Password</th>
            <th>Actions</th>
        </tr>
        <?php while($user = $users->fetch_assoc()): ?>
        <td>
            <?php echo $user['name']; ?>
        </td>
            <td>
                <?php echo $user['username']; ?>
            </td>
            <td>
                <?php echo $user['age']; ?>
            </td>
            <td>
                <?php echo $user['address']; ?>
            </td>
            <td>
                <?php echo $user['phone_number']; ?>
            </td>
            <td>
                <?php echo $user['email']; ?>
            </td>
            <td>
                <?php echo $user['password']; ?>
            </td>
            <td>
                <button onclick="window.location.href='?id=<?php echo $user['id']; ?>'">Delete</button>
            </td>
        </tr>
        <?php endwhile; ?>
</body>
</html>