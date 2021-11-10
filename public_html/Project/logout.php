<?php
session_start();
//setcookie("PHPSESSID","",time()-3600);
require(__DIR__ . "/../../lib/functions.php");
reset_session(); //start new session here  

flash("Successfully logged out", "success");
header("Location: login.php");
?>