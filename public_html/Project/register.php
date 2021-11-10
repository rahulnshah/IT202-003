<?php
require(__DIR__ . "/../../partials/nav.php");
reset_session();
?>
<?php $email = se($_POST, "email", "", false); 
      $username = se($_POST, "username", "", false);
?>
<div class="container-fluid">
<h1>Register</h1>
<form onsubmit="return validate(this)" method="POST"> <!--removed min and maxlength attributes, because I have validation for those things-->
    <div class="mb-3">
        <label for="email">Email</label>
        <input type="email" name="email" class="form-control form-control-sm" value="<?php se($email); ?>"/>
    </div>
    <div class="mb-3">
        <label for="username">Username</label>
        <input type="text" name="username" class="form-control form-control-sm" value="<?php se($username); ?>"/>
    </div>
    <div class="mb-3">
        <label for="pw">Password</label>
        <input type="password" id="pw" name="password" class="form-control form-control-sm"/>
    </div>
    <div class="mb-3">
        <label for="confirm">Confirm</label>
        <input type="password" name="confirm" class="form-control form-control-sm"/>
    </div>
    <input  class="btn btn-primary" type="submit" value="Register" />
</form>
</div>
<script>
    function validate(form) {
        //TODO 1: implement JavaScript validation
        //ensure it returns false for an error and true for success
        let flashElement = document.getElementById("flash");
        if(flashElement.children.length > 0)
        {
            while (flashElement.firstChild) {
                flashElement.removeChild(flashElement.firstChild);
            }
        }
        
        const formFieldOne = form.elements[0];
        const formFieldTwo = form.elements[1];
        const formFieldThree = form.elements[2];
        const formFieldFour = form.elements[3];
        let retVal = true;
        
        if (formFieldOne.value.length > 0 && formFieldTwo.value.length > 0 && formFieldThree.value.length > 0 && formFieldFour.value.length > 0) {
            if (!(/^([a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6})*$/.test(formFieldOne.value))) {
                //check if email is correctly formatted 
                flash("Invalid email address", "warning");
                console.log("You missed something");
                retVal = false;
            } 
            // the user has entered a username
            if (!(/^[a-z0-9_-]{3,30}$/i.test(formFieldTwo.value))){
                    flash("Username must only be alphanumeric and can only contain - or _");
                    retVal = false;
            }
            if(formFieldThree.value.length >= 8 && formFieldThree.value !== formFieldFour.value)
            {
                // flash("Password must be at least 8 characters", "warning");
                // retVal = false;
                //check if both match 
                flash("Passwords must match");
                retVal = false; 
            } 
            else{
                if(formFieldThree.value.length < 8)
                {
                    flash("Password must be at least 8 characters", "warning");
                    retVal = false;
                } 
                if(formFieldFour.value.length < 8)
                {
                    flash("Confirm must be at least 8 characters", "warning");
                    retVal = false;
                }
            } 
        }
        else
        {
            if(formFieldOne.value.length <= 0)
            {
                retVal = false;
                // show this flash message only if there is not already a message like this on top of page 
                flash("Email must not be empty");
            }
            if(formFieldTwo.value.length <= 0)
            {
                retVal = false;
                flash("Username must be set");
            }
            if(formFieldThree.value.length <= 0)
            {
                retVal = false;
                // show this flash message only if there is not already a message like this on top of page 
                flash("Password must be set");
            }
            if(formFieldFour.value.length <= 0)
            {
                retVal = false;
                // show this flash message only if there is not already a message like this on top of page 
                flash("Confirm password must not be empty");
            }
        }
        // console.log(retVal);
        return retVal;
    }
</script>
<?php
//TODO 2: add PHP Code
if (isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["confirm"])) {
    $email = se($_POST, "email", "", false);
    $password = se($_POST, "password", "", false);
    $confirm = se($_POST, "confirm", "", false);
    $username = se($_POST, "username", "", false);
    //TODO 3


    //$errors = [];
    $hasError = false;
    if (empty($email)) {
        flash("Email must not be empty");
        $hasError = true;
    }
    //$email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $email = sanitize_email($email);
    //validate
    //if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    if (!is_valid_email($email)) {
        flash("Invalid email");
        $hasError = true;
    }
    //check if $username is not blank on the server side 
    if(!empty($username))
    {
        if (!preg_match('/^[a-z0-9_-]{3,30}$/i', $username)) {
            flash("Username must only be alphanumeric and can only contain - or _");
            $hasError = true;
        }
    }
    else
    {
        flash("Username must be set");
        $hasError = true;
    }
    if (empty($password)) {
        flash("password must not be empty");
        $hasError = true;
    }
    if (empty($confirm)) {
        flash("Confirm password must not be empty");
        $hasError = true;
    }
    if (strlen($password) < 8) {
        flash("Password too short");
        $hasError = true;
    }
    if (strlen($password) > 0 && $password !== $confirm) {
        flash("Passwords must match");
        $hasError = true;
    }
    if ($hasError) {
        //flash("<pre>" . var_export($errors, true) . "</pre>");
    } else {
        //flash("Welcome, $email"); //will show on home.php
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO Users (email, password, username) VALUES(:email, :password, :username)");
        try {
            $stmt->execute([":email" => $email, ":password" => $hash, ":username" => $username]);
            flash("You've registered, yay...");
        } catch (Exception $e) {
            /*flash("There was a problem registering");
            flash("<pre>" . var_export($e, true) . "</pre>");*/
            users_check_duplicate($e->errorInfo);
        }
    }
}
?>
<?php
require(__DIR__ . "/../../partials/flash.php");
?>