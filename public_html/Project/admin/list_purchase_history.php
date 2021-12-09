<?php
//note we need to go up 1 more directory
require(__DIR__ . "/../../../partials/nav.php");

if (!has_role("Admin")) {
    flash("You don't have permission to view this page", "warning");
    redirect("$BASE_PATH" . "home.php");
}

$results = [];

    $db = getDB();
    $stmt = $db->prepare("SELECT id, user_id, total_price, payment_method, address from Orders ORDER BY created DESC LIMIT 10");
    try {
        $stmt->execute();
        $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($r) {
            $results = $r;
        }
        //echo "<pre>" . var_export($results, true) . "</pre>";
    } catch (PDOException $e) {
        flash("<pre>" . var_export($e, true) . "</pre>");
    }

?>
<div class="container-fluid">
    <h1>List Orders</h1>
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
                        <a class="btn btn-primary" href="../order_details.php?order_id=<?php se($record, "id"); ?>">View</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>
<?php
//note we need to go up 1 more directory
require_once(__DIR__ . "/../../../partials/flash.php");
?>