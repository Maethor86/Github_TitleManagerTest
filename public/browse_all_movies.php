<?php
require_once("../includes/filenames.php");
require_once($filename_standard_require_top);
include($filename_back_sidebar_left_include_top);

confirm_logged_in();
confirm_session();
?>

<?php
$page_title = "Browse Movies";
echo make_page_title($page_title);
echo session_message();
?>


<?php
$output  = "<ul class=\"users\">";
$output .= "<li class=\"users\">";
$output .= "<div><h3 class=\"users\">Title</h3></div>";
//$output .= "<div><h3 class=\"users\">Action</h3></div>";
$output .= "</li>";
$output .= make_browse_movies_list();
$output .= "</ul>";

echo $output;

?>
<a href="new_movie.php">Add new movie</a>
<?php

?>

<?php
require_once($filename_standard_require_bottom);
include($filename_standard_include_bottom);
?>
