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
        $sql = "SELECT Cart.unit_cost as cart_cost, Products.id as product_id, Products.unit_price as product_cost, Cart.desired_quantity, Products.stock, Products.name, Cart.user_id FROM Products INNER JOIN Cart on Products.id = Cart.product_id where Cart.user_id = :user_id AND (Cart.unit_cost != Products.unit_price OR Cart.desired_quantity > Products.stock)"; //inner joins are usally on primary keys and foreign keys
        //negate the where condition to see which items are not in stock if thre are any
        $stmt = $pdo->prepare($sql);
        try{
                $stmt->execute([":user_id" => $user_id]);
                $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $results = $r;
                if(count($results) > 0)
                {
                     //tell the user which items are not valid and why they are not valid in separate flash messages, that I will push in the errors array
                     //run a for loop on resutls , check if(AND) else if () else and concatenate the strings and set them to message
                    foreach($results as $res)
                    {
                        $str_to_attach = "";
                        if((floatval($res['cart_cost']) != floatval($res['product_cost'])) && ((int) $res['desired_quantity'] > (int) $res['stock']))
                        {
                            $str_to_attach = $res["name"] . "'s cart price:" . $res['cart_cost'] . " does not match its product price:" . " " . $res['product_cost'] . " and " . $res["name"] . "'s quantity in cart:" . $res['desired_quantity'] . " is greater than available stock:" . $res['stock'];
                        }
                        else if((int) $res['desired_quantity'] > (int) $res['stock'])
                        {
                            $str_to_attach = $res["name"] . "'s quantity in cart:" . $res['desired_quantity'] . " is greater than available stock:" . " " . $res['stock'];
                        }
                        else
                        {
                            $str_to_attach = $res["name"] . "'s cart price:" . $res['cart_cost'] . " does not match its product price:" . " " . $res['product_cost'];
                        }
                    }
                    $response["message"] = "At least one item is not in stock or its cart price does not match its actual price.";
                    error_log("<pre>" . var_export($results, true) . "</pre>");
                }
                else
                { 
                    try
                    {
                            $last_inserted_order_id = save_data("Orders", $_POST, ["true_price"]);
                            if($last_inserted_order_id > 0)
                            {
                                //Copy the cart details into the OrderItems tables with the Order ID from the previous step
                                $stmt = $pdo->prepare("INSERT INTO OrderItems (product_id, unit_price, quantity, order_id)
                                SELECT product_id, unit_cost, desired_quantity, :o_id FROM Cart where Cart.user_id = :user_id");
                                try{
                                    $stmt->execute([":user_id" => $user_id, ":o_id" => $last_inserted_order_id]);
                                    //Update the Products table Stock for each item to deduct the Ordered Quantity
                                    $stmt = $pdo->prepare("UPDATE Products INNER JOIN Cart ON Products.id = Cart.product_id
                                    SET Products.stock = Products.stock - Cart.desired_quantity WHERE Cart.user_id = :user_id");
                                    try
                                    {
                                        $stmt->execute([":user_id" => $user_id]);
                                        //clear cart 
                                        $stmt = $pdo->prepare("DELETE FROM Cart where user_id = :id");
                                        try {
                                            $stmt->execute([":id" => $user_id]);
                                            $response["message"] = "Cleared cart and purchase successfull";
                                            unset($_SESSION["total_cost"]);
                                        } catch (PDOException $e) {
                                            flash("Error getting cost of $item_id: " . var_export($e->errorInfo, true), "warning");
                                        }
                                    }
                                    catch(PDOException $e)
                                    {
                                        flash(var_export($e->errorInfo, true), "warning");
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
