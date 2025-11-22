<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form</title>
    <style>
     .container {
        justify-content: center;
        display: flex;
        align-items: center;
        flex-direction: column;
        }

        .container-form{
        display: flex;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Contact Us</h2>
        <form action="resume.php" method="POST" class="form-container">
            <label for="name">Name:</label><br>
            <input type="text" id="name" name="name" required> <br><br>
            <label for="age">Age:</label><br>
            <input type="number" id="age" name="age" required><br><br>
            <label for="address" >Address:</label><br>
            <input type="text" id="address" name="address" required><br><br>
            <label for="phone" >Phone Number:</label><br>
            <input type="tel" id="phone" name="phone" required><br><br>
            <label for="email" >Email:</label><br>
            <input type="email" id="email" name="email" required><br><br>

            <input type="submit" value="Send">
        </form>
    </div>
</body>
</html>