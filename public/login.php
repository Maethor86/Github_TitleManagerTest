<?php
require_once("../includes/filenames.php");
require_once($filename_login_require_top);
include($filename_login_include_top);
?>

<?php
$page_title = "Log in";
echo make_page_title($page_title);
echo session_message();
?>

<?php
if (logged_in()) {
  redirect_to($filename_main);
}

$username = "";

if (isset($_POST['login'])) {

  // form was submitted
  $username = $_POST["username"];;
  // validate input
  $fields_required = array("username", "password");
  $errors = field_validation($_POST, $fields_required, $errors);
  if (empty($errors)) {
    // can attempt login
    $user = attempt_login($_POST["username"],$_POST["password"]);
    if ($user) {

      // login successful
      $_SESSION["user_id"] = $user["UserID"];
      $_SESSION["username"] = $user["Username"];

      redirect_to("{$filename_search_title}?subject=3");
    }
    else {
      // login failed
      $errors["login"] = "Username/password incorrect";
    }
  }
  else {
    // found erorrs, cannot attempt login

  }
}
else {
  // form was not submitted
  $username = "";
  $password = "";
}
?>

<?php echo form_errors($errors) ?>

<?php
echo make_login_form($username, $filename_login);
?>



<?php
require_once($filename_login_require_bottom);
include($filename_login_include_bottom);
?>
