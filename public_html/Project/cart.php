<?php
//note we need to go up 1 more directory
require(__DIR__ . "/../../partials/nav.php");
if (!is_logged_in()) {
    flash("Need to be logged in to shop", "warning");
    redirect("login.php");
}
$results = [];
$db = getDB();
$stmt = $db->prepare("SELECT Cart.id, Cart.desired_quantity, Cart.product_id, Products.unit_price, Products.name, Products.description, Products.category, Products.stock, Cart.unit_cost FROM Products
INNER JOIN Cart on Products.id = Cart.product_id 
where Cart.user_id = :user_id"); // select prod it and name and inner join where prod id = cart.prod_id 
//based on that id 
$total_cart_value = 0;
try {
    $stmt->execute([":user_id" => get_user_id()]); //specify the user_id so I get only products in tat user's cart
    $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $results = $r;
    if(count($results) > 0)
    {
        foreach($results as $result)
        {
            $total_cart_value +=  (int) se($result, "desired_quantity", null, false) * floatval(se($result, "unit_cost", null, false));
        }
    }
    //var_export(($results));
} catch (PDOException $e) {
    flash("<pre>" . var_export($e, true) . "</pre>");
}
?>
<script>
    function clear_cart(u_id) {
        console.log("TODO clear items", u_id);
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
                // document.getElementsByTagName("div")[1].innerHTML = data["message"];
                let data = JSON.parse(this.responseText);
                console.log("received data", data);
                flash(data["message"], "success");
                document.getElementById("myCart").nextElementSibling.innerHTML = "<p>Your Cart is empty</p>"; // also could have created an new node paragraph
                let p2 = document.createElement("p");   // Create a <button> element
                p2.innerHTML = "Total: $0";                   // Insert text             // Append <button> to <body>
                document.getElementById("myCart").nextElementSibling.appendChild(p2);   
            }
        };
        http.open("POST", "api/clear_cart.php", true);
        let data = {
                user_id: u_id,
            }
            let q = Object.keys(data).map(key => key + '=' + data[key]).join('&');
            console.log(q);
            http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            http.send(q);
            console.log(http);
    }
    function remove_item(cart_id, quantity)
    {
        //console.log("TODO remove item", cart_id);
        let http = new XMLHttpRequest();
            http.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // document.getElementsByTagName("div")[1].innerHTML = data["message"];
                let data = JSON.parse(this.responseText);
                console.log("received data", data);
                flash(data["message"], "success");
            }
        };
        http.open("POST", "api/removeproduct_from_cart.php", true);
        let data = {
                id: cart_id,
                desired_quantity: quantity
            }
        let q = Object.keys(data).map(key => key + '=' + data[key]).join('&');
        console.log(q);
        http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        http.send(q);
        console.log(http);
    }

    function set_quantity(form,cart_id)
    {
    //    console.log(form);
    //    console.log(form.elements[0].value);
        //clears any standing flash messages at the top
        let flashElement = document.getElementById("flash");
        flashElement.innerHTML = "";
        const formFieldOne = form.elements[0];// this is the problem, need to get every form 
        console.log("fromFieldOne's value:", formFieldOne.value);
        //let retVal = true;
        //if the input is some wierd characters, or some number < 0, flash a message 
        if(!(/^-?[0-9]\d*(\.\d+)?$/.test(formFieldOne.value)) || formFieldOne.value < 0)
        {
            flash("Invalid quantity", "warning");
        }
        else{
        //make an ajax request - POST and in the api file update desired quantity as well as unit costof that item 
            let http = new XMLHttpRequest();
                http.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // document.getElementsByTagName("div")[1].innerHTML = data["message"];
                    let data = JSON.parse(this.responseText);
                    console.log("response text", this.responseText);
                    flash(data["message"], "success");
                }
            };
            http.open("POST", "api/setproductquantity_from_cart.php", true);
            let data = {
                    id: cart_id,
                    desired_quantity: formFieldOne.value
                }
            let q = Object.keys(data).map(key => key + '=' + data[key]).join('&');
            console.log(q);
            http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            http.send(q);
            //console.log(http);
        }
    }
</script>
<div class="container-fluid">
    <h1 id="myCart"><?php echo get_username() . "'s Cart";?></h1>
    <div class="row row-cols-1 row-cols-md-5 g-4">
        <!-- <?php echo "<pre>" . var_export($results,true) . "</pre>" ?> -->
        <?php if (count($results) === 0) : ?>
            <p>Your cart is empty</p>
        <?php else : ?>
            <!-- <?php echo "<pre>" . var_export($results,true) . "</pre>"; ?>  -->
            <?php foreach ($results as $item) : ?>
                <div id='cardwithID<?php echo $item["id"]; ?>' class="col">
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
                            <p class="card-text">CartID: <?php se($item, "id"); ?></p>
                            <p class="card-text">Category: <?php se($item, "category"); ?></p>
                            <!-- <p class="card-text">Stock: <?php se($item, "stock"); ?></p> show stock to user -->
                            <?php if (floatval(se($item, "unit_cost", "000.00", false)) !== floatval(se($item, "unit_price","000.00", false))) : ?>
                                <p class="card-text">Unit Price: $<?php se($item, "unit_price"); ?></p>
                                <p class="card-text">Unit Cost: $<?php se($item, "unit_cost"); ?></p> 
                            <?php endif; ?>
                        </div>
                        <div class="card-footer">
                            Subtotal: $<?php echo ((int) se($item, "desired_quantity", null, false) * floatval(se($item, "unit_cost", null, false))); ?>
                            <button onclick="remove_item('<?php se($item, 'id'); ?>', '<?php se($item, 'desired_quantity'); ?>')" class="btn btn-primary">Remove</button>
                            <a class="btn btn-primary" href="product_details.php?id=<?php se($item, "product_id");?>">View</a>
                            <form>
                                <label for="quantity">Quantity:</label>
                        <div class="input-group">
                                <input class="form-control" type="number" id="quantity" name="quantity" value="<?php se($item, 'desired_quantity'); ?>">
                                <button type="button" onclick="set_quantity(this.form,'<?php se($item, 'id'); ?>')" class="btn btn-outline-primary">Set Quantity</button>
                        </div>
                            </form>
                        </div>
                    </div>
                </div>
                <script>
                    function strikeThrough(text) {
                        return "<del>" + text + "</del>";
                    }
                    $(document.getElementById('cardwithID<?php echo $item["id"]; ?>').getElementsByClassName('card-body')[0]).click(function() {
                        document.location.href = 'product_details.php?id=<?php echo $item["product_id"]; ?>';
                    });
                    // extract unit_cost and price from string that check if bot are not null and display 
                    var u_price = document.getElementById("cardwithID<?php echo $item["id"]; ?>").children[0].children[1].children[4];
                    var u_cost = document.getElementById("cardwithID<?php echo $item["id"]; ?>").children[0].children[1].children[5];
                    if(u_cost !== undefined & u_price !== undefined)
                    {
                        u_price = Number(u_price.innerText.replace(/[^0-9\.]+/g,""));
                        u_cost = Number(u_cost.innerText.replace(/[^0-9\.]+/g,""));
                        var percentage_change = (Math.abs(u_cost - u_price)/u_cost) * 100;
                        if(u_price > u_cost)
                        {
                            document.getElementById("cardwithID<?php echo $item["id"]; ?>").children[0].children[1].children[4].innerText = "Now: $" + u_price + " (" + Math.round(percentage_change) + "% higher)";
                        }
                        else
                        {
                            document.getElementById("cardwithID<?php echo $item["id"]; ?>").children[0].children[1].children[4].innerText = "Now: $" + u_price + " (" + Math.round(percentage_change) + "% off)";
                            
                        }
                        document.getElementById("cardwithID<?php echo $item["id"]; ?>").children[0].children[1].children[5].innerHTML = "Unit Cost: " + strikeThrough("$" + u_cost);
                    }

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
            <p><?php echo "Total: $" . strval($total_cart_value); ?>
            <button onclick="clear_cart('<?php echo get_user_id(); ?>')" class="btn btn-primary">Clear Cart</button>
            <br>
            <?php $_SESSION['total_cost'] = strval($total_cart_value); ?>
            <a class="btn btn-primary" href="checkout.php">Checkout</a>
            <?php endif; ?>
    </div>
</div>
<?php
    require(__DIR__ . "/../../partials/flash.php");
?>
<!-- <h5 class="card-title">Name: <?php se($item, "name"); ?></h5>
<p class="card-text">Description: <?php se($item, "description"); ?></p>
<p class="card-text">Category: <?php se($item, "category"); ?></p> -->
<!-- <p class="card-text">Stock: <?php se($item, "stock"); ?></p> show stock to user -->
