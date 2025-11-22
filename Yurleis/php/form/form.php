<?php 
$username = $_POST['username'] ?? '';
$email    = $_POST['email']    ?? '';
$password = $_POST['password'] ?? '';

$errors = [];
$success = false;

if($_SERVER['REQUEST_METHOD'] === 'POST'){
   
    switch (true) {
        case (empty($username) || empty($email) || empty($password)):
            $errors[] = "All fields are required.";
            break;
        
        case (!filter_var($email, FILTER_VALIDATE_EMAIL)):
            $errors[] = "Invalid email format.";
            break;
        
        case (strlen($password) < 8):
            $errors[] = "Password must be at least 8 characters long.";
            break;
        
        default:
            $success = true;
            break;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Form</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container">
        <h2>Register Form</h2>

        <?php if (!empty($errors)) {
            echo '<div class="error"><ul>';
            foreach ($errors as $error) {
                echo '<li>' . $error . '</li>';
            }
            echo '</ul></div>'; 
        }
            if($success){
            echo '<div class="success">Registration successful!</div>';
            }
        ?>

      <div class="form-container">
          <form action="" method="POST">
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" required><br><br>
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required><br><br>

            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required><br>
            <span class="notification">must be at least 8 characters long</span><br><br>

            <input type="submit" value="Register">
        </form>
      </div>
    </div>
</body>
</html>
