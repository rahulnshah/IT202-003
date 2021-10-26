<?php
require(__DIR__ . "/../../partials/nav.php");
?>
<h1>Home</h1>
<?php
<<<<<<< HEAD
if (is_logged_in()) {
=======

if (is_logged_in(true)) {
>>>>>>> 4497fde24f91bd3d93ec2fb3560fc91157f93850
    echo "Welcome home, " . get_username();
    //comment this out if you don't want to see the session variables
    /*echo "<pre>" . var_export($_SESSION, true) . "</pre>";*/
}
?>
<?php
require(__DIR__ . "/../../partials/flash.php");
?>