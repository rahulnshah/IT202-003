<?php
//note we need to go up 1 more directory
$response = ["message" => "There was a problem completing your purchase"];
// http_response_code(400); //figure out what this is 
session_start(); 
require(__DIR__ . "/../../../lib/functions.php");
//flash("req: " . var_export($_POST, true)); 
//Add code the clear entrie cart here 
if (isset($_POST["user_id"])) { //need to check this, becuz user can easily navigate to this file 
    require_once(__DIR__ . "/../../../lib/functions.php");
    $user_id = (int)se($_POST, "user_id", 0, false);
    $isValid = true;
    $errors = [];
    if ($user_id <= 0) {
        //invald user
        array_push($errors, "Invalid user");
        $isValid = false;
    }
    if($isValid){
        $db = getDB();
        $stmt = $db->prepare("DELETE FROM Cart where user_id = :id");
        // $name = "";
        try {
            $stmt->execute([":id" => $user_id]);
            $response["message"] = "Cleared cart";
            // if ($r) {
            //     $cost = (int)se($r, "unit_price", 000.00, false);
            //     $name = se($r, "name", "", false);
            //     $response["message"] = "Purchased $quantity of $name";
            // }
        } catch (PDOException $e) {
            flash("Error getting cost of $item_id: " . var_export($e->errorInfo, true), "warning");
        }
    }
    else
    {
        $response["message"] = join("<br>", $errors);
    }
}
echo json_encode($response); // string
?>
