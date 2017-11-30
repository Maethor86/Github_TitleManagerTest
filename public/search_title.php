<?php
require_once("../includes/filenames.php");
require_once($filename_standard_require_top);
include($filename_standard_include_top);

confirm_logged_in();
confirm_session();
?>

<?php
$page_title = "Search Title";
echo make_page_title($page_title);
// want to have seesion_message here like all the other files, but kinda cant right now
?>

<?php
// checks to see if form was submitted
if (isset($_POST['search_title'])) {
	// form was submitted
	$titlename = trim($_POST["title"]);

	// validation
	$fields_required = array("title");
	$errors = field_validation($_POST, $fields_required, $errors);

	if (empty($errors)) {
		// no errors, can proceed

		// look up title in DB
		$title = find_title_by_titlename($titlename);
		if ($title) {
			// title found
			$_SESSION["message"] .= "Title found!";
		}
		else {
			// title not found
			$_SESSION["message"] .= "Title not found.";
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

?>
<?php
require_once($filename_standard_require_bottom);
include($filename_standard_include_bottom);
?>
