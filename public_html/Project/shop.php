<?php
require(__DIR__ . "/../../partials/nav.php");

$results = [];
$db = getDB();
$stmt = $db->prepare("SELECT id, name, description, category, unit_price, stock FROM Products WHERE visibility = 1 LIMIT 10"); // check if this the right query 
try {
    $stmt->execute();
    $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($r) {
        $results = $r;
    }
} catch (PDOException $e) {
    flash("<pre>" . var_export($e, true) . "</pre>");
}
$categoryResults = [];
$stmt = $db->prepare("SELECT category FROM Products WHERE visibility = 1"); // check if this the right query 
try {
    $stmt->execute();
    $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($r) {
        $categoryResults = $r;
    }
    //echo "<pre>" . var_export($results, true) . "</pre>";
    if (count($categoryResults) > 0) // only want to create an extra array (categories) if $results is not empty 
    {
        $categories = [];
        foreach ($categoryResults as $categoryResult) {
            $aCategory = se($categoryResult, "category", "", false);
            if (!in_array($aCategory, $categories)) {
                array_push($categories, $aCategory);
            }
        }
    }
    // echo "<pre>" . var_export($categories, true) . "</pre>";
} catch (PDOException $e) {
    flash("<pre>" . var_export($e, true) . "</pre>");
}
//prices 
$prices = [];
//get the max price from database and print it out 
$stmt = $db->prepare("SELECT MAX(unit_price) FROM Products"); // check if this the right query 
try {
    $stmt->execute();
    $r = $stmt->fetch();
    $r = (int) ceil((float) se($r, "MAX(unit_price)","", false));
    //run a while loop hear and push ranges into the $prices array
    $counter = 0;
    while($counter < $r)
    {
        $counter = $counter + 50;
        array_push($prices,$counter);
    }
    //echo "<pre>" . var_export($prices, true) . "</pre>";
    //echo "<pre>" . var_export($categories, true) . "</pre>";
} catch (PDOException $e) {
    flash("<pre>" . var_export($e, true) . "</pre>");
}
//check if a serch was submitted then make query, reset $results and let the HTML hand everything else
if (isset($_POST["itemName"])) {
     //echo var_export($_POST["itemName"], true);
    if(strlen($_POST["itemName"]) > 0)
    {
        $searchedItem = se($_POST,"itemName","",false);
        $stmt = $db->prepare("SELECT id, name, description, stock, unit_price from Products WHERE name like :name LIMIT 10");
        try {
            $stmt->execute([":name" => "%" . $searchedItem . "%"]);
            $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $results = $r; //remove if($r) from here becasuse then if $r is empty, if($r) is false, and $results does not get set to empty array
            //if $results is not empty , I don't see the No results to show message
            //echo var_export($results, true); //<- getting all records when I submit empty string why?
        } catch (PDOException $e) {
            flash("<pre>" . var_export($e, true) . "</pre>");
        }
    }
    else
    {
        $results = [];
    }
}
if (isset($_POST["categorySubmit"])) {
        //echo var_export($_POST["itemName"], true);
       if(!empty($_POST["categories"]))
       {
           $selected = se($_POST,"categories","",false);
           $stmt = $db->prepare("SELECT id, name, description, category, stock, unit_price from Products WHERE category = :category LIMIT 10");
            try {
                $stmt->execute([":category" => $selected]);
                $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $results = $r; //remove if($r) from here becasuse then if $r is empty, if($r) is false, and $results does not get set to empty array
                //if $results is not empty , I don't see the No results to show message
                //echo var_export($results, true); //<- getting all records when I submit empty string why?
            } catch (PDOException $e) {
                flash("<pre>" . var_export($e, true) . "</pre>");
            }
       }
       else
       {
           $results = [];
       }
    }
    if (isset($_POST["priceSubmit"])) {
        //echo var_export($_POST["itemName"], true);
        //Solution to save DB calls
       if(!empty($_POST["price"]))
       {
           $selected = se($_POST, "price", "", false);
           preg_match_all("!\d+!", $selected, $matches);
           $lowPriceBound = intval($matches[0][0]);
           $highPriceBound = intval($matches[0][1]);

            //var_dump($matches);
           //var_dump($selected);
          $stmt = $db->prepare("SELECT id, name, description, category, stock, unit_price from Products WHERE unit_price BETWEEN :lowPriceBound AND :highPriceBound LIMIT 10");
            try {
                $stmt->execute([":lowPriceBound" => $lowPriceBound, ":highPriceBound" => $highPriceBound]);
                $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $results = $r; //remove if($r) from here becasuse then if $r is empty, if($r) is false, and $results does not get set to empty array
                //if $results is not empty , I don't see the No results to show message
                //echo var_export($results, true); //<- getting all records when I submit empty string why?
            } catch (PDOException $e) {
                flash("<pre>" . var_export($e, true) . "</pre>");
            }
       }
       else
       {
           $results = [];
       }
    }
?>
<script>
    function purchase(item) {
        console.log("TODO purchase item", item);
        //TODO create JS helper to update all show-balance elements
    }
    function goToEditProducts(idVal)
    {
        document.location.href = "admin/edit_product.php?id=" + idVal;
    }
</script>

<div class="container-fluid">
    <h1>Shop</h1>
    <!-- TODO add filter -->
    <form method="POST">
        <label for="categories">Filter By Category:</label>
        <select name="categories" id="categories">
            <!-- TODO add php templating here to get all the categories-->
            <?php foreach ($categories as $category) : ?>
                <option value="<?php echo $category ?>"><?php echo $category ?></option>
            <?php endforeach; ?>
        </select>
        <br><br>
        <input type="submit" name="categorySubmit" value="Submit">
    </form>
    <form method="POST">
        <input type="search" name="itemName" placeholder="Item Filter" />
        <input type="submit" value="Search" />
    </form>
    <form method="POST">
        <label for="sortByPrice">Sort By:</label>
        <select name="price" id="sortByPrice">
            <?php foreach ($prices as $index => $value) : ?>
                    <?php if ($index == 0) : ?>
                        <option><?php echo "$" . 0 . " to " . "$" . $prices[$index]?></option>
                    <?php endif; ?>
                    <?php if ($index < count($prices) - 1) : ?>
                        <option><?php echo "$" . $prices[$index] . " to " . "$" . $prices[$index + 1]?></option>
                    <?php endif; ?>
            <?php endforeach; ?>
        </select>
        <br><br>
        <input type="submit" name="priceSubmit" value="Submit">
    </form>
    <div class="row row-cols-1 row-cols-md-5 g-4">
        <?php if (count($results) == 0) : ?>
            <p>No results to show</p>
        <?php else : ?>
            <?php foreach ($results as $item) : ?>
                <div class="col">
                    <div class="card bg-light">
                        <div class="card-header">
                            Placeholder
                        </div>
                        <!-- <?php if (se($item, "image", "", false)) : ?>
                        <img src="<?php se($item, "image"); ?>" class="card-img-top" alt="...">
                    <?php endif; ?> -->
                        <div class="card-body">
                            <h5 class="card-title">Name: <?php se($item, "name"); ?></h5>
                            <p class="card-text">Description: <?php se($item, "description"); ?></p>
                            <p class="card-text">Category: <?php se($item, "category"); ?></p>
                            <p class="card-text">Stock: <?php se($item, "stock"); ?></p> <!-- show stock to user-->
                        </div>
                        <div class="card-footer">
                            Unit Price: $<?php se($item, "unit_price"); ?>
                            <button onclick="purchase('<?php se($item, 'id'); ?>')" class="btn btn-primary">Purchase</button>
                            <?php if (has_role("Admin")) : ?>
                                <button onclick="goToEditProducts('<?php se($item, 'id'); ?>')" class="btn btn-primary">Edit</button>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<?php
        require(__DIR__ . "/../../partials/flash.php");
?>