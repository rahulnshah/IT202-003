<?php
//note we need to go up 1 more directory
require(__DIR__ . "/../../partials/nav.php");
$result = [];
$columns = get_columns("Products");
//echo "<pre>" . var_export($columns, true) . "</pre>";
$ignore = ["id", "modified", "created", "visibility"];
$db = getDB();
//get the item
$id = se($_GET, "id", -1, false);
if($id <= 0)
{
    flash("Need to select an item first.", "warning");
    redirect("shop.php");
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
            }
            catch(PDOException $e)
            {
                flash(var_export($e->errorInfo, true), "warning");
            }
    }
}
$allRatings = [];
$stmt = $db->prepare("SELECT user_id, created, comment, rating FROM Ratings WHERE product_id = :p_id ORDER BY created DESC LIMIT 10");
try {
    $stmt->execute([":p_id" => $id]);
    $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($r) {
        $allRatings = $r;
    }
} catch (PDOException $e) {
    flash("<pre>" . var_export($e, true) . "</pre>");
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
                <?php else : ?> 
                    <p><?php se($value); ?></p> <!-- equivalent to echo $value; -->
                <?php endif; ?> 
            <?php endif; ?>
        <?php endforeach; ?>
        <?php if(has_role("Admin")) : ?>
            <a class="btn btn-primary" href="admin/edit_product.php?id=<?php echo se($result, "id", "", false);?>">Edit</a>
        <?php endif; ?>
        <!-- add a rating form here with a comment box -->
        <?php if(is_logged_in()): ?>
        <form onsubmit="return validate(this)" method="POST">
            <div class="mb-3">
                <label for="vol">Average Rating (between 1 and 5): <?php echo get_average_rating($id)?>/5</label>
                <br>
                <input type="range" step="0.01" id="vol" name="vol" min="1" max="5" value="<?php echo get_average_rating($id)?>">
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
                <p>Comment: <?php se($rating,"comment","Unknown Comment"); ?><p>
                <p>Rating: <?php se($rating,"rating","Unknown rating"); ?><p>
            </div>
        </div>
        <?php endforeach; ?>
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
        
        document.getElementById("vol").oninput = function() { document.getElementsByTagName("label")[0].innerText = document.getElementsByTagName("label")[0].innerText.replace(document.getElementsByTagName("label")[0].innerText.substring(document.getElementsByTagName("label")[0].innerText.indexOf(":")), ": " + document.getElementById("vol").value + "/5");};
    });
</script>


<?php
//note we need to go up 1 more directory
require_once(__DIR__ . "/../../partials/flash.php");
?>