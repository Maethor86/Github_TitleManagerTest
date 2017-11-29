<?php
require_once("../includes/filenames.php");
require_once($filename_standard_require_top);
include($filename_standard_include_top);

confirm_logged_in();
confirm_session();
?>

<?php
$page_title = "OS & Browser";
echo show_page_title($page_title);
echo session_message();
?>

<?php
$user_agent = $_SERVER['HTTP_USER_AGENT'];
echo "<h4>Operating System:</h4>";
$OS = about_get_OS($user_agent);
echo $OS;
echo "<h4>Browser:</h4>";
$browser = about_get_browser($user_agent);
echo $browser;

//print_r($user_agent);
//echo "Visit <a href\"www.useragentstring.com\">useragentstring.com</a> to see what the above means.";


?>


<?php
require_once($filename_standard_require_bottom);
include($filename_standard_include_bottom);
?>
