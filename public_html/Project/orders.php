<?php
//note we need to go up 1 more directory
require(__DIR__ . "/../../partials/nav.php");
if (!is_logged_in()) {
    flash("Need to be logged in to view orders", "warning");
    redirect("login.php");
}
$results = [];
$db = getDB();
$stmt = $db->prepare("SELECT id, total_price, payment_method, address FROM Orders
where user_id = :user_id ORDER BY created DESC LIMIT 10"); // select prod it and name and inner join where prod id = cart.prod_id 
//based on that id 
// $total_cart_value = 0;
try {
    $stmt->execute([":user_id" => get_user_id()]); //specify the user_id so I get only products in tat user's cart
    $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $results = $r;
} catch (PDOException $e) {
    flash("<pre>" . var_export($e, true) . "</pre>");
}
//Filter Logic 
//if GET
?>
<div class="container-fluid">
    <h1 id="myCart">Orders</h1>
    <!-- gonna have to change this up a bit-->
    <p class="aPara">This is a paragraph.</p>
    <form class="row row-cols-auto g-3 align-items-center">
        <!-- <div class="col">
            <div class="input-group">
                <div class="input-group-text">Name</div>
                <input class="form-control" name="name" value="<?php se($name); ?>" />
            </div>
        </div> -->
        <!-- with php fill two dropdwown with their respective ranges, then 
        let the user select which thing to see the range of data purchased or category. When the 
        user select one of these hide the column of the other thing with jQuery-->
        <div class="col">
            <div class="input-group">
                <div class="input-group-text">Filter</div>
                <!-- make sure these match the in_array filter above-->
                <select class="form-control" name="col" value="<?php se($col); ?>">
                    <option value="category">Category</option>
                    <option value="created">Date Purchased</option>
                </select>
                <script>
                    //quick fix to ensure proper value is selected since
                    //value setting only works after the options are defined and php has the value set prior
                    document.forms[0].col.value = "<?php se($col); ?>";
                </script>
                <div class="input-group-text">Categories</div>
                <!-- make sure these match the in_array filter above-->
                <select class="form-control" name="categories" value="<?php se($category); ?>">
                        <!-- run a php for loop here -->
                </select>
                <script>
                    //quick fix to ensure proper value is selected since
                    //value setting only works after the options are defined and php has the value set prior
                    document.forms[0].categories.value = "<?php se($category); ?>";
                </script>
                <div class="input-group-text">Date Ranges</div>
                <!-- make sure these match the in_array filter above-->
                <select class="form-control" name="dateRanges" value="<?php se($dateRange); ?>">
                        <!-- run a php for loop here -->
                </select>
                <script>
                    //quick fix to ensure proper value is selected since
                    //value setting only works after the options are defined and php has the value set prior
                    document.forms[0].dateRanges.value = "<?php se($dateRange); ?>";
                </script>
                <div class="input-group-text">Order By</div>
                <select class="form-control" name="order" value="<?php se($order); ?>">
                    <option value="total_price">Total Price</option>
                    <option value="created">Date Purchased</option>
                </select>
                <script>
                    //quick fix to ensure proper value is selected since
                    //value setting only works after the options are defined and php has the value set prior
                    document.forms[0].order.value = "<?php se($order); ?>";
                </script>
                <div class="input-group-text">Sort</div>
                <select class="form-control" name="aOrd" value="<?php se($aOrd); ?>">
                    <option value="desc">High To Low/Recent to Old</option>
                    <option value="asc">Low To High/Old to Recent</option>
                </select>
                <script>
                    //quick fix to ensure proper value is selected since
                    //value setting only works after the options are defined and php has the value set prior
                    document.forms[0].aOrd.value = "<?php se($aOrd); ?>";
                </script>
            </div>
        </div>
        <div class="col">
            <div class="input-group">
                <input type="submit" class="btn btn-primary" value="Apply" />
            </div>
        </div>
    </form>
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
                //hide the other column if the user selet one column
                //datePurchased.click(hide the categories div and select dropdown
                //and if the datePurchased div and datePurchased
                //select dropdown are noth there, unhide them);
                //categories.click(hide the datePurchased div and select dropdown, and if the categories div and 
                //select dropdwon are noth there, unhide them);
                //Example 
//                 $(document.getElementsByTagName("p")[0]).click(function(){
//     $(this).hide();
//   });
            </script>
            <?php endif; ?>
    </div>
</div>
    <!-- migt have to do inner join Orders with orderItems to display order items for the logged in user-->
<?php
    require(__DIR__ . "/../../partials/flash.php");
?>