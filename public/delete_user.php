<?php
require_once("../includes/filenames.php");
require_once($filename_standard_require_top);
include($filename_back_sidebar_left_include_top);

confirm_logged_in();
confirm_is_admin();
confirm_session();
?>

<?php
$page_title = "Delete User";
echo show_page_title($page_title);
echo session_message();
?>

<?php
$deleted_user = delete_user($_GET);
if ($deleted_user) {
  $_SESSION["message"] .= "User deleted.<br />";
}
else {
  $_SESSION["message"] .= "User not deleted.<br />";
}
redirect_to("manage_users.php?page=3");
?>

<?php
require_once($filename_standard_require_bottom);
include($filename_standard_include_bottom);
?>
