<?php
require(__DIR__ . "/../../partials/nav.php");

$results = [];
$db = getDB();
$upOrdown = se($_GET, "itemsfeatured", "asc", false);
//allowed list
if (!in_array($upOrdown, ["asc", "desc"])) {
    $upOrdown = "asc"; //default value, prevent sql injection
}
//need to a value attric but to the priceRanges
$aPriceRange = se($_GET, "price", "", false);
$aCategory = se($_GET, "categories", "", false);
$aRatingRange = se($_GET, "ratings", "", false);
$itemName = se($_GET, "itemName", "", false);
$query = "SELECT id, name, description, category, unit_price, stock FROM Products";
$whereQuery = [];
$params = [];

if(!empty($aCategory))
{
    array_push($whereQuery,"category = :category");
    $params[":category"] = $aCategory;
}
if(!empty($aPriceRange))
{
    preg_match_all("!\d+!", $aPriceRange, $matches);
    if(count($matches) > 0 && count($matches[0]) >= 2)
    {
        $lowPriceBound = intval($matches[0][0]);
        $highPriceBound = intval($matches[0][1]);
        array_push($whereQuery,"unit_price BETWEEN :lowPriceBound AND :highPriceBound");
        $params[":lowPriceBound"] = $lowPriceBound;
        $params[":highPriceBound"] = $highPriceBound;
    }
}
if(!empty($aRatingRange))
{
        $rateArr = explode(" ", $aRatingRange);
        if(count($rateArr) >= 3)
        {
            $rate_1 = $rateArr[0];
            $rate_2 = $rateArr[2];
            array_push($whereQuery,"average_rating BETWEEN :rate_1 AND :rate_2");
            $params[":rate_1"] = $rate_1;
            $params[":rate_2"] = $rate_2;
        }
}
if(!empty($itemName))
{
    array_push($whereQuery,"name like :name");
    $params[":name"] = "%" . $itemName . "%";
}
$query .= " where visibility = 1 and stock >= 0 and unit_price > 0 ";
if(count($whereQuery) > 0)
{
    $query .= " and " . join(" and ",$whereQuery);
}
$total_query = str_replace("id, name, description, category, unit_price, stock","count(1) as total",$query);
$per_page = 10;
paginate($total_query, $params, $per_page); //$per_page defualts to 10 in the paginate function
if((int) $total_pages > 0)
{
    if (!empty($upOrdown)) {
        $query .= " ORDER BY unit_price $upOrdown"; //be sure you trust these values, I validate via the in_array checks above
    }
    $query .= " LIMIT :offset, :count";
    $params[":offset"] = $offset;
    $params[":count"] = $per_page;
    $stmt = $db->prepare($query); // select prod it and name and inner join where prod id = cart.prod_id 
    foreach ($params as $key => $value) {
        $type = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
        $stmt->bindValue($key, $value, $type);
    }
    $params = null;
    try {
        $stmt->execute($params); //specify the user_id so I get only products in tat user's cart
        $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $results = $r;
    } catch (PDOException $e) {
        flash("<pre>" . var_export($e, true) . "</pre>");
    }
}
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
//ratings
$theRatings = [];
//select the minimum rating, select the maximum rating from Produtcs then order the DISTINCT ratings and select the mimimal difference 
// between the first two ratings 
$stmt = $db->prepare("SELECT DISTINCT(average_rating) as average_Rating FROM Products WHERE visibility = 1 ORDER BY average_Rating ASC"); //could have used the distinct keyword here 
try {
    $stmt->execute();
    $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $avgRatings = $r;
        //get the date a index 0. 
    if(count($avgRatings) > 0) //count($dates) could be 0 if we have a new user 
    {
        $minAvgRating = floatval($avgRatings[0]["average_Rating"]);
        $maxAvgRating = floatval($avgRatings[count($avgRatings) - 1]["average_Rating"]);
        $diffBetweenAvgRatings = 0;
       //if the dates array has only one element, than difference is ofcourse 0, but if length is > 1, difference 
        //cannot be 0 between two elements
        if(count($avgRatings) > 1)
        {
            $diffBetweenAvgRatings = floatval($avgRatings[1]["average_Rating"]) - $minAvgRating;
        }
                //push in date ranges here , if diffBetween dates is 0, same += 0 = same and old = leates so array will be empty
        array_push($theRatings, $minAvgRating);
        while($minAvgRating < $maxAvgRating) //if they are the same this won't run but I will still push the minavgRating into theRatings array
        {
        //     error_log("date: " . date_add(date_create($oldestDate),date_interval_create_from_date_string(strval((int) $diffBetweenDates * 2) . " days"))->format("Y-m-d"));
            $minAvgRating += $diffBetweenAvgRatings;
            array_push($theRatings,$minAvgRating);
        }
    }
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
    $r = (int) ceil((float) se($r, "MAX(unit_price)", "", false));
    //run a while loop hear and push ranges into the $prices array
    $counter = 0;
    while ($counter < $r) {
        $counter = $counter + 50;
        array_push($prices, $counter);
    }
    //echo "<pre>" . var_export($prices, true) . "</pre>";
    //echo "<pre>" . var_export($categories, true) . "</pre>";
} catch (PDOException $e) {
    flash("<pre>" . var_export($e, true) . "</pre>");
}
?>
<script>
    function purchase(p_id, cost, stock, u_id, item_name) {
        //console.log("TODO purchase item", p_id);
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
                console.log("responseText:", this.responseText);
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
    <form id="myForm">
        <div class="col">
            <div class="input-group">
                <div class="input-group-text">Filter By Category:</div>
                    <select class="form-control" name="categories" form="myForm">
                            <option></option>
                        <!-- TODO add php templating here to get all the categories-->
                        <?php foreach ($categories as $category) : ?>
                            <option value="<?php echo $category ?>"><?php echo $category ?></option>
                        <?php endforeach; ?>
                    </select>
            
                <div class="input-group-text">Rating Between:</div>
                    <select class="form-control" name="ratings" form="myForm">
                            <option></option>
                        <!-- TODO add php templating here to get all the categories-->
                        <?php if (count($theRatings) == 1): ?>
                            <option value="<?php echo $theRatings[0] . " and " . ($theRatings[0] + 1);?>"><?php echo $theRatings[0] . " and " . ($theRatings[0] + 1);?></option>
                        <?php else: ?>
                            <?php for($i = 0; $i < count($theRatings) - 1; $i++) : ?>
                                <option value="<?php echo $theRatings[$i] . " and " . $theRatings[$i + 1];?>"><?php echo $theRatings[$i] . " and " . $theRatings[$i + 1];?></option>
                            <?php endfor; ?>
                        <?php endif; ?>
                    </select>
            
            
                <div class="input-group-text">Select a Price Range:</div>
                    <select class="form-control" name="price" form="myForm">
                            <option></option>
                        <?php foreach ($prices as $index => $value) : ?>
                            <?php if ($index == 0) : ?>
                                <option value="<?php echo "$" . 0 . " to " . "$" . $prices[$index] ?>"><?php echo "$" . 0 . " to " . "$" . $prices[$index] ?></option>
                            <?php endif; ?>
                            <?php if ($index < count($prices) - 1) : ?>
                                <option value="<?php echo "$" . $prices[$index] . " to " . "$" . $prices[$index + 1] ?>"><?php echo "$" . $prices[$index] . " to " . "$" . $prices[$index + 1] ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
            
                <div class="input-group-text">Featured:</div>
                    <select class="form-control" name="itemsfeatured" form="myForm">
                        <option></option>
                        <option value="asc">Low To High</option>
                        <option value="desc">High To Low</option>
                    </select>
                <input class="form-control me-2" type="search" form="myForm" name="itemName" placeholder="Item Filter" />
            </div>
        </div>
        <div class="col">
            <div class="input-group">
                <input type="submit" class="btn btn-primary" value="Apply" />
            </div>
        </div>
    </form>
    <?php if (count($results) == 0) : ?>
        <p>No results to show</p>
    <?php else : ?>
        <div class="row row-cols-1 row-cols-md-5 g-4">
            <!-- <?php var_export($results); ?> -->
            <?php foreach ($results as $item) : ?>
                <div id='cardwithID<?php echo $item["id"]; ?>' class="col">
                    <div id="aCard" class="card bg-light">
                        <!-- <div class="card-header">
                            Placeholder
                        </div> -->
                        <!-- <?php if (se($item, "image", "", false)) : ?>
                            <img src="<?php se($item, "image"); ?>" class="card-img-top" alt="...">
                        <?php endif; ?> -->
                        <div class="card-body">
                            <h5 class="card-title">Name: <?php se($item, "name"); ?></h5>
                            <p class="card-text">Description: <?php se($item, "description"); ?></p>
                            <p class="card-text">Category: <?php se($item, "category"); ?></p>
                            <?php if(!empty(se($item, "average_rating", "", false))): ?>
                                <p class="card-text">Average Rating: <?php se($item, "average_rating");?></p>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer">
                            Unit Price: $<?php se($item, "unit_price"); ?>
                            <?php if (is_logged_in()) : ?>
                                <button onclick="purchase('<?php se($item, 'id'); ?>','<?php se($item, 'unit_price'); ?>','<?php se($item, 'stock'); ?>','<?php echo get_user_id(); ?>', '<?php se($item, 'name'); ?>')" class="btn btn-primary">Add To Cart</button>
                            <?php endif; ?>
                            <?php if (has_role("Admin")) : ?>
                                <a class="btn btn-primary" href="admin/edit_product.php?id=<?php echo $item["id"]; ?>">Edit</a>
                                <br><br>
                            <?php endif; ?>
                            <a class="btn btn-primary" href="product_details.php?id=<?php echo $item["id"]; ?>">View</a>
                        </div>
                    </div>
                </div>
                <script>
                    $(document.getElementById('cardwithID<?php echo $item["id"]; ?>').getElementsByClassName('card-body')[0]).click(function() {
                        document.location.href = 'product_details.php?id=<?php echo $item["id"]; ?>';
                    });
                </script>
            <?php endforeach; ?>
            <script>
                let cards = document.querySelectorAll(".row > div");
                for(let i = 0; i < cards.length; i++)
                {
                    $(cards[i].firstElementChild).hover(
                            function() {
                                $(this).css("border-style", "solid");
                                $(this).css("border-color", "blue");
                                $(this).css("border-width", "medium");
                            },
                            function() {
                                $(this).css("border-style", "");
                                $(this).css("border-color", "");
                                $(this).css("border-width", "");
                            }
                    );
                }
            </script>
        </div>
        <br>
        <?php require(__DIR__ . "/../../partials/pagination.php"); ?>
    <?php endif; ?>
</div>
<?php
require(__DIR__ . "/../../partials/flash.php");
?>