<?php
require_once(__DIR__ . "/../../partials/nav.php");
if (!is_logged_in()) {
    redirect("login.php");
}
?>
<?php
//show the user profile if it is the user
$user_id = se($_GET, "id", get_user_id(), false);
error_log("user id $user_id");
$isMe = $user_id === get_user_id();
if ($user_id < 1) {
    flash("Invalid user", "danger");
    redirect("home.php");
    //die(header("Location: home.php"));
}
if (isset($_POST["save"]) && $isMe) {
    $email = se($_POST, "email", null, false);
    $username = se($_POST, "username", null, false);
    //Notes:
    // I am updating a row, with the given username and email, but if either alredy exists in its respective col, 
    // SQL stops the update.
    // even though I said user name cannot be empty, for safety reasons let's just check if it is not empty 
    $errors = false;
    if (empty($email)) {
        flash("Email must not be empty. Refresh this page.");
        $errors = true;
    }
    //$email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $email = sanitize_email($email);
    //validate
    //if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    if (!is_valid_email($email)) {
        flash("Invalid email");
        $errors = true;
    }
    
    if(!empty($username))
    {
        if (!preg_match('/^[a-z0-9_-]{3,30}$/i', $username)) {
            flash("Username must only be alphanumeric and can only contain - or _");
            $errors = true;
        }
    }
    else
    {
        flash("Username must be set. Refresh this page.");
        $errors = true;
    }
    if(!$errors)
    {
        $params = [":email" => $email, ":username" => $username, ":id" => get_user_id()];
        $db = getDB();
        $stmt = $db->prepare("UPDATE Users set email = :email, username = :username where id = :id");
        try {
            $stmt->execute($params);
        } catch (Exception $e) {
            if ($e->errorInfo[1] === 1062) {
                //https://www.php.net/manual/en/function.preg-match.php
                preg_match("/Users.(\w+)/", $e->errorInfo[2], $matches);
                //echo $matches[0];
                if (isset($matches[1])) {
                    flash("The chosen " . $matches[1] . " is not available.", "warning");
                } else {
                    //TODO come up with a nice error message
                    flash("An unexpected error occurred, please try again", "danger");
                }
            } else {
                //TODO come up with a nice error message
                flash("The chosen email and username are available, but an unexpected error occurred. Please try again", "danger");
            }
        }
        //select fresh data from table
        $stmt = $db->prepare("SELECT id, email, username from Users where id = :id LIMIT 1");
        try {
            $stmt->execute([":id" => get_user_id()]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                //$_SESSION["user"] = $user;
                $_SESSION["user"]["email"] = $user["email"];
                $_SESSION["user"]["username"] = $user["username"];
            } else {
                flash("User doesn't exist", "danger");
            }
        } catch (Exception $e) {
            flash("An unexpected error occurred, please try again", "danger");
            //echo "<pre>" . var_export($e->errorInfo, true) . "</pre>";
        }


        //check/update password and password reset is optional for the user.
        $current_password = se($_POST, "currentPassword", null, false);
        $new_password = se($_POST, "newPassword", null, false);
        $confirm_password = se($_POST, "confirmPassword", null, false);
        if (!empty($current_password) && !empty($new_password) && !empty($confirm_password)) {
            $hasErrors = false;
            if(strlen($current_password) < 8)
            {
                flash("Current password must be at least 8 characters", "warning");
                $hasErrors = true;
            }
            if(strlen($new_password) < 8)
            {
                flash("New password must be at least 8 characters", "warning");
                $hasErrors = true;
            }
            if(strlen($confirm_password) < 8)
            {
                flash("Confirm password must be at least 8 characters", "warning");
                $hasErrors = true;
            }
            if ($new_password === $confirm_password && !$hasErrors) {
                //TODO validate current --> meaning heck if all passwords have a length >= 8
                $stmt = $db->prepare("SELECT password from Users where id = :id");
                try {
                    $stmt->execute([":id" => get_user_id()]);
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    if (isset($result["password"])) {
                        if (password_verify($current_password, $result["password"])) {
                            $query = "UPDATE Users set password = :password where id = :id";
                            $stmt = $db->prepare($query);
                            $stmt->execute([
                                ":id" => get_user_id(),
                                ":password" => password_hash($new_password, PASSWORD_BCRYPT)
                            ]);

                            flash("Password reset", "success");
                        } else {
                            flash("Current password is invalid", "warning");
                        }
                    }
                } catch (Exception $e) {
                    // add a nice error messae here 
                    flash("You must be logged in to view this page", "warning");
                }
            } else {
                flash("New passwords don't match", "warning");
            }
        }
    }
}
$email = get_user_email();
$username = get_username();
$public = false;
$db = getDB();
$stmt = $db->prepare("SELECT username, email, visibility from Users where id = :id");
try {
    $stmt->execute([":id" => $user_id]); // user_id could be the id of the loggedin user.
    $r = $stmt->fetch(PDO::FETCH_ASSOC);
    error_log("user: " . var_export($r, true));
    $username = se($r, "username", "", false);
    $public = se($r, "visibility", 0, false) > 0;
    if (!$public && !$isMe) { // if profile is public or isMe this is false, and I stay on profile.php 
        flash("User's profile is private", "warning");
        redirect("home.php");
        //die(header("Location: home.php"));
    }
    else if(!$isMe && $public) // does matter than if the profile if public or private, I get my user and password, which are set by defualt
    {
        //unset the email, keep the username.
        unset($email); 
    }
} catch (Exception $e) {
    echo "<pre>" . var_export($e->errorInfo, true) . "</pre>";
}
?>

<?php
    $email = get_user_email(); //of the logged in user, want to only show this the user viewing their own profile, you don;t want to show this when the 
    //user vistis someone else's profile, it will default to your own 
    $username = get_username();
?>
<div class="container-fluid">
<h1>Profile</h1>
<form method="POST" onsubmit="return validate(this);">
    <div class="mb-3">
        <label for="email">Email</label>
        <input  class="form-control form-control-sm"  type="email" name="email" id="email" value="<?php se($email); ?>" />
    </div>
    <div class="mb-3">
        <label for="username">Username</label>
        <input  class="form-control form-control-sm"  type="text" name="username" id="username" value="<?php se($username); ?>" />
    </div>
    <!-- DO NOT PRELOAD PASSWORD -->
    <div>Password Reset</div>
    <div class="mb-3">
        <label for="cp">Current Password</label>
        <input  class="form-control form-control-sm" type="password" name="currentPassword" id="cp" />
    </div>
    <div class="mb-3">
        <label for="np">New Password</label>
        <input  class="form-control form-control-sm"  type="password" name="newPassword" id="np" />
    </div>
    <div class="mb-3">
        <label for="conp">Confirm Password</label>
        <input  class="form-control form-control-sm"  type="password" name="confirmPassword" id="conp" />
    </div>
    <input type="submit"  class="btn btn-primary" value="Update Profile" name="save" />
</form>
</div>
<script>
    function validate(form) {
        //clear any error messages at top 
        let flashElement = document.getElementById("flash");
        if(flashElement.children.length > 0)
        {
            while (flashElement.firstChild) {
                flashElement.removeChild(flashElement.firstChild);
            }
        }
        let pw = form.newPassword.value;
        let con = form.confirmPassword.value;
        let userEmail = form.email.value;
        let userName = form.username.value;
        let cw = form.currentPassword.value;
        let isValid = true;
        //TODO add other client side validation....
        //cannot let user input empty emails, and username 
        if(userEmail.length > 0 && userName.length > 0)
        {
            //validate email
            if (!(/^([a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6})*$/.test(userEmail))) {
                //check if email is correctly formatted 
                flash("Invalid email address", "warning");
                //console.log("You missed something");
                isValid = false;
            }
            //validate username 
            if (!(/^[a-z0-9_-]{3,30}$/i.test(userName))){
                flash("Username must only be alphanumeric and can only contain - or _");
                isValid = false;
            }
        }
        else
        {
            if(userEmail.length <= 0)
            {
                isValid = false;
                // show this flash message only if there is not already a message like this on top of page 
                flash("Email must not be empty. Refresh this page.");
            }
            if(userName.length <= 0)
            {
                isValid = false;
                flash("Username must be set. Refresh this page.");
            }      
        }
        //new password and confirm password 
        if(cw.length > 0 && pw.length > 0 && con.length > 0)
        {
            //check
            if(cw.length >= 8 && pw.length >= 8)
            {
                if(pw !== con)
                {
                    flash("New password and Confirm password don't match", "warning");
                    isValid = false;
                }
            }
            else
            {
                if(cw.length < 8)
                {
                    flash("Current password must be at least 8 characters", "warning");
                    isValid = false;
                }
                if(pw.length < 8)
                {
                    flash("New password must be at least 8 characters", "warning");
                    isValid = false;
                } 
                if(con.length < 8)
                {
                    flash("Confirm password must be at least 8 characters and match new password", "warning");
                    isValid = false;
                } 
            }
        }
        return isValid;
    }
</script>
<?php
require_once(__DIR__ . "/../../partials/flash.php");
?>