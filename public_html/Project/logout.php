<?php
session_start();
session_unset();
session_destroy();
header("Location: login.php");
require(__DIR__ . "/../../lib/functions.php");
flash("Successfully logged out", "success");
header("Location: login.php");
?>