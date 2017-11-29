<?php
require_once("../includes/filenames.php");
require_once($filename_standard_require_top);
include($filename_standard_include_top);

confirm_logged_in();
confirm_is_admin();
confirm_session();
?>

<?php
$page_title = "SQL Server";
echo show_page_title($page_title);
echo session_message();
?>

<h4>SQL Server User and Database:</h4>
<?php
sql_show_current_user();
sql_show_current_database();
?>

<h4>SQL Server Version:</h4>

<?php
sql_show_sqlversion();
?>


<?php
require_once($filename_standard_require_bottom);
include($filename_standard_include_bottom);
?>
