<?php
//note we need to go up 1 more directory
require(__DIR__ . "/../../partials/nav.php");
$id = se($_GET, "id", -1, false);
if($id <= 0)
{
    flash("Need to select an item first.", "warning");
    redirect("shop.php");
}
$result = [];
$columns = get_columns("Products");
//echo "<pre>" . var_export($columns, true) . "</pre>";
$ignore = ["id", "modified", "created", "visibility"];
$db = getDB();
//get the item
if(isset($_POST['comment']) && isset($_POST["vol"]))
{
    $comment = se($_POST, "comment", "", false);
    $rating = floatval(se($_POST, "vol", "", false));
    $haserrors = false;
    if($rating < 1 || $rating > 5)
    {
        flash("invalid rating","warning");
        $haserrors = true;
    }
    if(strlen($comment) <= 0)
    {
        flash("Comment must not be blank","warning");
        $haserrors = true;
    }
    if(!$haserrors)
    {
            $db = getDB();
            $stmt = $db->prepare("INSERT INTO Ratings (comment, product_id, user_id, rating) VALUES (:comment, :p_id, :u_id, :rating) ON DUPLICATE KEY UPDATE comment = VALUES(comment), rating = VALUES(rating)"); 
            try
            {
                $stmt->execute([":comment" => $comment,":p_id" => $id, ":u_id" => get_user_id(), ":rating" => $rating]);
                flash("Thank you for your feedback!");
                //update products, insert average rating for that product id. there has to be a rating at this stage.
                update_data("Products", $id, ['average_rating' => get_average_rating($id)]);
            }
            catch(PDOException $e)
            {
                flash(var_export($e->errorInfo, true), "warning");
            }
    }
}
$stmt = $db->prepare("SELECT * FROM Products where id =:id");
try {
    $stmt->execute([":id" => $id]);
    $r = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($r) {
        $result = $r;
    }
} catch (PDOException $e) {
    flash("<pre>" . var_export($e, true) . "</pre>");
}
$total_query = "SELECT count(1) as total FROM Ratings INNER JOIN Users On Ratings.user_id = Users.id WHERE product_id = :p_id";
$params = []; //define default params, add keys as needed and pass to execute
$params[":p_id"] = $id;
$per_page = 10;
paginate($total_query, $params, $per_page); //$per_page defualts to 10 in the paginate function
//$offset and $per_page variables are availble when the function above is called 
//now get a sub array of records from base query 
//set all ratings only when the total is greater than zero
$allRatings = []; 
if((int) $total_pages > 0)
{
    $base_query = "SELECT Ratings.user_id, Ratings.created, Ratings.comment, Ratings.rating, Users.username FROM Ratings INNER JOIN Users On Ratings.user_id = Users.id WHERE product_id = :p_id ORDER BY created DESC";
    $query = " LIMIT :offset, :count";
    $params[":offset"] = $offset;
    $params[":count"] = $per_page;
    //get the records
    $stmt = $db->prepare($base_query . $query);
    //we'll want to convert this to use bindValue so ensure they're integers so lets map our array
    foreach ($params as $key => $value) {
        $type = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
        $stmt->bindValue($key, $value, $type);
    }
    $params = null;
    try {
        $stmt->execute($params); //I am passing in nothing in the execute function by setting $params to null
        $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($r) {
            $allRatings = $r;
        }
    } catch (PDOException $e) {
        flash("<pre>" . var_export($e, true) . "</pre>");
    }
}
?>
<div class="container-fluid">
    <h1><?php se($result,"name"); ?> Details</h1>
        <?php foreach ($result as $column => $value) : ?>
            <?php if (!in_array($column, $ignore)) : ?> 
                <h3><?php echo str_replace("_", " ", se($column,null,"",false)); ?> :</h3>
                <!--<?php var_dump(se($column,null,"",false)); ?>-->
                <?php if (se($column,null,"",false) === "unit_price") : ?> 
                    <p><?php echo "$" . se($value,null,"",false); ?></p>
                <?php elseif (se($column,null,"",false) === "average_rating" && floatval(se($value,null,"",false)) <= 0) : ?>
                    <p><?php echo "Be the first to add a rating!"; ?></p>
                <?php else : ?> 
                    <?php if(se($column,null,"",false) === "average_rating"): ?>
                        <p><?php echo se($value,null,"",false) . "/5"; ?></p>
                    <?php else: ?>
                        <p><?php se($value); ?></p> <!-- equivalent to echo $value; -->
                    <?php endif; ?>
                <?php endif; ?> 
            <?php endif; ?>
        <?php endforeach; ?>
        <?php if(has_role("Admin")) : ?>
            <a class="btn btn-primary" href="admin/edit_product.php?id=<?php echo se($result, "id", "", false);?>">Edit</a>
            <br>
        <?php endif; ?>
        <!-- add a rating form here with a comment box -->
        <!-- <label for="vol" form="form1">Average Rating (between 1 and 5): <?php echo !!floatval(get_average_rating($id)) ? strval(floatval(get_average_rating($id))) . "/5" : get_average_rating($id)?></label> -->
        <?php if(is_logged_in()): ?>
        <form onsubmit="return validate(this)" id="form1" method="POST">
            <div class="mb-3">
                <input type="range" step="0.01" id="vol" name="vol" min="1" max="5" value="<?php echo !!floatval(get_average_rating($id)) ? strval(floatval(get_average_rating($id))) : "";?>">
            </div>
            <div class="mb-3">
                <label for="d">Comment</label>
                <textarea class="form-control form-control-sm" name="comment" id="d" placeholder="leave a comment..."></textarea>
            </div>
        <!-- submit btn -->
        <input class="btn btn-primary" type="submit" value="Submit Feedback"/>
    </form>
    <?php endif; ?>
    <?php if (count($allRatings) > 0) : ?>
        <h1>Ratings & Reviews</h1>
        <hr>
        <?php foreach ($allRatings as $rating) : ?>
        <div class="card">
            <div class="card-body">
                <p><?php echo " Created: " . se($rating,"created","Unknown created time", false); ?></p>
                By: <a href="<?php echo get_url("Profile.php?id=") . se($rating, "user_id", "Unknown user_id", false); ?>"><?php se($rating, "username", "Unknown user"); ?></a>
                <p>Comment: <?php se($rating,"comment","Unknown Comment"); ?><p>
                <p>Rating: <?php se($rating,"rating","Unknown rating"); ?><p>
            </div>
        </div>
        <?php endforeach; ?>
        <br>
        <?php require(__DIR__ . "/../../partials/pagination.php"); ?>
    <?php endif; ?>
</div>
<script>
    function validate(form) {
            //clear error messages
            let flashElement = document.getElementById("flash");
            flashElement.innerHTML = "";
            const formFieldOne = form.elements[0];
            const formFieldTwo = form.elements[1];
            let retVal = true;
            if(parseFloat(formFieldOne.value) < 1 || parseFloat(formFieldOne.value) > 5)
            {
                flash("Invalid rating", "warning");
                retVal = false;
            }
            if(formFieldTwo.value.length <= 0)
            {
                flash("Need to provide a comment", "warning");
                retVal = false;
            }
            return retVal;

    }
    $(document).ready(function (){
        if(document.getElementById("vol") !== null)
        {
            document.getElementById("vol").oninput = function() { document.getElementsByTagName("p")[5].innerText = document.getElementById("vol").value + "/5";};
        }
    });
</script>


<?php
//note we need to go up 1 more directory
require_once(__DIR__ . "/../../partials/flash.php");
?>