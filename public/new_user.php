<?php
require_once("../includes/filenames.php");
require_once($filename_standard_require_top);
include($filename_back_sidebar_left_include_top);

confirm_logged_in();
confirm_is_admin();
confirm_session();
?>

<?php
$page_title = "New User";
echo make_page_title($page_title);
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

<?php
echo form_errors($errors);
echo make_new_user_form($username);
?>


<?php
require_once($filename_standard_require_bottom);
include($filename_standard_include_bottom);
?>
