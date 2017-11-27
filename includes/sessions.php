<?php

session_save_path("../../../Sessions");
$session = session_start();
//initialize_session();


function session_message() {
  if (!empty($_SESSION["message"])) {
    $output  = "<div class=\"message\">";
    $output .= $_SESSION["message"];
    $output .= "</div>";

    $_SESSION["message"] = "";
    return $output;
  }
}

function check_session() {
  $max_inactivity_time_seconds = 60*30;
  if (isset($_SESSION["last_activity"]) && ((time() - $_SESSION["last_activity"]) > $max_inactivity_time_seconds)) {
    $_SESSION["last_activity"] = NULL;
    $_SESSION["message"] .= "Session timed out.";
    return FALSE;
  }
  else {
    $_SESSION["last_activity"] = time();
    return TRUE;
  }
}
/*
function initialize_session() {
  $_SESSION["message"] = "";
}
*/
/*
naming conventions for session entities:
all lowercase
  things stored in the sessions now
  $_SESSION["user_id"]
  $_SESSION["username"]
  $_SESSION["error"]?
  $_SESSION["message"]
  $_SESSION["last_activity"]
  $_SESSION["login_id"]
*/
?>
