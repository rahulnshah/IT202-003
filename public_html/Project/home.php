<?php
require(__DIR__."/../../partials/nav.php");
?>
<h1>Home</h1>
<?php
if(is_logged_in()){
 echo "Welcome, " . $_SESSION["user"]["email"]; 
}
else{
  echo "You're not logged in";
}
?>