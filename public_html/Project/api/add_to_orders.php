<?php
//remember, API endpoints should only echo/output precisely what you want returned
//any other unexpected characters can break the handling of the response
$response = ["message" => "There was a problem completing your purchase"];
// http_response_code(400); //figure out what this is 
session_start(); 
require(__DIR__ . "/../../../lib/functions.php");
//flash("req: " . var_export($_POST, true)); 
if (isset($_POST["address"]) && isset($_POST["true_price"]) && isset($_POST["payment_method"]) && isset($_POST["user_id"]) && isset($_POST["total_price"])) {
    require_once(__DIR__ . "/../../../lib/functions.php");
    $user_id = (int)se($_POST, "user_id", 0, false);
    $address = se($_POST, "address", "", false);
    $total_pice = floatval(se($_POST,"total_price", 000.00,false));
    $true_pice = floatval(se($_POST,"true_price", 000.00,false));
    $payment_method = se($_POST, "payment_method", "Unknown payment method", false); //this is a decimal, I am not gonna still cast it 
    //flash("req: " . var_export($cost, true)); 
    $isValid = true;
    //check if $total_price matches the actual price as calculated from the cart table 
    $errors = [];
    if ($user_id <= 0) {
        array_push($errors, "Invalid user");
        $isValid = false;
    }
    if(strlen($address) <= 0)
    {
        array_push($errors,"Invalid address");
        $isValid = false;
    }
    if ($total_pice <= 000.00 || $total_pice !== $true_pice) {
        array_push($errors, "Invalid total price");
        $isValid = false;
    }
    if($payment_method === "Unknown payment method")
    {
        array_push($errors, "Unknown payment method");
        $isValid = false;
    }
    if($isValid){
        $pdo = getDB();
        //verfiy current prodcut price against products table 
        $sql = "SELECT Cart.unit_price AS Cart_price, Products.unit_price AS Product_price, Products.id FROM Products
        INNER JOIN Cart on Products.id = Cart.product_id 
        where Cart.user_id = :user_id AND Cart_price = Product_price AND Cart.desired_quantity <= Products.stock"; //inner joins are usally on primary keys and foreign keys
        //negate the where condition to see which items are not in stock if thre are any
        $stmt = $pdo->prepare($sql);
        try{
                $stmt->execute([":user_id" => $user_id]); //specify the user_id so I get only products in tat user's cart
                $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $results = $r;
                // compare with the number of records in the Cart table fro that user 
                //if ($cart_items === count($reuslts), proceed with the next steps of the order else do 
                //$response["message"] = "something's wrong")
                //SELECT Cart.unit_cost, Products.unit_price, Cart.desired_quantity, Products.stock, Cart.user_id, Products.unit_price, Products.id FROM Products INNER JOIN Cart on Products.id = Cart.product_id where Cart.user_id = 22 AND (Cart.unit_cost != Products.unit_price OR Cart.desired_quantity > Products.stock);
                error_log(count($results));
                if(count($results) === count($cart_items))
                {
                    //check all entries in cart when desired quantity > stock and if that array of resutls 
                    // is > 1, run a for loop on the reuslts and queue in messages denotitng whihc items fall 
                    //in that situation 

                    $sql = "SELECT Products.name, Products.id, Cart.desired_quantity FROM Products
                    INNER JOIN Cart on Products.id = Cart.product_id 
                    where Cart.user_id = :user_id and Cart.desired_quantity > Products.stock";
                    $stmt = $pdo->prepare($sql);
                    try
                    {
                        $stmt->execute([":user_id" => $user_id]); //specify the user_id so I get only products in tat user's cart
                        $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        $items_to_purchase = $r; 
                        if(count($items_to_purchase) > 0)
                        {
                            //run a for loop or use a array_map to obtain a flash message for those items and prevent purchase of items in Cart.
                            
                        }
                        else
                        {
                            $last_inserted_order_id = save_data("Orders", $_POST, ["true_price"]);
                            if($last_inserted_order_id > 0)
                            {

                                //Copy the cart details into the OrderItems tables with the Order ID from the previous step
                                $stmt = $pdo->prepare("INSERT INTO OrderItems (product_id, unit_price, quantity, order_id)
                                SELECT product_id, unit_cost, desired_quantity, :o_id FROM Cart where Cart.user_id = :user_id");
                                try{
                                    $stmt->execute([":user_id" => $user_id, ":o_id" => $last_inserted_order_id]);
                                    $last_inserted_orderitem = $pdo->lastInsertId();
                                }
                                catch(PDOException $e)
                                {
                                    flash(var_export($e->errorInfo, true), "warning");
                                }
                                
                            }
                        }
                    }
                    catch(PDOException $e)
                    {
                        flash(var_export($e->errorInfo, true), "warning");
                    } 
                }
        }
        catch(PDOException $e)
        {
            flash(var_export($e->errorInfo, true), "warning");
        }
    }
    else
    {
        $response["message"] = join("<br>", $errors);
    }
}
echo json_encode($response); // string
?>
