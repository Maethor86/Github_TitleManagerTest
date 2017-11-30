<?php
require_once("../includes/filenames.php");
require_once($filename_standard_require_top);
include($filename_standard_include_top);

confirm_logged_in();
confirm_session();
?>

<?php
$page_title = "User Info";
echo make_page_title($page_title);
echo session_message();
?>

<?php
$user = find_current_user();
$user_level = find_userrole_name_by_userrole_id($user["UserRoleID"]);
$created_datetime = date_format($user["DateTimeCreated"], 'd-m-Y, H:i:s');
?>

<?php
echo make_current_user_form($user["Username"], $user_level, $created_datetime);
?>

<?php
require_once($filename_standard_require_bottom);
include($filename_standard_include_bottom);
?>
