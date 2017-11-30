<?php
require_once("../includes/filenames.php");
require_once($filename_standard_require_top);
include($filename_standard_include_top);

confirm_logged_in();
confirm_is_admin();
confirm_session();
?>

<?php
$page_title = "PHP";
echo make_page_title($page_title);
echo session_message();
?>

<?php
phpinfo();
?>



<?php
require_once($filename_standard_require_bottom);
include($filename_standard_include_bottom);
?>
