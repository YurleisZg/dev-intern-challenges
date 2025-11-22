<!DOCTYPE html>
<hmtl>
    <head>
        <title>Practice</title>
    </head>
    <body>
        <h1 class="container">Welcome to aventure!</h1>
        <?php echo "<p>Hello World!, Today is " . date("Y-m-d") . "</p>";

            //two numbers and choose the greater one
            $num1 = 10;
            $num2 = 7;
            $greater = ($num1 > $num2) ? $num1 : $num2;
            echo "<p>The greater number between $num1 and $num2 is: $greater</p>";
            $a = 5;

           echo phpinfo();
        ?>
    </body>
</html>
