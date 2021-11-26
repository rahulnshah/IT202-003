<?php
//remember, API endpoints should only echo/output precisely what you want returned
//any other unexpected characters can break the handling of the response
$response = ["message" => "There was a problem completing your purchase"];
// http_response_code(400); //figure out what this is 
session_start(); 
require(__DIR__ . "/../../../lib/functions.php");
//flash("req: " . var_export($_POST, true)); 
if (isset($_POST["product_id"]) && isset($_POST["unit_price"]) && isset($_POST["stock"]) && isset($_POST["user_id"]) && isset($_POST["name"])) {
    require_once(__DIR__ . "/../../../lib/functions.php");
    $user_id = (int)se($_POST, "user_id", 0, false);
    $item_id = (int)se($_POST, "product_id", 0, false);
    $stock = (int)se($_POST,"stock",0,false);
    $cost = floatval(se($_POST, "unit_price", 000.00, false)); //this is a decimal, I am not gonna still cast it 
    //flash("req: " . var_export($cost, true)); 
    $name = se($_POST,"name", "Unknown item", false);
    $isValid = true;
    $errors = [];
    if ($user_id <= 0) {
        array_push($errors, "Invalid user");
        $isValid = false;
    }
    if($stock <= 0)
    {
        array_push($errors,"Item not in stock");
        $isValid = false;
    }
    if ($cost <= 0) {
        array_push($errors, "Invalid cost");
        $isValid = false;
    }
    if ($item_id <= 0) {
        //invalid item
        array_push($errors, "Invalid item");
        $isValid = false;
    }
    if($name === "Unknown item")
    {
        array_push($errors, "Unknown item");
        $isValid = false;
    }
    if($isValid){
        //get true price from DB, don't trust the client, and call svae_data with passed in params to insert 
        //professor only uses $ignore array in php templating
        try{
            $id = save_data("Cart", $_POST, ["stock", "unit_price", "name"]);
            if ($id > 0) {
                // flash("Created Item with id $id", "success");
                update_data("Cart", $id, ['unit_cost' => strval($cost)]);
                //$_POST["stock"] = strval($stock - 1); <-- code for purchase items 
                // error_log("CARTID: " . var_export($id,true));
                //$didUpdate = update_data("Products", $item_id, $_POST,["unit_price", "name", "product_id", "user_id"]);
                /*if($didUpdate)
                {
                    
                }*/
                $response["message"] = "Added $name to cart";
            }
        }
        catch(PDOException $e)
        {
            //I wouldn't throw an exception from save_data unless I have a duplicate key exeltpion
            $db = getDB();
            $stmt = $db->prepare("INSERT INTO Cart (product_id, user_id) VALUES (:product_id, :user_id) ON DUPLICATE KEY UPDATE desired_quantity = desired_quantity + 1, unit_cost = desired_quantity * :unit_price"); 
            try
            {
                $stmt->execute([":product_id" => $item_id, ":user_id" => $user_id, ":unit_price" => $cost]);
                //error_log at ajax endpoints
                //$_POST["stock"] = strval($stock - 1); <-- code for purchase items 
                //$didUpdate = update_data("Products", $item_id, $_POST,["unit_price", "name", "product_id", "user_id"]);
                /*if($didUpdate)
                {
                    
                }*/
                $response["message"] = "Added $name to cart";
            }
            catch(PDOException $e)
            {
                flash(var_export($e->errorInfo, true), "warning");
            }
        }
    }
    else
    {
        $response["message"] = join("<br>", $errors);
    }
}
echo json_encode($response); // string
?>
