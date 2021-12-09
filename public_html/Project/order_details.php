<?php
//note we need to go up 1 more directory
require(__DIR__ . "/../../partials/nav.php");
$order_id = se($_GET, "order_id", -1, false);
if($order_id <= 0)
{
    flash("Need to select an order first.", "warning");
    redirect("orders.php");
}
$total_price_of_order = 0;
$results = [];
$db = getDB();
$stmt = $db->prepare("SELECT Products.id, Products.name, OrderItems.unit_price, OrderItems.quantity FROM Products INNER JOIN OrderItems ON OrderItems.product_id = Products.id where order_id = :o_id"); // select prod it and name and inner join where prod id = cart.prod_id 
//based on that id 
// $total_cart_value = 0;
try {
    $stmt->execute([":o_id" => $order_id]); //specify the user_id so I get only products in tat user's cart
    $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $results = $r;
} catch (PDOException $e) {
    flash("<pre>" . var_export($e, true) . "</pre>");
}
?>
<div class="container-fluid">
    <h1 id="myCart">Order Items</h1>
    <div class="row row-cols-1 row-cols-md-5 g-4">
        <!-- <?php echo "<pre>" . var_export($results,true) . "</pre>" ?> -->
        <p>Thank you for shopping!</p>
        <?php if (count($results) > 0) : ?>
            <!-- <?php echo "<pre>" . var_export($results,true) . "</pre>"; ?>  -->
            <?php foreach ($results as $item) : ?>
                <?php $total_price_of_order += (int) se($item, "quantity", null, false) * floatval(se($item, "unit_price", null, false));?>
                <div id='productwithID<?php echo $item["id"]; ?>' class="col">
                    <div class="card bg-light">
                        <div class="card-header">
                            Placeholder
                        </div>
                        <!-- <?php if (se($item, "image", "", false)) : ?>
                        <img src="<?php se($item, "image"); ?>" class="card-img-top" alt="...">
                    <?php endif; ?> -->
                        <div class="card-body">
                            <h5 class="card-title">Name: <?php se($item, "name"); ?></h5>
                            <p class="card-text">Unit price: $<?php se($item, "unit_price"); ?></p>
                            <p class="card-text">Quantity purchased: <?php se($item, "quantity"); ?></p>
                            <p class="card-text">ProductID: <?php se($item, "id"); ?></p>
                        </div>
                        <div class="card-footer">
                            Subtotal: $<?php echo ((int) se($item, "quantity", null, false) * floatval(se($item, "unit_price", null, false))); ?>
                            <a class="btn btn-primary" href="product_details.php?id=<?php se($item, "id");?>">View</a>
                        </div>
                    </div>
                </div>
                <script>
                    $(document.getElementById('productwithID<?php echo $item["id"]; ?>').getElementsByClassName('card-body')[0]).click(function() {
                        document.location.href = 'product_details.php?id=<?php echo $item["id"]; ?>';
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
            <p><?php echo "Total Price of Order: $" . strval($total_price_of_order);?>
            <br>
            <?php endif; ?>
    </div>
</div>
    <!-- migt have to do inner join Orders with orderItems to display order items for the logged in user-->
<?php
    require(__DIR__ . "/../../partials/flash.php");
?>