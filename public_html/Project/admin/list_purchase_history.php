<?php
//note we need to go up 1 more directory
require(__DIR__ . "/../../../partials/nav.php");

if (!has_role("Admin")) {
    flash("You don't have permission to view this page", "warning");
    redirect("../home.php");
}

$results = [];
$db = getDB();
$orderCol = se($_GET, "order", "total_price", false);
//allowed list
if (!in_array($orderCol, ["total_price", "created"])) {
    $orderCol = "total_price"; //default value, prevent sql injection
}
$upOrdown = se($_GET, "aOrd", "asc", false);
//allowed list
if (!in_array($upOrdown, ["asc", "desc"])) {
    $upOrdown = "asc"; //default value, prevent sql injection
}
//cannot know before hand all the appropiate date ranges with SQL
$aDateRange = se($_GET, "dateRanges", "", false);
$aCategory = se($_GET, "categories", "", false);
$query = "SELECT Orders.id, Orders.user_id, Orders.total_price, Orders.created as created, Orders.payment_method, Orders.address from Orders";
$params = [];
if(!empty($aDateRange))
{
    $dateArr = explode(" ", $aDateRange);
    if(count($dateArr) >= 2)
    {
        $date_1 = $dateArr[0];
        $date_2 = $dateArr[2];
        $query .= " where DATE(created) BETWEEN :date_1 AND :date_2 AND";
        $params[":date_1"] = date("Y-m-d",strtotime($date_1));
        $params[":date_2"] = date("Y-m-d",strtotime($date_2));
    }
}
else if(!empty($aCategory))
{
    $query .= " INNER JOIN OrderItems ON Orders.id = OrderItems.order_id INNER JOIN Products ON Products.id = OrderItems.product_id WHERE Products.category = :category AND";
    $params[":category"] = $aCategory;
}
else
{
    $query .= " where";
}
$query .= " Orders.user_id = :user_id";
$params[":user_id"] = get_user_id();
//paginate 
$total_query = str_replace("Orders.user_id, Orders.id, Orders.created as created, Orders.total_price, Orders.payment_method, Orders.address","count(1) as total",$query);
$per_page = 10;
paginate($total_query, $params, $per_page); //$per_page defualts to 10 in the paginate function
$stmt = $db->prepare();
try {
    $stmt->execute();
    $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($r) 
    {
        $results = $r;
    }
        //echo "<pre>" . var_export($results, true) . "</pre>";
} 
catch (PDOException $e) {
    flash("<pre>" . var_export($e, true) . "</pre>");
}

?>
<div class="container-fluid">
    <h1>List Orders</h1>
    <?php echo "<pre>" . var_export($results,true) . "</pre>"; ?>
    <form class="row row-cols-auto g-3 align-items-center" id="myForm">
        <div class="col">
            <div class="input-group">
                <div class="input-group-text">Filter</div>
                <!-- make sure these match the in_array filter above-->
                <select class="form-control" name="col" form="myForm">
                    <option value="category">Category</option>
                    <option value="created">Date Purchased</option>
                </select>
                <script>
                    //quick fix to ensure proper value is selected since
                    //value setting only works after the options are defined and php has the value set prior
                </script>
                <div class="input-group-text">Categories</div>
                <!-- make sure these match the in_array filter above-->
                <select class="form-control" name="categories" form="myForm">
                        <!-- run a php for loop here -->
                        <?php foreach ($categories as $category) : ?>
                            <option value="<?php echo $category ?>"><?php echo $category ?></option>
                        <?php endforeach; ?>
                </select>
                <script>
                    //quick fix to ensure proper value is selected since
                    //value setting only works after the options are defined and php has the value set prior
                </script>
                <div class="input-group-text">Date Ranges</div>
                <!-- make sure these match the in_array filter above-->
                <select class="form-control" name="dateRanges" form="myForm">
                        <?php if (count($dates) > 0): ?>
                            <?php if (count($rangeOfDates) > 0): ?>
                                <?php foreach ($rangeOfDates as $dateRange) : ?>
                                    <option value="<?php se($dateRange)?>"><?php se($dateRange); ?></option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="<?php se($oldestDate->format("Y-m-d") . " to " . date_add($oldestDate,date_interval_create_from_date_string("2 days"))->format("Y-m-d"))?>"><?php se($oldestDate->format("Y-m-d") . " to " . date_add($oldestDate,date_interval_create_from_date_string("2 days"))->format("Y-m-d"))?></option>
                            <?php endif; ?>
                        <?php endif; ?>
                </select>
                <script>
                    //quick fix to ensure proper value is selected since
                    //value setting only works after the options are defined and php has the value set prior
                </script>
                <div class="input-group-text">Order By</div>
                <select class="form-control" name="order" form="myForm">
                    <option value="total_price">Total Price</option>
                    <option value="created">Date Purchased</option>
                </select>
                <script>
                    //quick fix to ensure proper value is selected since
                    //value setting only works after the options are defined and php has the value set prior
                </script>
                <div class="input-group-text">Sort</div>
                <select class="form-control" name="aOrd" form="myForm">
                    <option value="desc">High To Low/Recent to Old</option>
                    <option value="asc">Low To High/Old to Recent</option>
                </select>
                <script>
                    //quick fix to ensure proper value is selected since
                    //value setting only works after the options are defined and php has the value set prior
                </script>
            </div>
        </div>
        <div class="col">
            <div class="input-group">
                <input type="submit" class="btn btn-primary" value="Apply" />
            </div>
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
                        <a class="btn btn-primary" href="../order_details.php?order_id=<?php se($record, "id"); ?>">View</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>
<script>
                //hide the other column if the user selet one column
                //datePurchased.click(hide the categories div and select dropdown
                //and if the datePurchased div and datePurchased
                //select dropdown are noth there, unhide them);
                //categories.click(hide the datePurchased div and select dropdown, and if the categories div and 
                //select dropdwon are noth there, unhide them);
                //Example 
                $(document).ready(function(){
                    $(".input-group-text:nth-child(7)").hide(); 
                    $(".form-control:nth-child(8)").hide();
                    $(".form-control:nth-child(8)").attr("form", "anotherForm"); 
                    $("[name=col]").change(function(){ //date purchased select 
                        if(this.value === "created")
                        {
                            $(".input-group-text:nth-child(4)").hide(); // category div
                            $(".form-control:nth-child(5)").hide(); //category 
                            $(".form-control:nth-child(5)").attr("form", "anotherForm"); //category 
                            $(".input-group-text:nth-child(7)").show(); //date range div
                            $(".form-control:nth-child(8)").show();//date purchased 
                            $(".form-control:nth-child(8)").attr("form", "myForm");
                        }
                        else
                        {
                            $(".input-group-text:nth-child(7)").hide(); 
                            $(".form-control:nth-child(8)").hide(); 
                            $(".form-control:nth-child(8)").attr("form", "anotherForm");
                            $(".input-group-text:nth-child(4)").show(); 
                            $(".form-control:nth-child(5)").show();
                            $(".form-control:nth-child(5)").attr("form", "myForm");
                        }
                    });
            });
</script>
<?php
//note we need to go up 1 more directory
require_once(__DIR__ . "/../../../partials/flash.php");
?>