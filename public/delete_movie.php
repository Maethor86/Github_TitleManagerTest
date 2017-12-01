<?php
require_once("../includes/filenames.php");
require_once($filename_standard_require_top);
include($filename_back_sidebar_left_include_top);

confirm_logged_in();
confirm_session();
?>

<?php
$page_title = "Delete Movie";
echo make_page_title($page_title);
echo session_message();
?>

<?php
$deleted_movie = delete_movie($_GET);
if ($deleted_movie) {
  $_SESSION["message"] .= "Movie deleted.<br />";
}
else {
  $_SESSION["message"] .= "Movie not deleted.<br />";
}
redirect_to("browse_all_movies.php?subject=3");
?>

<?php
require_once($filename_standard_require_bottom);
include($filename_standard_include_bottom);
?>
