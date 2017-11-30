<?php
require_once("../includes/filenames.php");
require_once($filename_standard_require_top);
include($filename_back_sidebar_left_include_top);

confirm_logged_in();
confirm_is_admin();
confirm_session();
?>

<?php
$page_title = "Edit User";
echo make_page_title($page_title);
?>

<?php
$current_username = find_username_by_user_id($_GET["userID"]);
$new_username = "";
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

<?php
echo make_edit_user_form($_GET["userID"], $current_username, $new_username);
?>


<?php
require_once($filename_standard_require_bottom);
include($filename_standard_include_bottom);
?>
