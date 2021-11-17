<?php
//note we need to go up 1 more directory
require(__DIR__ . "/../../../partials/nav.php");

if (!has_role("Admin")) {
    flash("You don't have permission to view this page", "warning");
    die(header("Location: $BASE_PATH" . "home.php"));
}
if (isset($_POST["submit"])) {
    $id = save_data("Products", $_POST);
    if ($id > 0) {
        flash("Created Item with id $id", "success");
    }
}
//get the table definition
$columns = get_columns("Products");
//echo "<pre>" . var_export($columns, true) . "</pre>";
$ignore = ["id", "modified", "created"]; //Admin can choose visisbility for a product 
?>
<div class="container-fluid">
    <h1>Add Product</h1>
    <form method="POST">
        <?php foreach ($columns as $index => $column) : ?>
            <?php /* Lazily ignoring fields via hardcoded array*/ ?>
            <?php if (!in_array($column["Field"], $ignore)) : ?>
                <?php if ($column["Field"] === "stock") : ?>
                <div class="mb-4">
                    <label class="form-label" for="<?php se($column, "Field"); ?>"><?php se($column, "Field"); ?></label>
                    <input class="form-control" id="<?php se($column, "Field"); ?>" type="<?php echo inputMap(se($column, "Type", "", false)); ?>" name="<?php se($column, "Field"); ?>" value="0"/>
                </div>
                <?php endif; ?>
                <?php if ($column["Field"] === "unit_price") : ?>
                    <div class="mb-4">
                        <label class="form-label" for="<?php se($column, "Field"); ?>"><?php se($column, "Field"); ?></label>
                        <input class="form-control" id="<?php se($column, "Field"); ?>" type="<?php echo inputMap(se($column, "Type", "", false)); ?>" name="<?php se($column, "Field"); ?>" value="000.00"/>
                    </div>
                <?php endif; ?>
                <?php if ($column["Field"] === "visibility") : ?>
                    <div class="mb-4">
                        <label class="form-label" for="<?php se($column, "Field"); ?>"><?php se($column, "Field"); ?></label>
                        <input class="form-control" id="<?php se($column, "Field"); ?>" type="<?php echo inputMap(se($column, "Type", "", false)); ?>" name="<?php se($column, "Field"); ?>" value="1"/>
                    </div>
                <?php endif; ?>
                <?php if (!in_array($column["Field"],["stock","unit_price","visibility"])) : ?>
                    <div class="mb-4">
                        <label class="form-label" for="<?php se($column, "Field"); ?>"><?php se($column, "Field"); ?></label>
                        <input class="form-control" id="<?php se($column, "Field"); ?>" type="<?php echo inputMap(se($column, "Type", "", false)); ?>" name="<?php se($column, "Field"); ?>" required/>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        <?php endforeach; ?>
        <input class="btn btn-primary" type="submit" value="Create" name="submit" />
    </form>
</div>
<?php
require(__DIR__ . "/../../../partials/flash.php");
?>