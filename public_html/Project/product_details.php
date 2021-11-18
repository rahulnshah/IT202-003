<?php
//note we need to go up 1 more directory
require(__DIR__ . "/../../partials/nav.php");

// if (!has_role("Admin")) {
//     flash("You don't have permission to view this page", "warning");
//     die(header("Location: $BASE_PATH" . "home.php"));
// }
//get the table definition
$result = [];
$columns = get_columns("Products");
//echo "<pre>" . var_export($columns, true) . "</pre>";
$ignore = ["id", "modified", "created", "visibility"];
$db = getDB();
//get the item
$id = se($_GET, "id", -1, false);
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
//echo "<pre>" . var_export($result,true) . "</pre>";
?>
<div class="container-fluid">
    <h1><?php se($result,"name"); ?> Details</h1>
    <form method="POST">
        <?php foreach ($result as $column => $value) : ?>
            <?php if (!in_array($column, $ignore)) : ?> 
                <h3><?php echo str_replace("_", " ", se($column,null,"",false)); ?> :</h3>
                <!--<?php var_dump(se($column,null,"",false)); ?>-->
                <?php if (se($column,null,"",false) === "unit_price") : ?> 
                    <p><?php echo "$" . se($value,null,"",false); ?></p>
                <?php endif; ?>
                <?php if (se($column,null,"",false) !== "unit_price") : ?> 
                    <p><?php se($value); ?></p> <!-- equivalent to echo $value; -->
                <?php endif; ?> 
            <?php endif; ?>
        <?php endforeach; ?>
        <?php if(has_role("Admin")) : ?>
            <a class="btn btn-primary" href="admin/edit_product.php?id=<?php echo se($result, "id", "", false);?>">Edit</a>
        <?php endif; ?>
    </form>
</div>

<?php
//note we need to go up 1 more directory
require_once(__DIR__ . "/../../partials/flash.php");
?>