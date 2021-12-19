<?php
//note we need to go up 1 more directory
require(__DIR__ . "/../../partials/nav.php");
if (!is_logged_in()) {
    flash("Need to be logged in to view orders", "warning");
    redirect("login.php");
}
$results = [];
$db = getDB();
//Sort and Filters
$orderCol = se($_GET, "order", "total_price", false);
//allowed list
if (!in_array($orderCol, ["total_price", "created"])) {
    $orderCol = "total_price"; //default value, prevent sql injection
}
$upOrdown = se($_GET, "aOrd", "asc", false);
//allowed list
if (!in_array($upOrdown, ["asc", "desc"])) {
    $upOrdown = "asc"; //default value, prevent sql injection
}
//cannot know before hand all the appropiate date ranges with SQL
$aDateRange = se($_GET, "dateRanges", "", false);
$aCategory = se($_GET, "categories", "", false);
$query = "SELECT Orders.user_id, Orders.id, Orders.created as created, Orders.total_price, Orders.payment_method, Orders.address FROM Orders";
$params = [];
$params[":user_id"] = get_user_id();
if(!empty($aDateRange))
{
    $dateArr = explode(" ", $aDateRange);
    if(count($dateArr) >= 2)
    {
        $date_1 = $dateArr[0];
        $date_2 = $dateArr[2];
        $query .= " where DATE(created) BETWEEN :date_1 AND :date_2 AND";
        $params[":date_1"] = date("Y-m-d",strtotime($date_1));
        $params[":date_2"] = date("Y-m-d",strtotime($date_2));
    }
}
else if(!empty($aCategory))
{
    $query .= " INNER JOIN OrderItems ON Orders.id = OrderItems.order_id INNER JOIN Products ON Products.id = OrderItems.product_id WHERE Products.category = :category AND";
    $params[":category"] = $aCategory;
}
else
{
    $query .= " where";
}
$query .= " Orders.user_id = :user_id";
$params[":user_id"] = get_user_id();
//paginate 
$total_query = str_replace("Orders.user_id, Orders.id, Orders.created as created, Orders.total_price, Orders.payment_method, Orders.address","count(1) as total",$query);
$per_page = 10;
paginate($total_query, $params, $per_page); //$per_page defualts to 10 in the paginate function
if((int) $total_pages > 0)
{
    if (!empty($orderCol) && !empty($upOrdown)) {
        $query .= " ORDER BY $orderCol $upOrdown"; //be sure you trust these values, I validate via the in_array checks above
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
$rangeOfDates = [];
$oldestDate = "";
if(count($results) > 0)
{
        //get the categories to prefill
        $categoryResults = [];
        $stmt = $db->prepare("SELECT category FROM Products WHERE visibility = 1"); //could have used the distinct keyword here 
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
        //data ranges to fill: find the smallest difference between adjacent dates (in sorted order, and then increase
        //by the ammount starting from the samllest date. 
        $dates = [];
        $stmt = $db->prepare("SELECT DISTINCT(DATE(created)) as oldestDate FROM Orders WHERE user_id = :u_id ORDER BY oldestDate ASC"); //could have used the distinct keyword here 
        try {
            $stmt->execute([":u_id" => get_user_id()]);
            $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $dates = $r;
                //get the date a index 0. 
                $oldestDate = date_create($dates[0]["oldestDate"]);
                $latestDate = date_create($dates[count($dates) - 1]["oldestDate"]);
                $diffBetweenDates = 0;
                //if the dates array has only one element, than difference is ofcourse 0, but if length is > 1, difference 
                //cannot be 0 between two elements
                if(count($dates) > 1)
                {
                    $diffBetweenDates = date_diff($oldestDate, date_create($dates[1]["oldestDate"]));
                    $diffBetweenDates = $diffBetweenDates->format("%a");
                }
                //push in date ranges here , if diffBetween dates is 0, same += 0 = same and old = leates so array will be empty
                while((int) date_diff($oldestDate, $latestDate)->format("%a") > 0)
                {
                //     error_log("date: " . date_add(date_create($oldestDate),date_interval_create_from_date_string(strval((int) $diffBetweenDates * 2) . " days"))->format("Y-m-d"));
                    array_push($rangeOfDates, $oldestDate->format("Y-m-d") . " to " . date_add($oldestDate,date_interval_create_from_date_string(strval((int) $diffBetweenDates) . " days"))->format("Y-m-d"));
                    error_log($oldestDate->format("Y-m-d"));
                }
        } catch (PDOException $e) {
            flash("<pre>" . var_export($e, true) . "</pre>");
        }
}
?>
<div class="container-fluid">
    <h1 id="myCart">Orders</h1>
    <?php if (count($results) > 0) : ?>
    <form class="row row-cols-auto g-3 align-items-center" id="myForm">
        <div class="col">
            <div class="input-group">
                <div class="input-group-text">Filter</div>
                <!-- make sure these match the in_array filter above-->
                <select class="form-control" name="col" form="myForm">
                    <option value="category">Category</option>
                    <option value="created">Date Purchased</option>
                </select>
                <script>
                    //quick fix to ensure proper value is selected since
                    //value setting only works after the options are defined and php has the value set prior
                </script>
                <div class="input-group-text">Categories</div>
                <!-- make sure these match the in_array filter above-->
                <select class="form-control" name="categories" form="myForm">
                        <!-- run a php for loop here -->
                        <?php foreach ($categories as $category) : ?>
                            <option value="<?php echo $category ?>"><?php echo $category ?></option>
                        <?php endforeach; ?>
                </select>
                <script>
                    //quick fix to ensure proper value is selected since
                    //value setting only works after the options are defined and php has the value set prior
                </script>
                <div class="input-group-text">Date Ranges</div>
                <!-- make sure these match the in_array filter above-->
                <select class="form-control" name="dateRanges" form="myForm">
                        <!-- run a php for loop here -->
                        <!-- make the first option have the value of the oldestDate (first element in $dates) if diff is < 1-->
                        <!-- else run a for loop setting -->
                        <?php if (count($rangeOfDates) > 0): ?>
                            <?php foreach ($rangeOfDates as $dateRange) : ?>
                                <option value="<?php se($dateRange)?>"><?php se($dateRange); ?></option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="<?php se($oldestDate)?>"><?php se($oldestDate); ?></option>
                        <?php endif; ?>
                </select>
                <script>
                    //quick fix to ensure proper value is selected since
                    //value setting only works after the options are defined and php has the value set prior
                </script>
                <div class="input-group-text">Order By</div>
                <select class="form-control" name="order" form="myForm">
                    <option value="total_price">Total Price</option>
                    <option value="created">Date Purchased</option>
                </select>
                <script>
                    //quick fix to ensure proper value is selected since
                    //value setting only works after the options are defined and php has the value set prior
                </script>
                <div class="input-group-text">Sort</div>
                <select class="form-control" name="aOrd" form="myForm">
                    <option value="desc">High To Low/Recent to Old</option>
                    <option value="asc">Low To High/Old to Recent</option>
                </select>
                <script>
                    //quick fix to ensure proper value is selected since
                    //value setting only works after the options are defined and php has the value set prior
                </script>
            </div>
        </div>
        <div class="col">
            <div class="input-group">
                <input type="submit" class="btn btn-primary" value="Apply" />
            </div>
        </div>
    </form>
    <?php endif; ?>
    <div class="row row-cols-1 row-cols-md-5 g-4">
        <!-- <?php echo "<pre>" . var_export($results,true) . "</pre>" ?> -->
        <?php if (count($results) > 0) : ?>
            <!-- <?php echo "<pre>" . var_export($results,true) . "</pre>"; ?>  -->
            <?php foreach ($results as $item) : ?>
                <div id='orderwithID<?php echo $item["id"]; ?>' class="col">
                    <div class="card bg-light">
                        <div class="card-header">
                            Placeholder
                        </div>
                        <!-- <?php if (se($item, "image", "", false)) : ?>
                        <img src="<?php se($item, "image"); ?>" class="card-img-top" alt="...">
                    <?php endif; ?> -->
                        <div class="card-body">
                            <p class="card-text">Total price: $<?php se($item, "total_price"); ?></p>
                            <p class="card-text">UserID: <?php se($item, "user_id"); ?></p>
                            <p class="card-text">OrderID: <?php se($item, "id"); ?></p>
                            <p class="card-text">Payment method: <?php se($item, "payment_method"); ?></p>
                            <p class="card-text">Deliever To: <?php echo join(",",explode(" ", se($item, "address", "Unknown address", false))); ?></p>
                        </div>
                        <div class="card-footer">
                            <a class="btn btn-primary" href="order_details.php?order_id=<?php se($item, "id");?>">View</a>
                        </div>
                    </div>
                </div>
                <script>
                    $(document.getElementById('orderwithID<?php echo $item["id"]; ?>').getElementsByClassName('card-body')[0]).click(function() {
                        document.location.href = 'order_details.php?order_id=<?php echo $item["id"]; ?>';
                    });
                </script>
            <?php endforeach; ?>
            <script>
                let cards = document.getElementsByClassName("col");
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
            <br>
            <?php require(__DIR__ . "/../../partials/pagination.php"); ?>
            <?php else: ?>
                <p>No results to show</p>
            <?php endif; ?>
    </div>
</div>
<script>
                //hide the other column if the user selet one column
                //datePurchased.click(hide the categories div and select dropdown
                //and if the datePurchased div and datePurchased
                //select dropdown are noth there, unhide them);
                //categories.click(hide the datePurchased div and select dropdown, and if the categories div and 
                //select dropdwon are noth there, unhide them);
                //Example 
                $(document).ready(function(){
                    $(".input-group-text:nth-child(7)").hide(); 
                    $(".form-control:nth-child(8)").hide();
                    $(".form-control:nth-child(8)").attr("form", "anotherForm"); 
                    $("[name=col]").change(function(){ //date purchased select 
                        if(this.value === "created")
                        {
                            $(".input-group-text:nth-child(4)").hide(); // category div
                            $(".form-control:nth-child(5)").hide(); //category 
                            $(".form-control:nth-child(5)").attr("form", "anotherForm"); //category 
                            $(".input-group-text:nth-child(7)").show(); //date range div
                            $(".form-control:nth-child(8)").show();//date purchased 
                            $(".form-control:nth-child(8)").attr("form", "myForm");
                        }
                        else
                        {
                            $(".input-group-text:nth-child(7)").hide(); 
                            $(".form-control:nth-child(8)").hide(); 
                            $(".form-control:nth-child(8)").attr("form", "anotherForm");
                            $(".input-group-text:nth-child(4)").show(); 
                            $(".form-control:nth-child(5)").show();
                            $(".form-control:nth-child(5)").attr("form", "myForm");
                        }
                    });
            });
</script>
    <!-- migt have to do inner join Orders with orderItems to display order items for the logged in user-->
<?php
    require(__DIR__ . "/../../partials/flash.php");
?>