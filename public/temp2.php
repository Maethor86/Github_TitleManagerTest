<?php
require_once("../includes/functions.php");
require_once("../includes/validation_functions.php");
require_once("../includes/sql_functions.php");
require_once("../includes/sessions.php");
require_once("../includes/db_connection.php");
?>

<?php
if (isset($_GET["subject"])) {
  $selected_subject_id = $_GET["subject"];
  $selected_page_id = null;
}
elseif (isset($_GET["page"])) {
  $selected_subject_id = null;
  $selected_page_id = $_GET["page"];
}
?>


<?php include("../includes/layouts/header.php"); ?>
<div id="main">
  <div id="navigation">
    <?php echo navigation(1,1); ?>
  </div>
  <div id="page">

    <h1>
      Temp
    </h1>

    <p>
    <?php
    if (isset($selected_subject_id)) {
      echo "Subject is " . $selected_subject_id;
    }
    if (isset($selected_page_id)) {
      echo "Page is " . $selected_page_id;
    }

     ?>
    This is a temporary site.This is a temporary site.
    This is a temporary site.This is a temporary site.
    This is a temporary site.This is a temporary site.
    This is a temporary site.This is a temporary site.
    This is a temporary site.This is a temporary site.
    This is a temporary site.This is a temporary site.
    This is a temporary site.This is a temporary site.
    This is a temporary site.This is a temporary site.
    This is a temporary site.This is a temporary site.
    This is a temporary site.This is a temporary site.
    This is a temporary site.This is a temporary site.
    This is a temporary site.This is a temporary site.
    This is a temporary site.This is a temporary site.
    This is a temporary site.This is a temporary site.
    This is a temporary site.This is a temporary site.
    This is a temporary site.This is a temporary site.
    This is a temporary site.This is a temporary site.
    This is a temporary site.This is a temporary site.
    This is a temporary site.This is a temporary site.
    This is a temporary site.This is a temporary site.
    This is a temporary site.This is a temporary site.
    This is a temporary site.This is a temporary site.
    This is a temporary site.This is a temporary site.
    This is a temporary site.This is a temporary site.
    This is a temporary site.This is a temporary site.
    This is a temporary site.This is a temporary site.
    </p>

  </div>
</div>


<?php include("../includes/layouts/footer.php"); ?>
