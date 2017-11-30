<?php

session_save_path("../../../Sessions");
$session = session_start();
//initialize_session();

function session_error() {
  if (!empty($_SESSION["error"])) {
    $output  = "<div class=\"error\">";
    $output .= $_SESSION["error"];
    $output .= "</div>";

    $_SESSION["error"] = "";
    return $output;
  }
}

function session_message() {
  echo session_error();
  if (!empty($_SESSION["message"])) {
    $output  = "<div class=\"message\">";
    $output .= $_SESSION["message"];
    $output .= "</div>";

    $_SESSION["message"] = "";
    return $output;
  }
}

function confirm_session() {
  $valid_session = check_session_validity();
  if ($valid_session) {
    // session is valid
    $updated_session = update_session_activity();
    if ($updated_session) {
      // session last_activity updated successfully
    }
    else {
      // something went wrong updating the session
      log_out();
    }
  }
  else {
    // session is not valid
    log_out();
  }
}

function check_session_validity() {
  $max_inactivity_time_seconds = 30*60;

  if (!isset($_SESSION["last_activity"])) {
    $_SESSION["message"] .= "Warning: Session last activity not set when it should have been set.";
    return FALSE;
  }
  elseif (isset($_SESSION["last_activity"]) && ((strtotime(substr(generate_datetime_for_sql(),0,19)) - strtotime(substr($_SESSION["last_activity"],0,19))) > $max_inactivity_time_seconds)) {

    $_SESSION["message"] .= "Session timed out.";
    $_SESSION["last_activity"] = NULL;
    return FALSE;
  }
  else {
    // session is valid
    return TRUE;
  }
}

function update_session_activity() {
  if (isset($_SESSION["last_activity"])) {
    $_SESSION["last_activity"] = generate_datetime_for_sql();

    if (isset($_SESSION["login_id"])) {
      // $_SESSION["login_id"] is set, this should be set whenever $_SESSION["user_id"] is set
      $login_id = $_SESSION["login_id"];
      update_user_log_in_database($login_id);
      return TRUE;
    }
    else {
      $_SESSION["message"] = "Warning: User will not be logged in the database.";
      return FALSE;
    }
  }
  else {
    // couldn't update session last_activity, it wasn't set
    $_SESSION["message"] = "Warning: Session last activity not set, cannot update last activity.";
    return FALSE;
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
  $_SESSION["error"]
  $_SESSION["message"]
  $_SESSION["last_activity"]
  $_SESSION["login_id"]
*/
?>
