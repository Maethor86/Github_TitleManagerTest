<?php
require_once("../includes/filenames.php");
require_once($filename_config);
require_once($filename_functions);
require_once($filename_sessions);
require_once($filename_sql_functions);
// require_once($filename_db_connection); may require?
?>

<?php
// update last_activity in sql server to reflect clicking on logout
update_user_log_in_database($_SESSION["login_id"]);

// reset session, and redirect to login page
$_SESSION["user_id"] = NULL;
$_SESSION["username"] = NULL;
$_SESSION["last_activity"] = NULL;
$_SESSION["login_id"] = NULL;
// $_SESSION["message"] = NULL;
// $_SESSION["error"] = NULL;
// $_SESSION = NULL;
// session_destroy();
redirect_to("login.php");
?>
