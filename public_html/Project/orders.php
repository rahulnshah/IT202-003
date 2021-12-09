<?php
//note we need to go up 1 more directory
require(__DIR__ . "/../../partials/nav.php");
if (!is_logged_in()) {
    flash("Need to be logged in to view orders", "warning");
    redirect("login.php");
}
$results = [];
$db = getDB();
$stmt = $db->prepare("SELECT Orders.id, Orders.total_price, Orders.payment_method, Orders.address FROM Orders
where Orders.user_id = :user_id"); // select prod it and name and inner join where prod id = cart.prod_id 
//based on that id 
// $total_cart_value = 0;
try {
    $stmt->execute([":user_id" => get_user_id()]); //specify the user_id so I get only products in tat user's cart
    $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $results = $r;
} catch (PDOException $e) {
    flash("<pre>" . var_export($e, true) . "</pre>");
}
?>
<div class="container-fluid">
    <h1 id="myCart">Orders</h1>
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