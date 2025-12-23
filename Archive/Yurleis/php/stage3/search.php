
<!DOCTYPE html>
<html>  
<head>
    <title>Search in Array</title>
    <link rel="stylesheet" href="./styles.css">
</head>
<body>
    <h1 class="title">Search in Array</h1>
   <div class="results">
        <ul>
        <?php
        $items = [ "Apple", "Banana","Orange", "Grapes", "Watermelon"];
        
        $searchTerm = "wa"; 
        $foundItems = array_filter($items, function($item) use ($searchTerm) {
            return stripos($item, $searchTerm) !== false;
        }); 
        if (!empty($foundItems)) {
            foreach ($foundItems as $item) {
                echo "<li>" . $item . "</li>";
            }
        } else {
            echo "<li>No items found.</li>";
        }
        ?>
        </ul>
   </div>
</body> 
</html>