<?php
require_once("../includes/filenames.php");
require_once("../includes/{$filename_functions}");
require_once("../includes/{$filename_sessions}");
?>

<?php
// reset session, and redirect to login page
$_SESSION["user_id"] = NULL;
$_SESSION["username"] = NULL;
$_SESSION["last_activity"] = NULL;
// $_SESSION["message"] = NULL;
// $_SESSION["error"] = NULL;
// $_SESSION = NULL;
// session_destroy();
redirect_to("login.php");
?>
