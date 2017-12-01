<?php
require_once("../includes/filenames.php");
require_once($filename_standard_require_top);
include($filename_back_sidebar_left_include_top);

confirm_logged_in();
confirm_session();
?>

<?php
$page_title = "New Movie";
echo make_page_title($page_title);
echo session_message();
?>

<?php
$title = "";
if (isset($_POST["create_movie"])) {

  $fields_required = array("title");
  $errors = field_validation($_POST, $fields_required, $errors);
  if (empty($errors)) {
    $created_movie = create_movie($_POST);
    if ($created_movie) {
      $_SESSION["message"] .= "Movie created.<br />";
    }
    else {
      $_SESSION["message"] .= "Movie not created.<br />";
    }
    redirect_to("browse_all_movies.php?subject=3");
  }
  $title = $_POST["title"];
}
?>

<?php
echo form_errors($errors);
echo make_new_title_form($title);
?>


<?php
require_once($filename_standard_require_bottom);
include($filename_standard_include_bottom);
?>
