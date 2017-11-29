<?php
require_once("../includes/filenames.php");
require_once($filename_standard_require_top);
include($filename_standard_include_top);

confirm_logged_in();
confirm_is_admin();
confirm_session();
?>

<?php
$page_title = "Edit User";
echo show_page_title($page_title);
?>

<?php
$current_username = find_username_by_user_id($_GET["userID"]);
$new_username = $current_username;
if (isset($_POST["edit_user"])) {
  $new_username = $_POST["new_username"];
  if (!empty($_POST["new_password"]) || !empty($_POST["confirm_new_password"])) {
    $fields_required = array("new_username", "old_password", "new_password", "confirm_new_password");
    $errors = field_validation($_POST, $fields_required, $errors);
    if (!($_POST["new_password"] == $_POST["confirm_new_password"])) {
      $errors["new_password_not_confirmed"] = "New passwords don't match";
    }
  }
  if (isset($_POST["new_username"])) {
    $fields_required = array("new_username", "old_password");
    $errors = field_validation($_POST, $fields_required, $errors);
    if (!check_user_credentials($current_username, $_POST["old_password"])) {
      $errors["password_not_confirmed"] = "Incorrect password for user";
    }
  }

  if (empty($errors)) {
    $edited_user = edit_user($_POST);
    if ($edited_user) {
      $_SESSION["message"] .= "User edited.";
    }
    else {
      $_SESSION["message"] .= "User not edited.";
    }
    redirect_to("manage_users.php?page=3");
  }
}

?>

<?php
echo form_errors($errors);
echo session_message();
//$old_username = find_username_by_id($_GET["userID"]);
?>
<form action=<?php echo "edit_user.php?userID=" . $_GET["userID"] ?> method="post">
  <ul class="form">
    <li class="form">
      <div>Current username:</div><div><i><?php echo $current_username ?></i></div>
    </li>
    <li class="form">
      <div>New username:</div><div><input type="text" name="new_username" value=<?php echo $new_username ?> ></div>
    </li>
    <li class="form">
      <div>Old password:</div><div><input type="password" name="old_password" value="" ></div>
    </li>
    <li class="form">
      <div>New password:</div><div><input type="password" name="new_password" value="" ></div>
    </li>
    <li class="form">
      <div>Confirm new password:</div><div><input type="password" name="confirm_new_password" value="" ></div>
    </li>
  </ul>
	<input type="submit" name="edit_user" value="Edit User" /> <br /><br />
</form>
<a href="manage_users.php?page=3">Cancel</a>
<?php

?>


<?php
require_once($filename_standard_require_bottom);
include($filename_standard_include_bottom);
?>
