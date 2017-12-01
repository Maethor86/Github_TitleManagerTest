<?php
require_once("../includes/filenames.php");
require_once($filename_standard_require_top);
include($filename_standard_include_top);

confirm_logged_in();
confirm_session();
?>

<?php
$page_title = "Search Movies";
echo make_page_title($page_title);
// want to have seesion_message here like all the other files, but kinda cant right now
?>

<?php
$movies_found = FALSE;
// checks to see if form was submitted
if (isset($_POST['search_title'])) {
	// form was submitted
	$title = trim($_POST["title"]);

	// validation
	$fields_required = array("title");
	$errors = field_validation($_POST, $fields_required, $errors);

	if (empty($errors)) {
		// no errors, can proceed

		// look up title in DB
		$movie_set = find_movie_set_by_title($title);
		if ($movie_set) {
			// movies found
			$movies_found = TRUE;
		}
		else {
			// movies not found

		}
	}
}
else {
	// form was not submitted
	$title = "";
}
?>

<?php
echo session_message();
echo form_errors($errors);
if (isset($_POST["title"])) {
	$title = $_POST["title"];
}
else {
	$title = "";
}
echo make_searchtitle_form($title, $filename_search_title);
if ($movies_found) {
	echo "<br /><br /><br />";
	echo "<div class=\"users\"><h3>Search Results</h3></div>";
	echo make_movies_list($movie_set);
}
?>
<?php
require_once($filename_standard_require_bottom);
include($filename_standard_include_bottom);
?>
