<?php
require_once("../includes/filenames.php");
require_once($filename_standard_require_top);
include($filename_standard_include_top);

confirm_logged_in();
confirm_is_admin();
?>

<?php
$page_title = "New User";
echo show_page_title($page_title);
echo session_message();
?>

<?php
$username = "";
if (isset($_POST["create_user"])) {

  $fields_required = array("username", "password", "confirm_password");
  $errors = field_validation($_POST, $fields_required, $errors);
  if (!($_POST["password"] == $_POST["confirm_password"])) {
    $errors["password_not_confirmed"] = "Passwords don't match";
  }
  if (empty($errors)) {
    $created_user = create_user($_POST);
    if ($created_user) {
      $_SESSION["message"] .= "User created.";
    }
    else {
      $_SESSION["message"] .= "User not created.";
    }
    redirect_to("manage_users.php?page=3");
  }
  $username = $_POST["username"];
}
?>

<?php echo form_errors($errors) ?>
<form action="new_user.php" method="post">
  <ul class="form">
    <li class="form">
      <div>Username:</div><div><input type="text" name="username" value=<?php echo $username ?> ></div>
    </li>
    <li class="form">
      <div>Password:</div><div><input type="password" name="password" value="" ></div>
    </li>
    <li class="form">
      <div>Confirm password:</div><div><input type="password" name="confirm_password" value="" ></div>
    </li>
  </ul>
	<input type="submit" name="create_user" value="Create User" /> <br /><br />
</form>
<a href="manage_users.php?page=3">Cancel</a>
<?php

?>


<?php
require_once($filename_standard_require_bottom);
include($filename_standard_include_bottom);
?>
