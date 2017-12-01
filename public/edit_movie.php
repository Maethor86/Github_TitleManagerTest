<?php
require_once("../includes/filenames.php");
require_once($filename_standard_require_top);
include($filename_back_sidebar_left_include_top);

confirm_logged_in();
confirm_session();
?>

<?php
$page_title = "Edit Movie";
echo make_page_title($page_title);
?>

<?php
$current_title = find_movie_title_by_movie_id($_GET["movieID"]);
$new_title = "";
if (isset($_POST["edit_movie"])) {
  $new_title = $_POST["new_title"];
  if (isset($_POST["new_title"])) {
    $fields_required = array("new_title");
    $errors = field_validation($_POST, $fields_required, $errors);
  }
  if (isset($_POST["new_imdbrating"])) {
    $fields_required = array("new_title");
    $errors = field_validation($_POST, $fields_required, $errors);
  }
  if (isset($_POST["new_runningtime"])) {
    $fields_required = array("new_title");
    $errors = field_validation($_POST, $fields_required, $errors);
  }

  if (empty($errors)) {
    $edited_movie = edit_movie($_POST);
    if ($edited_movie) {
      $_SESSION["message"] .= "Movie edited.";
    }
    else {
      $_SESSION["message"] .= "Movie not edited.";
    }
    redirect_to("browse_all_movies.php?subject=3");
  }
}

?>

<?php
echo form_errors($errors);
echo session_message();
?>

<?php
echo make_edit_movie_form($_GET["movieID"], $current_title, $new_title);
?>


<?php
require_once($filename_standard_require_bottom);
include($filename_standard_include_bottom);
?>
