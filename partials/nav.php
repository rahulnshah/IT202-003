<?php
//Note: this is to resolve cookie issues with port numbers
$domain = $_SERVER["HTTP_HOST"];
if (strpos($domain, ":")) {
    $domain = explode(":", $domain)[0];
}
$localWorks = true; //some people have issues with localhost for the cookie params
//if you're one of those people make this false

//this is an extra condition added to "resolve" the localhost issue for the session cookie
if (($localWorks && $domain == "localhost") || $domain != "localhost") {
    session_set_cookie_params([
        "lifetime" => 60 * 60,
        "path" => "/Project",
        //"domain" => $_SERVER["HTTP_HOST"] || "localhost",
        "domain" => $domain,
        "secure" => true,
        "httponly" => true,
        "samesite" => "lax"
    ]);
}
session_start();
require_once(__DIR__ . "/../lib/functions.php");

?>
<!-- include css and js files -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<link rel="stylesheet" href="<?php echo get_url('styles.css'); ?>">
<script src="<?php echo get_url('helpers.js'); ?>"></script>
<script>
    $(document).ready(function(){
        let textFields = document.querySelectorAll("input[type=text], input[type=email], input[type=password]");
        textFields.forEach(function(activeLink) {
            $(activeLink).focus(
                function(){
                    $(this).css("background-color", "yellow");
                    $(this).css("border-style", "");
                    $(this).css("border-color", "");
                    $(this).css("border-width", "");
                }
            );
        });
        textFields.forEach(function(activeLink) {
            $(activeLink).blur(
                function(){
                    $(this).css("border-style", "solid");
                    if(this.value.length > 0)
                    {
                        $(this).css("border-color", "");
                    }
                    else
                    {
                        $(this).css("border-color", "red");
                    }
                    $(this).css("border-width", "medium");
                    $(this).css("background-color", "");
                }
            );
        });
        let allActiveLinks = document.getElementsByClassName("navbar navbar-expand-lg navbar-light bg-primary")[0].querySelectorAll(".nav-link.active");
        allActiveLinks.forEach(function(activeLink) {
            $(activeLink).hover(
                function(){
                    $(this).css("color", "white");
                },
                function(){
                    $(this).css("color", "");
                }
            );
        });
        let allDropDownLinks = document.getElementsByClassName("navbar navbar-expand-lg navbar-light bg-primary")[0].querySelectorAll(".dropdown-item");
        console.log(allDropDownLinks);
        allDropDownLinks.forEach(function(activeLink) {
            $(activeLink).hover(
                function(){
                    $(this).css("color", "white");
                    $(this).css("background-color", "black");
                },
                function(){
                    $(this).css("color", "");
                    $(this).css("background-color", "");
                }
            );
        });
        if(allDropDownLinks.length > 0)
        {
            let dropDownMenu = document.getElementById("navbarDropdown");
            $(dropDownMenu).hover(
                function()
                {
                    $(this).css("color", "white");
                },
                function()
                {
                    $(this).css("color", "");
                }
            
            );
        }
    }
    );
</script>
<nav class="navbar navbar-expand-lg navbar-light bg-primary">
  <div class="container-fluid">
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <?php if (is_logged_in()) : ?>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="<?php echo get_url('home.php'); ?>">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="<?php echo get_url('Profile.php'); ?>">Profile</a>
                </li>
            <?php endif; ?>
            <?php if (!is_logged_in()) : ?>
                <li class="nav-item"><a class="nav-link active" aria-current="page" href="<?php echo get_url('login.php'); ?>">Login</a></li>
                <li class="nav-item"><a class="nav-link active" aria-current="page" href="<?php echo get_url('register.php'); ?>">Register</a></li>
                <li class="nav-item"><a class="nav-link active" aria-current="page" href="<?php echo get_url('shop.php'); ?>">Shop</a></li>
                <li class="nav-item"><a class="nav-link active" aria-current="page" href="<?php echo get_url('orders.php'); ?>">Orders</a></li>
            <?php endif; ?>
            <?php if (is_logged_in()) : ?>
                <li class="nav-item"><a class="nav-link active" aria-current="page" href="<?php echo get_url('logout.php'); ?>">Logout</a></li>
                <li class="nav-item"><a class="nav-link active" aria-current="page" href="<?php echo get_url('shop.php'); ?>">Shop</a></li>
                <li class="nav-item"><a class="nav-link active" aria-current="page" href="<?php echo get_url('orders.php'); ?>">Orders</a></li>
                <li class="nav-item"><a id="show-numOfCart-items" class="nav-link active" aria-current="page" href="<?php echo get_url('cart.php'); ?>"></a>
                </li>
            <?php endif; ?>
            <?php if (has_role("Admin")) : ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Admin
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <?php if (has_role("Admin")) : ?>
                            <li><a class="dropdown-item" href="<?php echo get_url('admin/create_role.php'); ?>">Create Role</a></li>
                            <li><a class="dropdown-item" href="<?php echo get_url('admin/list_roles.php'); ?>">List Roles</a></li>
                            <li><a class="dropdown-item" href="<?php echo get_url('admin/assign_roles.php'); ?>">Assign Roles</a></li>
                            <li><a class="dropdown-item" href="<?php echo get_url('admin/add_product.php'); ?>">Add Product</a></li>
                            <li><a class="dropdown-item" href="<?php echo get_url('admin/list_products.php'); ?>">List Products</a></li>
                            <li><a class="dropdown-item" href="<?php echo get_url('admin/list_purchase_history.php'); ?>">List Purchase History</a></li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<div id="cart-value">
    Cart: <?php echo get_number_of_cartItems(); ?>
</div>
<script>
    let bv = document.getElementById("cart-value");
    //I'll make this flexible so I can define various placeholders and copy
    //the value into all of them
    let placeholder = document.getElementById("show-numOfCart-items");
    //if place holder exists, then do the following, remove bv either way. 
        //https://developer.mozilla.org/en-US/docs/Web/API/Node/cloneNode
    if(placeholder !== null)
    {
        placeholder.innerHTML = bv.outerHTML;//bv.cloneNode(true).outerHTML;
    }
    bv.remove(); //delete the original
</script>