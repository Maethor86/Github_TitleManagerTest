<?php
require_once("../includes/filenames.php");
require_once($filename_standard_require_top);
include($filename_standard_include_top);

confirm_logged_in();
confirm_is_admin();
?>

<?php
$page_title = "Manage Users";
echo show_page_title($page_title);
echo session_message();
?>

<h5>Admins</h5>
<?php
echo make_user_list_of_userrole(1);
?>
<h4>Users</h4>
<?php
echo make_user_list_of_userrole(2);
?>

<?php
echo make_userlist();
echo make_user_list_of_userrole(1);
echo make_user_list_of_userrole(2);
?>
<a href="new_user.php?page=3">Add new user</a>
<?php

?>

<?php
require_once($filename_standard_require_bottom);
include($filename_standard_include_bottom);
?>
