<?php
	require_once("../includes/functions.php");
 	require_once("../includes/validation_functions.php");

	$errors = array();
	$message = "";

	// checks to see if form was submitted
	// if form was submitted, then want to validate input,
	// and look up in DB
	if (isset($_POST['submit'])) {
		// form was submitted
		// $title = trim($_POST["title_text"])

		// validation
		$fields_required = array("title");
		foreach ($fields_required as $field) {
			$value = trim($_POST[$field]);
			if (!has_presence($value)) {
				$errors[$field] =  ucfirst($field) . " can't be blank";
			}

		}


		if (empty($errors)) {
			// no errors, can proceed

			// look up title in DB

			// for now just do a redirect to temp.php
			redirect_to("temp.php");
		}
	}

	// form was not submitted
	else {
		$title = "default";
		$message = "Please enter title.";
	}
?>

<?php include("../includes/layouts/header.php"); ?>

<!-- display welcome message, and/or error messages -->
<?php echo $message ?><br />
<?php echo form_errors($errors) ?>

<!-- name of php-file with form -->
<?php $form_name = "TitleManagerTest01.php"; ?>

<!-- a form consisting of
	text input field, with text to the left: "Title:"
	a button, "Submit" -->
<form action=<?php echo $form_name ?> method="post">
	Title: <input type="text" name="title" value=<?php echo $title ?> > <br />
	<input type="submit" name="submit" value="Submit" />
</form>


<?php include("../includes/layouts/footer.php"); ?>
