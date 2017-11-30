<?php
require_once("../includes/filenames.php");
require_once($filename_standard_require_top);
include($filename_standard_include_top);

confirm_logged_in();
confirm_is_admin();
confirm_session();
?>

<?php
$page_title = "Template";
echo make_page_title($page_title);
echo session_message();
?>

<?php
if ($current_subject) {
  echo "Subject is " . $current_subject["MenuName"];
}
elseif ($current_page) {
  echo "Page is " . $current_page["MenuName"];
  $current_page["Contents"];
}
else {
  echo "Welcome!";
}
?>

<?php
require_once($filename_standard_require_bottom);
include($filename_standard_include_bottom);
?>
