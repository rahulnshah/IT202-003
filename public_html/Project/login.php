<?php
require(__DIR__ . "/../../partials/nav.php"); 
?>
<?php
//TODO 2: add PHP Code
if (isset($_POST["email"]) && isset($_POST["password"])) {
    //get the email key from $_POST, default to "" if not set, and return the value
    $email = se($_POST, "email", "", false);
    //same as above but for password
    $password = se($_POST, "password", "", false);
    //TODO 3: validate/use
    //$errors = [];
    $hasErrors = false;
    if (empty($email)) {
        //array_push($errors, "Email must be set");
        flash("Username or email must be set", "warning");
        $hasErrors = true;
    }
    //sanitize
    //$email = filter_var($email, FILTER_SANITIZE_EMAIL);
    if (str_contains($email, "@")) {
        $email = sanitize_email($email);
        //validate
        //if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        if (!is_valid_email($email)) {
            //array_push($errors, "Invalid email address");
            flash("Invalid email address", "warning");

            $hasErrors = true;
        }
    } else {
        if (!preg_match('/^[a-z0-9_-]{3,30}$/i', $email)) {
            flash("Username must only be alphanumeric and can only contain - or _");
            $hasErrors = true;
        }
    }
    if (empty($password)) {
        //array_push($errors, "Password must be set");
        flash("Password must be set");
        $hasErrors = true;
    }
    if (strlen($password) < 8) {
        //array_push($errors, "Password must be 8 or more characters");
        flash("Password must be at least 8 characters", "warning");
        $hasErrors = true;
    }
    if ($hasErrors) {
        //Nothing to output here, flash will do it
        //can likely flip the if condition
        //echo "<pre>" . var_export($errors, true) . "</pre>";
    } else {
        //TODO 4
        $db = getDB();
        $stmt = $db->prepare("SELECT id, username, email, password from Users where email = :email or username = :email");
        try {
            $r = $stmt->execute([":email" => $email]);
            if ($r) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($user) {
                    $hash = $user["password"];
                    unset($user["password"]);
                    if (password_verify($password, $hash)) {
                        $_SESSION["user"] = $user;
                        //echo var_export($_SESSION,true);
                        //lookup potential roles
                        //echo var_export($user,true);
                        $stmt = $db->prepare("SELECT Roles.name FROM Roles 
                        JOIN UserRoles on Roles.id = UserRoles.role_id 
                        where UserRoles.user_id = :user_id and Roles.is_active = 1 and UserRoles.is_active = 1");
                        $stmt->execute([":user_id" => $user["id"]]);
                        $roles = $stmt->fetchAll(PDO::FETCH_ASSOC); //fetch all since we'll want multiple
                        //save roles or empty array
                        //echo var_export($roles,true);
                        if ($roles) {
                            $_SESSION["user"]["roles"] = $roles; //at least 1 role
                        } else {
                            $_SESSION["user"]["roles"] = []; //no roles
                        }
                        redirect("home.php");
                    } else {
                        //echo "Invalid password";
                        flash("Invalid password", "danger");
                    }
                } else {
                    //echo "Invalid email";
                    flash("Email/Username not found", "danger");
                }
            }
        } catch (Exception $e) {
            //echo "<pre>" . var_export($e, true) . "</pre>";
            flash(var_export($e, true));
        }
    }
}
?>
<?php $email = se($_POST, "email", "", false); ?>
<div class="container-fluid">
<h1>Login</h1>
<form onsubmit="return validate(this)" method="POST">
    <div class="mb-3">
        <label for="email">Username/Email</label>
        <input type="text" class="form-control form-control-sm" name="email" value="<?php se($email); ?>"/>
    </div>
    <div class="mb-3">
        <label for="pw">Password</label>
        <input type="password" id="pw" class="form-control form-control-sm" name="password"/> <!-- rmoved minlength attr, and required attr to see php error messages-->
    </div>
    <input type="submit" class="btn btn-primary" value="Login" />
</form>
</div>
<script>
    function validate(form) {
        //TODO 1: implement JavaScript validation
        //ensure it returns false for an error and true for success
        //clear error messages
        let flashElement = document.getElementById("flash");
        flashElement.innerHTML = "";
        const formFieldOne = form.elements[0];
        const formFieldTwo = form.elements[1];
        let retVal = true;
        
        if (formFieldOne.value.length > 0 && formFieldTwo.value.length > 0) {
            if (formFieldOne.value.indexOf("@") > -1) {
                //check if email is correctly formatted 
                if (!(/^([a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6})*$/.test(formFieldOne.value))){
                    
                    flash("Invalid email address", "warning");
                    retVal = false;
                }
            } 
            else { // the user has entered a username
                if (!(/^[a-z0-9_-]{3,30}$/i.test(formFieldOne.value))){
                    flash("Username must only be alphanumeric and can only contain - or _");
                    retVal = false;
                }
            }
            if(formFieldTwo.value.length < 8)
            {
                flash("Password must be at least 8 characters", "warning");
                retVal = false;
            }   
        }
        else
        {
            if(formFieldOne.value.length <= 0)
            {
                retVal = false;
                // show this flash message only if there is not already a message like this on top of page 
                flash("Username or email must be set", "warning");
            }
            if(formFieldTwo.value.length <= 0)
            {
                retVal = false;
                flash("Password must be set");
            }
        }
        //console.log(retVal);
        return retVal;
    }
</script>
<?php
require(__DIR__ . "/../../partials/flash.php");
?>