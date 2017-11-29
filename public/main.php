<?php
require_once("../includes/filenames.php");
require_once($filename_standard_require_top);
include($filename_standard_include_top);

confirm_logged_in();
confirm_session();
?>

<?php
$page_title = "Main";
echo show_page_title($page_title);
echo session_message();
?>

<?php
if ($current_subject) {
  if ($current_subject["MenuName"] == "Search Title") {
    redirect_to("search_title.php?subject=3");
  }
}
elseif ($current_page) {
  if ($current_page["MenuName"] == "Users") {
    redirect_to("manage_users.php?page=3");
  }
  if ($current_page["MenuName"] == "SQL Server") {
    redirect_to("about_sql.php?page=1");
  }
  if ($current_page["MenuName"] == "PHP") {
    redirect_to("about_php.php?page=2");
  }
  if ($current_page["MenuName"] == "OS & Browser") {
    redirect_to("about_OS_browser.php?page=4");
  }
}
else {
  echo "Welcome!";
}

?>


<?php
require_once($filename_standard_require_bottom);
include($filename_standard_include_bottom);
?>
