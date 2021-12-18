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
    <form method="GET">
        <label for="categories">Filter By Category:</label>
        <br>
        <div class="input-group">
            <select class="form-select form-select-sm" name="categories" id="categories">
                <!-- TODO add php templating here to get all the categories-->
                <!-- <?php foreach ($categories as $category) : ?>
                    <option value="<?php echo $category ?>"><?php echo $category ?></option>
                <?php endforeach; ?> -->
            </select>
        </div>
        <br>
        <label for="dateRanges">Select A Date Range:</label>
        <br>
        <div class="input-group">
            <select class="form-select form-select-sm" name="categories" id="dateRanges">
                <!-- TODO add php templating here to get all the categories-->
                <!-- <?php foreach ($categories as $category) : ?>
                    <option value="<?php echo $category ?>"><?php echo $category ?></option>
                <?php endforeach; ?> -->
            </select>
        </div>
        <br>
        <label for="dates">Sort By Date Purchased:</label>
        <br>
        <div class="input-group">
            <select class="form-select form-select-sm" name="categories" id="dates">
                <!-- TODO add php templating here to get all the categories-->
                <option>Recent To Old</option>
                <option>Old To Recent</option>
            </select>
        </div>
        <br>
        <label for="totalPrices">Sort By Total:</label>
        <br>
        <div class="input-group">
            <select class="form-select form-select-sm" name="categories" id="totalPrices">
                <!-- TODO add php templating here to get all the categories-->
                <option>Low To High</option>
                <option>High To Low</option>
            </select>
        </div>
        <br>
        <input type="submit" class="btn btn-primary" value="Apply"/>
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
            </script>
            <?php endif; ?>
    </div>
</div>
    <!-- migt have to do inner join Orders with orderItems to display order items for the logged in user-->
<?php
    require(__DIR__ . "/../../partials/flash.php");
?>