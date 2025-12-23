<!DOCTYPE html>
<html>  
<head>
    <title>Product List</title>
    <link rel="stylesheet" href="./styles.css">
</head>
<body>
    <h1 class="title">Product List</h1>
   <div class="table">
     <table>
        <tr>
            <th>Name</th>
            <th>Price</th>
        </tr>
        <?php
        $products = ["Laptop" => 999.99, "Smartphone" => 499.49, "Tablet" => 299.99];
        foreach ($products as $key => $value): 
            echo "<tr> 
            <td>$key</td> <td>$$value</td>
            </tr>";
        endforeach;
        ?>
    </table>
   </div>
</body> 
</html>
