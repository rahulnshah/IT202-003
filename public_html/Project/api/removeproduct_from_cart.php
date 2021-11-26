<?php
//note we need to go up 1 more directory
$response = ["message" => "There was a problem completing your purchase"];
// http_response_code(400); //figure out what this is 
session_start(); 
require(__DIR__ . "/../../../lib/functions.php");
//flash("req: " . var_export($_POST, true)); 
//Add code the clear entrie cart here 
if (isset($_POST["id"]) && isset($_POST["desired_quantity"]) && isset($_POST["unit_price"])) { //need to check this, becuz user can easily navigate to this file 
    require_once(__DIR__ . "/../../../lib/functions.php");
    $cart_id = (int)se($_POST, "id", 0, false);
    $quantity = (int) se($_POST, "desired_quantity", 0, false);
    $cost = floatval(se($_POST, "unit_price", 000.00, false)); 
    $isValid = true;
    $errors = [];
    if ($cart_id <= 0) {
        //invald user
        array_push($errors, "Invalid cart");
        $isValid = false;
    }
    if($quantity <= 0)
    {
        array_push($errors, "Invalid quantity");
        $isValid = false;
    }
    if ($cost <= 0) {
        array_push($errors, "Invalid cost");
        $isValid = false;
    }
    if($isValid){
        $db = getDB();
        if($quantity > 1)
        {
            //decrement desired_quantity by one; colud have also used update_data() here 
            $sql = "UPDATE Cart SET desired_quantity=:desired_quantity - 1, unit_cost=unit_cost - :unit_price WHERE id=:id";
            $stmt= $db->prepare($sql);
            try{
                $data = [":id" => $cart_id, ":unit_price" => $cost, ":desired_quantity" => $quantity];
                $stmt->execute($data);
                $response["message"] = "Decremented quantity by 1";
            }
            catch(PDOException $e) {
                flash("Error decrementing desired_quantity of cardId $cart_id: " . var_export($e->errorInfo, true), "warning");
            }
        }
        else
        {
            //DELETE THE item
            $stmt = $db->prepare("DELETE FROM Cart where id = :id");
            // $name = "";
            try {
                $stmt->execute([":id" => $cart_id]);
                $response["message"] = "Removed 1 item from cart";
                // if ($r) {
                //     $cost = (int)se($r, "unit_price", 000.00, false);
                //     $name = se($r, "name", "", false);
                //     $response["message"] = "Purchased $quantity of $name";
                // }
            } catch (PDOException $e) {
                flash("Error deleting item of cartId $cart_id: " . var_export($e->errorInfo, true), "warning");
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
