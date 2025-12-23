<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table of products</title>
</head>
<style>
    table, th, td,caption {
  border:1px solid black;
}
</style>
<body>
    <?php
    //Reto: Representar una lista de productos como array asociativo y mostrarla en una tabla HTML.
       $products = ["Coca Cola"=>5000, "Pepsi"=>5500,"Fanta"=>2000, "Postobon"=>2500, "Jugo Hit"=>1500];

    ?>
    <table style="width:100%">
        <caption>
            List of Drinks
        </caption>
        <tr>
            <th>Name</th>
            <th>Price</th>
        </tr>
        <?php 
            foreach ($products as $key => $value) {
                echo "<tr><td>$key</td><td>$value</td></tr>";
            }
        ?>
    </table>
            
</body>
</html>