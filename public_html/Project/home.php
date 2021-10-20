<?php
require(__DIR__ . "/../../partials/nav.php");
?>
<h1>Home</h1>
<?php
if (is_logged_in()) {
    echo "Welcome home, " . get_user_email();
    //comment this out if you don't want to see the session variables
    echo "<pre>" . var_export($_SESSION, true) . "</pre>";
} else {
    echo "You're not logged in";
}
?>
<?php
require(__DIR__ . "/../../partials/flash.php");
?>