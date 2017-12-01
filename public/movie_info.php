<?php
require_once("../includes/filenames.php");
require_once($filename_standard_require_top);
include($filename_back_sidebar_left_include_top);

confirm_logged_in();
confirm_session();
?>

<?php
$page_title = "Movie Info";
echo make_page_title($page_title);
echo session_message();
?>

<?php
$movie = find_movie_by_movie_id($_GET["movieID"]);
?>

<?php
echo make_movie_info($movie);
?>

<?php
require_once($filename_standard_require_bottom);
include($filename_standard_include_bottom);
?>
