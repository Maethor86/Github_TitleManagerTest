<?php
require_once("../includes/filenames.php");
require_once($filename_standard_require_top);
include($filename_standard_include_top);

confirm_logged_in();
confirm_is_admin();
confirm_session();
?>

<?php
$page_title = "Manage Users";
echo show_page_title($page_title);
echo session_message();
?>


<?php
$output  = "<ul class=\"users\">";
$output .= "<li class=\"users\">";
$output .= "<div><h3 class=\"users\">Username</h3></div>";
$output .= "<div><h3 class=\"users\">Action</h3></div>";
$output .= "</li>";
$output .= "<li class=\"page_header\">";
$output .= "<div><h4 class=\"users\">Admins</h4></div>";
$output .= "</li>";
$output .= make_manage_user_list_of_userrole(1);
$output .= "<li class=\"users\">";
$output .= "<div><h4 class=\"users\">Users</h4></div>";
$output .= "</li>";
$output .= make_manage_user_list_of_userrole(2);
$output .= make_manage_user_list();
$output .= "</ul>";
// $output .= make_manage_user_list();

echo $output;
//echo make_user_list_of_userrole(1);
//echo make_user_list_of_userrole(2);
?>
<a href="new_user.php?page=3">Add new user</a>
<?php

?>

<?php
require_once($filename_standard_require_bottom);
include($filename_standard_include_bottom);
?>
