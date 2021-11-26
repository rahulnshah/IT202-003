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
// echo count($results);
//echo "<pre>" . var_export($results,true) . "</pre>";
$categoryResults = [];
$stmt = $db->prepare("SELECT category FROM Products WHERE visibility = 1"); 
try {
    $stmt->execute();
    $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($r) {
        $categoryResults = $r;
    }
    //echo "<pre>" . var_export($results, true) . "</pre>";
    $categories = [];
    if (count($categoryResults) > 0) // only want to create an extra array (categories) if $results is not empty 
    {
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
$stmt = $db->prepare("SELECT MAX(unit_price) FROM Products WHERE visibility = 1"); // check if this the right query 
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
        $stmt = $db->prepare("SELECT id, name, description, stock, unit_price from Products WHERE name like :name and visibility = 1 LIMIT 10");
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
    //need to be able to sort results by price
    if (isset($_POST["featuredSubmit"])) {
        //echo var_export($_POST["itemName"], true);
        //Solution to save DB calls
       if(!empty($_POST["itemsfeatured"]))
       {
           $selected = se($_POST, "itemsfeatured", "", false);
           if($selected === "Low To High")
           {
            $stmt = $db->prepare("SELECT id, name, description, category, stock, unit_price from Products WHERE visibility = 1 ORDER BY unit_price LIMIT 10");
            try {
                $stmt->execute();
                $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $results = $r; //remove if($r) from here becasuse then if $r is empty, if($r) is false, and $results does not get set to empty array
                //if $results is not empty , I don't see the No results to show message
                //echo var_export($results, true); //<- getting all records when I submit empty string why?
            } catch (PDOException $e) {
                flash("<pre>" . var_export($e, true) . "</pre>");
            }
           }
           else if($selected === "High To Low")
           {
            $stmt = $db->prepare("SELECT id, name, description, category, stock, unit_price from Products WHERE visibility = 1 ORDER BY unit_price DESC LIMIT 10");
            try {
                $stmt->execute();
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
                //feature by new arrivals
           }
        //    echo "<pre>" . var_export($results,true) . "</pre>";
        }
        else
        {
           $results = [];
        }
    }
    // echo count($results);
    
?>
<script>
    function purchase(p_id, cost, stock, u_id, item_name) {
        console.log("TODO purchase item", p_id);
        //TODO create JS helper to update all show-balance elements
        //use AJAX here to send a request and recieve a response. 
        //you will the send the data to a php file in the api folder, which will insert it appropiately, and then
        //return a respnse back to this function and you will display a message here with the .message property of data
        //what should i pass into the purchase function? Can pass in other things such as, porduct_it, user_id, 
        //desired_quentity to be 1, and cost.  Then I can update the quentity with the cost on Cart.php

        //cart.php will only diplay from the databse cart, and will have the view button too    
        let http = new XMLHttpRequest();
            http.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                console.log("responseText:",this.responseText);
                var data = JSON.parse(this.responseText);
                flash(data.message, "success");
               
            }
        };
        http.open("POST", "api/addproduct_to_cart.php", true);
        let data = {
                product_id: p_id,
                unit_price: cost,
                user_id: u_id,
                stock: stock,
                name: item_name
            }
            let q = Object.keys(data).map(key => key + '=' + data[key]).join('&');
            console.log(q);
            http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            //http.responseType = 'json';
            http.send(q);
            // console.log(http);
    }
</script>

<div class="container-fluid">
    <h1>Shop</h1>
    <!-- TODO add filter -->
        <form method="POST">
            <label for="categories">Filter By Category:</label>
            <br>
            <div class="input-group">
                <select class="form-select form-select-sm" name="categories" id="categories">
                    <!-- TODO add php templating here to get all the categories-->
                    <?php foreach ($categories as $category) : ?>
                        <option value="<?php echo $category ?>"><?php echo $category ?></option>
                    <?php endforeach; ?>
                </select>
                <br>
                <input class="btn btn-primary" type="submit" name="categorySubmit" value="Submit">
            </div>
        </form>
        <form method="POST">
            <label for="sortByPrice">Select a Price Range:</label>
            <br>
            <div class="input-group">
                <select class="form-select form-select-sm" name="price" id="sortByPrice">
                    <?php foreach ($prices as $index => $value) : ?>
                            <?php if ($index == 0) : ?>
                                <option><?php echo "$" . 0 . " to " . "$" . $prices[$index]?></option>
                            <?php endif; ?>
                            <?php if ($index < count($prices) - 1) : ?>
                                <option><?php echo "$" . $prices[$index] . " to " . "$" . $prices[$index + 1]?></option>
                            <?php endif; ?>
                    <?php endforeach; ?>
                </select>
                <input class="btn btn-primary" type="submit" name="priceSubmit" value="Submit">
            </div>
        </form>
        <form method="POST">
            <label for="sortByFeatured">Featured:</label>
            <br>
            <div class="input-group">
                <select class="form-select form-select-sm" name="itemsfeatured" id="sortByFeatured">
                    <!-- <?php foreach ($prices as $index => $value) : ?>
                            <?php if ($index == 0) : ?>
                                <option><?php echo "$" . 0 . " to " . "$" . $prices[$index]?></option>
                            <?php endif; ?>
                            <?php if ($index < count($prices) - 1) : ?>
                                <option><?php echo "$" . $prices[$index] . " to " . "$" . $prices[$index + 1]?></option>
                            <?php endif; ?>
                    <?php endforeach; ?> -->
                    <option>Low To High</option>
                    <option>High To Low</option>
                    <!-- <option value="mercedes">Mercedes</option> -->
                </select>
                <input class="btn btn-primary" type="submit" name="featuredSubmit" value="Submit">
            </div>
        </form>
        <form method="POST" class="d-flex">
            <input class="form-control me-2" type="search" name="itemName" placeholder="Item Filter" />
            <input class="btn btn-outline-success" type="submit" value="Search" />
        </form>
        <?php if (count($results) == 0) : ?>
            <p>No results to show</p>
        <?php else : ?>
        <div class="row row-cols-1 row-cols-md-5 g-4">
                <!-- <?php var_export($results); ?> -->
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
                                <!-- <p class="card-text">Stock: <?php se($item, "stock"); ?></p> show stock to user -->
                            </div>
                            <div class="card-footer">
                                Unit Price: $<?php se($item, "unit_price"); ?>
                                <?php if(is_logged_in()) : ?>
                                    <button onclick="purchase('<?php se($item, 'id'); ?>','<?php se($item, 'unit_price'); ?>','<?php se($item, 'stock'); ?>','<?php echo get_user_id(); ?>', '<?php se($item, 'name'); ?>')" class="btn btn-primary">Add To Cart</button>
                                <?php endif; ?>
                                <?php if (has_role("Admin")) : ?>
                                    <a class="btn btn-primary" href="admin/edit_product.php?id=<?php echo $item["id"];?>">Edit</a>
                                    <br><br>
                                <?php endif; ?>
                                <a class="btn btn-primary" href="product_details.php?id=<?php echo $item["id"];?>">View</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
        </div>
        <?php endif; ?>
</div>
<?php
    require(__DIR__ . "/../../partials/flash.php");
?>