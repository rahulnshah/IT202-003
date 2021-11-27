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
<link rel="stylesheet" href="<?php echo get_url('styles.css'); ?>">
<script src="<?php echo get_url('helpers.js'); ?>"></script>
<nav class="navbar navbar-expand-lg navbar-light bg-primary">
  <div class="container-fluid">
    <!-- <a class="navbar-brand" href="#">Navbar</a>-->
    <!-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button> -->
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <?php if (is_logged_in()) : ?>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="<?php echo get_url('home.php'); ?>">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="<?php echo get_url('profile.php'); ?>">Profile</a>
                </li>
            <?php endif; ?>
            <?php if (!is_logged_in()) : ?>
                <li class="nav-item"><a class="nav-link active" aria-current="page" href="<?php echo get_url('login.php'); ?>">Login</a></li>
                <li class="nav-item"><a class="nav-link active" aria-current="page" href="<?php echo get_url('register.php'); ?>">Register</a></li>
                <li class="nav-item"><a class="nav-link active" aria-current="page" href="<?php echo get_url('shop.php'); ?>">Shop</a></li>
            <?php endif; ?>
            <?php if (is_logged_in()) : ?>
                <li class="nav-item"><a class="nav-link active" aria-current="page" href="<?php echo get_url('logout.php'); ?>">Logout</a></li>
                <li class="nav-item"><a class="nav-link active" aria-current="page" href="<?php echo get_url('shop.php'); ?>">Shop</a></li>
                <li class="nav-item"><a class="nav-link active" aria-current="page" href="<?php echo get_url('cart.php'); ?>">Cart</a></li>
            <?php endif; ?>
            <?php if (has_role("Admin")) : ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Dropdown
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <?php if (has_role("Admin")) : ?>
                            <li><a class="dropdown-item" href="<?php echo get_url('admin/create_role.php'); ?>">Create Role</a></li>
                            <li><a class="dropdown-item" href="<?php echo get_url('admin/list_roles.php'); ?>">List Roles</a></li>
                            <li><a class="dropdown-item" href="<?php echo get_url('admin/assign_roles.php'); ?>">Assign Roles</a></li>
                            <li><a class="dropdown-item" href="<?php echo get_url('admin/add_product.php'); ?>">Add Product</a></li>
                            <li><a class="dropdown-item" href="<?php echo get_url('admin/list_products.php'); ?>">List Products</a></li>
                            <!-- add a separate shop for admin so that he/she could see the products with any visibility-->
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>
        <!-- <li class="nav-item">
          <a class="nav-link disabled">Disabled</a>
        </li> -->
      </ul>
      <!-- <form class="d-flex">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form> -->
    </div>
  </div>
</nav>