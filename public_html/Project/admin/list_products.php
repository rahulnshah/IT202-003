<?php
//note we need to go up 1 more directory
require(__DIR__ . "/../../../partials/nav.php");

if (!has_role("Admin")) {
    flash("You don't have permission to view this page", "warning");
    redirect("../home.php");
}

$results = [];
$itemName = se($_POST, "itemName", "", false);
$stockToCheck = se($_POST, "stockToCheck", "", false);
$db = getDB();
$query = "SELECT id, name, description, stock, category, unit_price, visibility from Products WHERE name like :name";
$params = [];
$params[":name"] = "%" . $itemName . "%";
if(!empty($stockToCheck))
{
    $query .= " OR stock <= :stockToCheck";
    $params[":stockToCheck"] = $stockToCheck; 
}
$total_query = str_replace("id, name, description, stock, category, unit_price, visibility","count(1) as total",$query);
$per_page = 10;
paginate($total_query, $params, $per_page); //$per_page defualts to 10 in the paginate function
if((int) $total_pages > 0)
{
    $query .= " LIMIT :offset, :count";
    $params[":offset"] = $offset;
    $params[":count"] = $per_page;
    $stmt = $db->prepare($query);
    foreach ($params as $key => $value) {
        $type = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
        $stmt->bindValue($key, $value, $type);
    }
    $params = null;
    try {
        $stmt->execute($params);
        $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $results = $r;
    } catch (PDOException $e) {
            flash("<pre>" . var_export($e, true) . "</pre>");
    }
}

?>
<div class="container-fluid">
    <h1>List Products</h1>
    <form method="POST" class="row row-cols-lg-auto g-3 align-items-center">
        <div class="input-group mb-3">
            <input class="form-control" type="search" name="itemName" placeholder="Item Filter" />
            <input class="btn btn-primary" type="submit" value="Search"/>
        </div>
        <div class="input-group">
            <input class="form-control" type="number" id="stockToCheck" name="stockToCheck" value="<?php se($item, 'desired_quantity'); ?>">
            <input type="submit" class="btn btn-primary" value="Check Stock">
        </div>
    </form>
    <?php if (count($results) == 0) : ?>
        <p>No results to show</p>
    <?php else : ?>
        <table class="table table-bordered border-primary">
            <?php foreach ($results as $index => $record) : ?>
                <?php if ($index == 0) : ?>
                    <thead>
                        <?php foreach ($record as $column => $value) : ?>
                            <th><?php se($column); ?></th>
                        <?php endforeach; ?>
                        <th>Actions</th>
                    </thead>
                <?php endif; ?>
                <tr>
                    <?php foreach ($record as $column => $value) : ?>
                        <td><?php se($value, null, "N/A"); ?></td>
                    <?php endforeach; ?>
                    <td>
                        <a class="btn btn-primary" href="edit_product.php?id=<?php se($record, "id"); ?>">Edit</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php require(__DIR__ . "/../../../partials/pagination.php"); ?>
    <?php endif; ?>
</div>
<?php
//note we need to go up 1 more directory
require_once(__DIR__ . "/../../../partials/flash.php");
?>