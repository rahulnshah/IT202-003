<?php
require_once(__DIR__ . "/../../partials/nav.php");
if (!is_logged_in()) {
    redirect("login.php");
}
//is_logged_in(true);
/**
 * Logic:
 * Check if query params have an id
 * If so, use that id
 * Else check logged in user id
 * otherwise redirect away
 */
$user_id = se($_GET, "id", get_user_id(), false);
error_log("user id $user_id");
$isMe = $user_id === get_user_id();
//!! makes the value into a true or false value regardless of the data https://stackoverflow.com/a/2127324
$edit = !!se($_GET, "edit", false, false); //if key is present allow edit, otherwise no edit
error_log(var_export($edit, true));
if ($user_id < 1) {
    flash("Invalid user", "danger");
    redirect("home.php");
}
?>
<?php
if (isset($_POST["save"]) && $isMe && $edit) {
    $db = getDB();
    $email = se($_POST, "email", null, false);
    $username = se($_POST, "username", null, false);
    $visibility = !!se($_POST, "visibility", false, false) ? 1 : 0;
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
        $params = [":email" => $email, ":username" => $username, ":id" => get_user_id(), ":vis" => $visibility];
        $db = getDB();
        $stmt = $db->prepare("UPDATE Users set email = :email, username = :username, visibility = :vis where id = :id");
        try {
            $stmt->execute($params);
        } catch (Exception $e) {
            users_check_duplicate($e->errorInfo);
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
$created = "";
$public = false;
//$user_id = get_user_id(); //this is retrieved above now
//TODO pull any other public info you want
$db = getDB();
$stmt = $db->prepare("SELECT username, created, visibility from Users where id = :id");
try {
    $stmt->execute([":id" => $user_id]);
    $r = $stmt->fetch(PDO::FETCH_ASSOC);
    error_log("user: " . var_export($r, true));
    $username = se($r, "username", "", false);
    $created = se($r, "created", "", false);
    $public = se($r, "visibility", 0, false) > 0;
    if (!$public && !$isMe) {
        flash("User's profile is private", "warning");
        redirect("home.php");
        //die(header("Location: home.php"));
    }
} catch (Exception $e) {
    echo "<pre>" . var_export($e->errorInfo, true) . "</pre>";
}
$word = $public ? " Private" : " Public";
?>

<?php
$email = get_user_email();
$username = get_username();
?>
<div class="container-fluid">
<h1>Profile</h1>
<form method="POST" onsubmit="return validate(this);">
    <div class="mb-3">
        <div class="form-check form-switch">
            <input name="visibility" class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" <?php if ($public) echo "checked"; ?>>
            <label class="form-check-label" for="flexSwitchCheckDefault">Make Profile<?php echo $word ?></label>
        </div>
    </div>
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