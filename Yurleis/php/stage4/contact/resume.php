<?php 
    $name = $_POST["name"];
    $age = $_POST["age"];
    $address = $_POST["address"];
    $phone = $_POST["phone"];
    $email = $_POST["email"];

    $errors = [];

    if($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        if(empty($name) || empty($age) || empty($address) || empty($phone) || empty($email))
        {
            $errors[] = "All fields are required.";
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $errors[] = "Invalid email format.";
        }
        if(!is_numeric($age) || $age <= 0)
        {
            $errors[] = "Age must be a positive number.";
        }  
        
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resume Submission</title>
    <style>
        body {
            margin: 20px;
            justify-content: center;
            display: flex;
            align-items: center;
            flex-direction: column;
        }
        h2 {
            color: #333;
        }
        p {
            font-size: 16px;
        }
    </style>
</head>
<body>
    <?php 
    echo "<h2>Resume Submission Result</h2>";
        if(!empty($errors)){
            foreach($errors as $error)
            {
                echo "<p>" . $error . "</p>";
            }
        }
        else
        {
            echo "<p>Thank you, " .$name. ". Your resume has been submitted successfully!</p>";
            echo "<ul>";
            echo "<li>Name: " . $name . "</li>";
            echo "<li>Age: " . $age . "</li>";
            echo "<li>Address: " . $address . "</li>";
            echo "<li>Phone Number: " . $phone . "</li>";
            echo "<li>Email: " . $email . "</li>";
            echo "</ul>";
        }
    ?>
</body>