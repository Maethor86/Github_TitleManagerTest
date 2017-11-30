<?php

// redirect to a specified page
function redirect_to($new_location) {
  header("Location: " . $new_location);
  exit;
}

// exception funtions
function make_exception_message_to_user($e) {
  $output .= "<h3>Oops, something went wrong ...</h3>";
  $output .= "Contact admin and provide the following code: " . $e->getCode() . ". <br />";
  return $output;
}

// navigation functions
function make_sidebar_left($subject_array, $page_array) {
  global $filename_main;

  $subject_set = find_all_subjects();
  $output =  "<ul class=\"subjects\">";
  while ($subject = sqlsrv_fetch_array($subject_set, SQLSRV_FETCH_ASSOC)) {
    if ($subject["Visible"] == TRUE || is_admin()) {
      $output .= "<li ";
      if ($subject_array && $subject["SubjectID"] == $subject_array["SubjectID"]) {
        $output .= "class= \"selected\" ";
      }
      $output .= ">";
      $output .= "<a href={$filename_main}?subject=";
      $output .= $subject["SubjectID"];
      $output .= " \" >";
      $output .= $subject["MenuName"];
      $output .= " </a>";
    }

    $page_set = find_pages_for_subject($subject["SubjectID"]);
    $output .=  "<ul class=\"pages\">";
    while ($page = sqlsrv_fetch_array($page_set, SQLSRV_FETCH_ASSOC)) {
      if ($page["Visible"] == TRUE || is_admin()) {
        $output .= "<li ";
        if ($page_array && $page["PageID"] == $page_array["PageID"]) {
          $output .= "class= \"selected\"";
        }
        $output .= ">";
        $output .= "<a href={$filename_main}?page=";
        $output .= $page["PageID"];
        $output .= " \" >";
        $output .= $page["MenuName"];
        $output .= " </a></li>";
      }
    }
    $output .= "</ul></li>";
  }
  $output .= " </ul>";
  return $output;

}

function make_sidebar_left_back($subject_array, $page_array) {
  global $filename_main;

  $output  = "<ul class=\"subjects\">";
  $output .= "<li>";
  $output .= "<a href={$filename_main}?subject=3>";
  $output .= "<<< Main menu";
  $output .= "</a>";
  $output .= "</li>";
  $output .= "</ul>";
  return $output;
}

function make_sidebar_right($username) {
  $output  = "Currently logged in as: ";
  $output .= "<a href=\"current_user.php\">" . $username . "</a>";
  if (logged_in()) {
      $output .= "<hr /><a href=\"logout.php\">Log out</a>";
  }
  return $output;
}

function find_all_subjects() {
  // returns array with subjects if successful

  // want to use prepared statements
  $query  = "SELECT * FROM Web_Subjects";
  $params = array();

  $subject_set = sql_request_query($query, $params);
  return $subject_set;

}

function find_subject_by_subject_id($subject_id) {
  // returns array with the subject for the given subject id, else returns false


  $query  = "SELECT TOP 1 * FROM Web_Subjects";
  $query .= " WHERE SubjectID = ?";

  $params = array($subject_id);

  // want to use prepared statements
  $subject_set = sql_request_query($query, $params);
  if ($subject = sqlsrv_fetch_array($subject_set, SQLSRV_FETCH_ASSOC)) {
    return $subject;
  }
  else {
    return FALSE;
  }

}

function find_page_by_page_id($page_id) {
  // returns array with the subject for the given subject id, else returns false

  $query  = "SELECT TOP 1 * FROM Web_Pages";
  $query .= " WHERE PageID = ?";

  $params = array($page_id);

  $page_set = sql_request_query($query, $params);
  if ($page = sqlsrv_fetch_array($page_set, SQLSRV_FETCH_ASSOC)) {
    return $page;
  }
  else {
    return FALSE;
  }

}

function find_pages_for_subject($subject_id) {
  // returns array with pages for the given subject

  $query  = "SELECT * FROM Web_Pages";
  $query .= " WHERE SubjectID = ?";

  $params = array($subject_id);

  $page_set = sql_request_query($query, $params);
  return $page_set;

}

function find_current_page() {

  global $current_subject;
  global $current_page;

  if (isset($_GET["subject"])) {
    $current_subject = find_subject_by_subject_id($_GET["subject"]);
    $current_page = null;
  }
  elseif (isset($_GET["page"])) {
    $current_subject = null;
    $current_page = find_page_by_page_id($_GET["page"]);
  }
  else {
    $current_subject = null;
    $current_page = null;
  }
}

// display functions
function make_page_title($page_title="Default") {
  $output = "<h2 class=\"page_header\">{$page_title}</h2>";
  return $output;
}

function make_login_form($username = "", $login_page = "login.php") {

  $output  = "<form action= $login_page method=\"post\">";
  $output .= "<ul class=\"form\">";
  $output .= "Please enter username and password.";
  $output .= "<li class=\"form\">";
  $output .= "<div>Username:</div> <div><input type=\"text\" name=\"username\" value=$username ></div></li>";
  $output .= "<li class=\"form\">";
  $output .= "<div>Password:</div> <div><input type=\"password\" name=\"password\" value=\"\" ></div></li>";
  $output .= "<li class=\"form\">";
  $output .= "<div><input type=\"submit\" name=\"login\" value=\"Log in\" /></div></li>";
  $output .= "</ul></form>";

  return $output;
}

function make_new_user_form($username) {

  $output  = "<form action=\"new_user.php\" method=\"post\">";
  $output .= "<ul class=\"form\">";
  $output .= "<li class=\"form\">";
  $output .= "<div>Username:</div><div><input type=\"text\" name=\"username\" value=$username></div>";
  $output .= "</li>";
  $output .= "<li class=\"form\">";
  $output .= "<div>Password:</div><div><input type=\"password\" name=\"password\" value=\"\" ></div>";
  $output .= "</li>";
  $output .= "<li class=\"form\">";
  $output .= "<div>Confirm password:</div><div><input type=\"password\" name=\"confirm_password\" value=\"\" ></div>";
  $output .= "</li>";
  $output .= "</ul>";
  $output .= "<input type=\"submit\" name=\"create_user\" value=\"Create User\" /> <br /><br />";
  $output .= "</form>";
  $output .= "<a href=\"manage_users.php?page=3\">Cancel</a>";
  return $output;

}

function make_searchtitle_form($title = "", $searchtitle_page = "search_title.php") {
  // magic constant, needs refactoring
  $searchtitle_page .= "?subject=3";
  $output  = "<form action= $searchtitle_page method=\"post\">";
  $output .= "Title: <input type=\"text\" name=\"title\" value= $title >";
  $output .= "<input type=\"submit\" name=\"search_title\" value=\"Search\" />";
  $output .= "</form>";

  return $output;
}

function make_current_user_form($username, $user_level, $created_datetime) {

  $output  = "<ul class=\"form\">";
  $output .= "<li class=\"form\">";
  $output .= "<div>Username:</div><div><i>$username</i></div>";
  $output .= "</li>";
  $output .= "<li class=\"form\">";
  $output .= "<div>User level:</div><div><i>$user_level</i></div>";
  $output .= "</li>";
  $output .= "<li class=\"form\">";
  $output .= "<div>User created:</div><div><i>$created_datetime</i></div>";
  $output .= "</li>";
  $output .= "</ul>";

  return $output;

}

function make_edit_user_form($userid, $current_username, $new_username) {

  $output  = "<form action= \"edit_user.php?userID=$userid method=\"post\">";
  $output .= "<ul class=\"form\">";
  $output .= "<li class=\"form\">";
  $output .= "<div>Current username:</div><div><i>$current_username</i></div>";
  $output .= "</li>";
  $output .= "<li class=\"form\">";
  $output .= "<div>New username:</div><div><input type=\"text\" name=\"new_username\" value=$new_username></div>";
  $output .= "</li>";
  $output .= "<li class=\"form\">";
  $output .= "<div>Old password:</div><div><input type=\"password\" name=\"old_password\" value=\"\" ></div>";
  $output .= "</li>";
  $output .= "<li class=\"form\">";
  $output .= "<div>New password:</div><div><input type=\"password\" name=\"new_password\" value=\"\" ></div>";
  $output .= "</li>";
  $output .= "<li class=\"form\">";
  $output .= "<div>Confirm new password:</div><div><input type=\"password\" name=\"confirm_new_password\" value=\"\" ></div>";
  $output .= "</li>";
  $output .= "</ul>";
  $output .= "<input type=\"submit\" name=\"edit_user\" value=\"Edit User\" /> <br /><br />";
  $output .= "</form>";
  $output .= "<a href=\"manage_users.php?page=3\">Cancel</a>";

  return $output;
}

// user functions
function find_all_users() {
  // returns array with users if successful

  // want to use prepared statements
  $query  = "SELECT * FROM Web_Users";

  $params = array();

  $user_set = sql_request_query($query, $params);
  return $user_set;
}

function make_manage_user_line($user, $userrole) {

  $output  = "<li class=\"users\">";
  $output .= "<div";
  if ($user["UserRoleID"] == 1) {
    $output .= " class=\"admins\"";
  }
  $output .= ">";
  $output .= $user["Username"];
  //if ($user["UserRoleID"] == 1) {
  //
  //}
  $output .= "</div><div";
  if ($user["UserRoleID"] == 1) {
    $output .= " class=\"admins\">admin";
  }
  else {
    $output .= "><a href=\"edit_user.php?userID=";
    $output .= $user["UserID"];
    $output .= " \" >";
    $output .= "Edit";
    $output .= "</a> ";
    $output .= "<a href=\"delete_user.php?userID=";
    $output .= $user["UserID"];
    $output .= " \" >";
    $output .= "Delete";
    $output .= " </a>";
  }
  $output .= "</div></li>";
  return $output;
}

function make_manage_user_list() {

  $user_set = find_all_users();
  $output  = "<ul class=\"users\">";
  // $output .= "<li class=\"users\">";
  while ($user = sqlsrv_fetch_array($user_set, SQLSRV_FETCH_ASSOC)) {
    $output .= "<li class=\"users\">";
    $output .= "<div";
    if ($user["UserRoleID"] == 1) {
      $output .= " class=\"admins\"";
    }

    $output .= ">";
    $output .= $user["Username"];
    if ($user["UserRoleID"] == 1) {

    }
    $output .= "</div><div";
    if ($user["UserRoleID"] == 1) {
      $output .= " class=\"admins\"> admin";
    }
    else {
      $output .= "><a href=\"edit_user.php?userID=";
      $output .= $user["UserID"];
      $output .= " \" >";
      $output .= "Edit";
      $output .= "</a> ";
      $output .= "<a href=\"delete_user.php?userID=";
      $output .= $user["UserID"];
      $output .= " \" >";
      $output .= "Delete";
      $output .= " </a>";
    }
    $output .= "</div></li>";
  }
  $output .= " </ul>";
  return $output;

}

function make_manage_user_list_of_userrole($userrole) {

  $user_set = find_users_of_userrole($userrole);
  $output  = "<ul class=\"users\">";
  while ($user = sqlsrv_fetch_array($user_set, SQLSRV_FETCH_ASSOC)) {
    $output .= make_manage_user_line($user, $userrole);
  }
  $output .= " </ul>";
  return $output;

}

function make_user_list_of_userrole($userrole) {
  $user_set = find_users_of_userrole($userrole);
  $output  = "<ul class=\"users\">";
  $output .= "<li class=\"users\">";
  while ($user = sqlsrv_fetch_array($user_set, SQLSRV_FETCH_ASSOC)) {
    $output .= "<li class=\"users\">";
    $output .= $user["Username"];
    $output .= "</li>";
  }
  $output .= " </ul>";
  return $output;
}

  // user CRUD functions
function create_user($post, $user_role_id = 2) {
  // must remember to check if username is unique

  $username = $post["username"];
  $password = $post["password"];
  $date_time_created = generate_datetime_for_sql();

  $safe_username = sql_stringprep($username);
  $hashed_password = hash_password($password);

  $query  = "INSERT INTO Web_Users (Username, HashedPassword, UserRoleID, DateTimeCreated)";
  $query .= " VALUES (?, ?, ?, ?)";

  $params = array($safe_username, $hashed_password, $user_role_id, $date_time_created);

  try {
    $created_user = sql_request_query($query, $params);
  }
  catch (exception $e) {
    sql_log_errors($e, sqlsrv_errors());
    $_SESSION["error"] .= make_exception_message_to_user($e);
    /*
    if ($e->getCode() === EXCEPTION_CODE_SQL) {
      $_SESSION["message"] .= "Something went wrong trying to write to database. <br />";
      $_SESSION["message"] .= "Contact admin and provide the following code: " . $e->getCode() . ". <br />";
      //$e->getMessage();

    }
    else {
      $_SESSION["message"] .= $e->getMessage();
    }
    */
  }
  return $created_user;

}

function edit_user($post) {
  $user_id = $_GET["userID"];
  $user = find_user_by_user_id($user_id);
  if ($user) {
    if ($user["UserRoleID"] == 1) {
      $_SESSION["message"] .= "Can't edit admin.<br />";
      return FALSE;
    }
    if ($user_id == $_SESSION["user_id"]) {
      $_SESSION["message"] .= "(refactor) Can't edit the user that is logged in.<br />";
      return FALSE;
    }
    else {
      $safe_user_id = sql_stringprep($user_id);
      $safe_username = sql_stringprep($post["new_username"]);

      $query  = "UPDATE Web_Users";
      $query .= " SET Username = ?";

      $params = array($safe_username);

      if (!empty($post["new_password"])) {
        // need this safe_password?
        $safe_password = sql_stringprep($post["new_password"]);
        $hashed_password = hash_password($safe_password);
        $query .= " , HashedPassword = ?";

        $params = array_push($hashed_password);
      }
      $query .= " WHERE UserID = ?";

      $params = array_push($safe_user_id);
      $edited_user = sql_request_query($query, $params);
      return $edited_user;
    }
  }
  else {
    // i dont think this can ever happen
    $_SESSION["message"] .= "Couldn't find user.<br />";
    return FALSE;
  }
}

function delete_user($get) {

  $user_id = $get["userID"];
  $user = find_user_by_user_id($user_id);
  if ($user) {
    if ($user["UserRoleID"] == 1) {
      $_SESSION["message"] .= "Can't delete admin.<br />";
      return FALSE;
    }
    elseif ($user_id == $_SESSION["user_id"]) {
      $_SESSION["message"] .= "Can't delete the user that is logged in.<br />";
      return FALSE;
    }
    else {
      $safe_user_id = sql_stringprep($user_id);

      $query  = "DELETE FROM Web_Users";
      $query .= " WHERE UserID = ?";

      $params = array($safe_user_id);

      $deleted_user = sql_request_query($query, $params);
      return $deleted_user;
    }
  }
  else {
    // i dont think this can ever happen
    $_SESSION["message"] .= "Couldn't find user.<br />";
    return FALSE;
  }
}

  // user find functions
function find_user_by_user_id($user_id) {
  // returns array with the user for the given user id, else returns false

  $query  = "SELECT TOP 1 * FROM Web_Users";
  $query .= " WHERE UserID = ?";

  $params = array($user_id);

  $user_set = sql_request_query($query, $params);
  if ($user = sqlsrv_fetch_array($user_set, SQLSRV_FETCH_ASSOC)) {
    return $user;
  }
  else {
    return FALSE;
  }
}

function find_user_by_username($username)  {
  // need refactor, shouldnt find user based on username!
  // returns user if successful, or FALSE if unsuccessful

  $query  = "SELECT TOP 1 * FROM Web_Users";
  $query .= " WHERE Username = ?";

  $params = array($username);

  $user_set = sql_request_query($query, $params);

  if ($user = sqlsrv_fetch_array($user_set, SQLSRV_FETCH_ASSOC)) {
    return $user;
  }
  else {
    return FALSE;
  }
  /*
  $user = array();
  while ($row = sqlsrv_fetch_array($query_result, SQLSRV_FETCH_ASSOC)) {
    $user = array(
              'Username'          => $row['Username'],
              // 'HashedPassword'    => $row['HashedPassword'],
              );
  }
  */

}

function find_users_of_userrole($userrole) {
  // returns array with users if successful

  // want to use prepared statements
  $query  = "SELECT * FROM Web_Users";
  $query .= " WHERE UserRoleID = ?";

  $params = array($userrole);

  $user_set = sql_request_query($query, $params);
  return $user_set;
}

function find_username_by_user_id($user_id) {
  $user = find_user_by_user_id($user_id);
  return $user["Username"];
}

function find_userrole_id_by_user_id($user_id) {
  $user = find_user_by_user_id($user_id);
  return $user["UserRoleID"];
}

function find_userrole_by_userrole_id($userrole_id) {
  // returns array with the userrole for the given userrole id, else returns false
  $query  = "SELECT TOP 1 * FROM Web_UserRoles";
  $query .= " WHERE UserRoleID = ?";

  $params = array($userrole_id);

  $userrole_set = sql_request_query($query, $params);
  if ($userrole = sqlsrv_fetch_array($userrole_set, SQLSRV_FETCH_ASSOC)) {
    return $userrole;
  }
  else {
    return FALSE;
  }
}

function find_userrole_name_by_userrole_id($userrole_id) {
  // returns string with the userrole name for the given userrole id
  $userrole = find_userrole_by_userrole_id($userrole_id);
  return $userrole["UserRoleName"];
}

function find_current_user() {
  $current_user_id = find_user_id_of_current_user();
  $current_user = find_user_by_user_id($current_user_id);
  return $current_user;
}

function find_user_id_of_current_user() {
  if (isset($_SESSION["user_id"])) {
    $user_id = $_SESSION["user_id"];
    return $user_id;
  }
  else {
    return FALSE;
  }
}

function find_username_of_current_user() {
  if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
    return $username;
  }
  else {
    return FALSE;
  }
}

function find_userrole_id_of_current_user() {
  if (isset($_SESSION["user_id"])) {
    $userrole_id = find_userrole_id_by_user_id($_SESSION["user_id"]);
    return $userrole_id;
  }
  else {
    return FALSE;
  }
}

function check_user_credentials($username, $password) {
  return attempt_login($username, $password);
}

// login functions
function attempt_login($username, $password) {
  // returns user if successful, or FALSE if unsuccessful

  if (!isset($username)) {
    return FALSE;
  }
  $user = find_user_by_username($username);
  if ($user) {
    // found user, now check password
    if (password_verify($password, $user['HashedPassword'])) {
      //password mathces
      log_user_in_database($user);
      return $user;
    }
  }
  else {
    // user not found
    return FALSE;
  }
}

function generate_datetime_for_sql() {

  $fractional_second_precision = SQL_DATETIME_FRAC_SEC_PRECISIONz;
  $time = microtime(TRUE);
  $milli_secs =  $time - floor($time);
  $milli_secs =  substr($milli_secs, 1, $fractional_second_precision + 1);
  $datetime_format = "Y-m-d H:i:s";

  $datetime =  date($datetime_format) . $milli_secs;
  return $datetime;
}

function logged_in() {
  return isset($_SESSION["user_id"]);
}

function confirm_logged_in() {
  if (!logged_in()) {
    log_out();
  }
  else {
  }

}

function is_admin() {
  $admin = 1;
  if (logged_in()) {
    if (find_userrole_id_of_current_user() == $admin) {
      return TRUE;
    }
    else {
      return FALSE;
    }
  }
  else {
    return FALSE;
  }
}

function confirm_is_admin() {
  if (!is_admin()) {
    $_SESSION["message"] .= "You're not an admin.";
    // magic constant, need refactoring
    $nonadmin_redirect = "search_title.php?subject=3";
    redirect_to($nonadmin_redirect);
    // return FALSE;
  }
  else {
    // redirect_to("main.php");
    return TRUE;
  }
}

function log_out() {
  redirect_to("logout.php");
}

function hash_password($password) {
    $hashed_password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
    return $hashed_password;
}

// title CRUD
function find_title_by_titlename($titlename) {
  return FALSE;
}


function create_title($post) {
  // must remember to check if username is unique

  $username = $post["username"];
  $password = $post["password"];
  $date_time_created = generate_datetime_for_sql();

  $safe_username = sql_stringprep($username);
  $hashed_password = hash_password($password);

  $query  = "INSERT INTO Web_Users (Username, HashedPassword, UserRoleID, DateTimeCreated)";
  $query .= " VALUES (?, ?, ?, ?)";

  $params = array($safe_username, $hashed_password, $user_role_id, $date_time_created);

  try {
    $created_user = sql_request_query($query, $params);
  }
  catch (exception $e) {
    sql_log_errors($e, sqlsrv_errors());
    $_SESSION["error"] .= make_exception_message_to_user($e);

  }
  return $created_user;

}

function edit_title($post) {
  $user_id = $_GET["userID"];
  $user = find_user_by_user_id($user_id);
  if ($user) {
    if ($user["UserRoleID"] == 1) {
      $_SESSION["message"] .= "Can't edit admin.<br />";
      return FALSE;
    }
    if ($user_id == $_SESSION["user_id"]) {
      $_SESSION["message"] .= "(refactor) Can't edit the user that is logged in.<br />";
      return FALSE;
    }
    else {
      $safe_user_id = sql_stringprep($user_id);
      $safe_username = sql_stringprep($post["new_username"]);

      $query  = "UPDATE Web_Users";
      $query .= " SET Username = ?";

      $params = array($safe_username);

      if (!empty($post["new_password"])) {
        // need this safe_password?
        $safe_password = sql_stringprep($post["new_password"]);
        $hashed_password = hash_password($safe_password);
        $query .= " , HashedPassword = ?";

        $params = array_push($hashed_password);
      }
      $query .= " WHERE UserID = ?";

      $params = array_push($safe_user_id);
      $edited_user = sql_request_query($query, $params);
      return $edited_user;
    }
  }
  else {
    // i dont think this can ever happen
    $_SESSION["message"] .= "Couldn't find user.<br />";
    return FALSE;
  }
}

function delete_title($get) {

  $user_id = $get["userID"];
  $user = find_user_by_user_id($user_id);
  if ($user) {
    if ($user["UserRoleID"] == 1) {
      $_SESSION["message"] .= "Can't delete admin.<br />";
      return FALSE;
    }
    elseif ($user_id == $_SESSION["user_id"]) {
      $_SESSION["message"] .= "Can't delete the user that is logged in.<br />";
      return FALSE;
    }
    else {
      $safe_user_id = sql_stringprep($user_id);

      $query  = "DELETE FROM Web_Users";
      $query .= " WHERE UserID = ?";

      $params = array($safe_user_id);

      $deleted_user = sql_request_query($query, $params);
      return $deleted_user;
    }
  }
  else {
    // i dont think this can ever happen
    $_SESSION["message"] .= "Couldn't find user.<br />";
    return FALSE;
  }
}


// log or database functions
function log_user_in_database($user) {
  //rename to create_user_log($user) ?

  $user_id = $user["UserID"];
  $datetime_login = generate_datetime_for_sql();

  $datetime_last_activity = generate_datetime_for_sql();
  // date_default_timezone_set('Australia/Melbourne');

  // $safe_datetime_login = sql_stringprep($datetime_login);

  $query  = "INSERT INTO Web_Logins (UserID, DateTimeLogin, DateTimeLastActivity)";
  $query .= " VALUES (?, ?, ?)";
  $query .= " ; SELECT SCOPE_IDENTITY() as id";

  $params = array($user_id, $datetime_login, $datetime_last_activity);

  $logged_user = sql_request_query($query, $params);
  $_SESSION["login_id"] = sql_get_scope_identity($logged_user);
  $_SESSION["last_activity"] = $datetime_last_activity;
  return $logged_user;
}

function update_user_log_in_database($login_id) {

  $datetime_last_activity = generate_datetime_for_sql();
  // date_default_timezone_set('Australia/Melbourne');

  // $safe_datetime_login = sql_stringprep($datetime_login);

  $query  = "UPDATE Web_Logins";
  $query .= " SET DateTimeLastActivity = ?";
  $query .= " WHERE Web_LoginID = ?";

  $params = array($datetime_last_activity, $login_id);

  $logged_user = sql_request_query($query, $params);
  return $logged_user;
}

function log_sql_errors_in_database($exception, $error_string = " ") {

    $datetime_log = generate_datetime_for_sql();
    $error_message = $error_string;
    $exception_message = $exception->getMessage();
    $exception_code = $exception->getCode();
    $exception_trace = $exception->getTraceAsString();

    $query  = "INSERT INTO Web_Errors (DateTimeLog, ErrorMessage, ExceptionMessage, ExceptionCode, ExceptionTrace)";
    $query .= " VALUES (?, ?, ?, ?, ?)";
    //$query .= " ; SELECT SCOPE_IDENTITY() as id";

    $params = array($datetime_log, $error_message, $exception_message, $exception_code, $exception_trace);

    $logged_error = sql_request_query_for_error_log($query, $params);
    return $logged_error;
}


// about functions (OS and Browser)
function about_get_browser($user_agent = null) {

  if(!isset($user_agent) && isset($_SERVER['HTTP_USER_AGENT'])) {
      $user_agent = $_SERVER['HTTP_USER_AGENT'];
  }
    $browser        =   "Unknown Browser";
    $browser_array  =   array(
                            '/msie|trident/i' =>  'Internet Explorer',
                            '/firefox/i'      =>  'Firefox',
                            '/safari/i'       =>  'Safari',
                            '/chrome/i'       =>  'Chrome',
                            '/edge/i'         =>  'Edge',
                            '/opera/i'        =>  'Opera',
                            '/netscape/i'     =>  'Netscape',
                            '/maxthon/i'      =>  'Maxthon',
                            '/konqueror/i'    =>  'Konqueror',
                            '/mobile/i'       =>  'Handheld Browser'
                        );

    foreach ($browser_array as $regex => $value) {
        if (preg_match($regex, $user_agent)) {
            $browser    =   $value;
        }
    }
    return $browser;
}

function about_get_OS($user_agent = null) {
    if(!isset($user_agent) && isset($_SERVER['HTTP_USER_AGENT'])) {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
    }

    // https://stackoverflow.com/questions/18070154/get-operating-system-info-with-php
    $os_array = [
        'windows nt 10'                              =>  'Windows 10',
        'windows nt 6.3'                             =>  'Windows 8.1',
        'windows nt 6.2'                             =>  'Windows 8',
        'windows nt 6.1|windows nt 7.0'              =>  'Windows 7',
        'windows nt 6.0'                             =>  'Windows Vista',
        'windows nt 5.2'                             =>  'Windows Server 2003/XP x64',
        'windows nt 5.1'                             =>  'Windows XP',
        'windows xp'                                 =>  'Windows XP',
        'windows nt 5.0|windows nt5.1|windows 2000'  =>  'Windows 2000',
        'windows me'                                 =>  'Windows ME',
        'windows nt 4.0|winnt4.0'                    =>  'Windows NT',
        'windows ce'                                 =>  'Windows CE',
        'windows 98|win98'                           =>  'Windows 98',
        'windows 95|win95'                           =>  'Windows 95',
        'win16'                                      =>  'Windows 3.11',
        'mac os x 10.1[^0-9]'                        =>  'Mac OS X Puma',
        'macintosh|mac os x'                         =>  'Mac OS X',
        'mac_powerpc'                                =>  'Mac OS 9',
        'linux'                                      =>  'Linux',
        'ubuntu'                                     =>  'Linux - Ubuntu',
        'iphone'                                     =>  'iPhone',
        'ipod'                                       =>  'iPod',
        'ipad'                                       =>  'iPad',
        'android'                                    =>  'Android',
        'blackberry'                                 =>  'BlackBerry',
        'webos'                                      =>  'Mobile',

        '(media center pc).([0-9]{1,2}\.[0-9]{1,2})' =>'Windows Media Center',
        '(win)([0-9]{1,2}\.[0-9x]{1,2})'             =>'Windows',
        '(win)([0-9]{2})'                            =>'Windows',
        '(windows)([0-9x]{2})'                       =>'Windows',

        // Doesn't seem like these are necessary...not totally sure though..
        //'(winnt)([0-9]{1,2}\.[0-9]{1,2}){0,1}'=>'Windows NT',
        //'(windows nt)(([0-9]{1,2}\.[0-9]{1,2}){0,1})'=>'Windows NT', // fix by bg

        'Win 9x 4.90'                                =>'Windows ME',
        '(windows)([0-9]{1,2}\.[0-9]{1,2})'          =>'Windows',
        'win32'                                      =>'Windows',
        '(java)([0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,2})' =>'Java',
        '(Solaris)([0-9]{1,2}\.[0-9x]{1,2}){0,1}'    =>'Solaris',
        'dos x86'                                    =>'DOS',
        'Mac OS X'                                   =>'Mac OS X',
        'Mac_PowerPC'                                =>'Macintosh PowerPC',
        '(mac|Macintosh)'                            =>'Mac OS',
        '(sunos)([0-9]{1,2}\.[0-9]{1,2}){0,1}'       =>'SunOS',
        '(beos)([0-9]{1,2}\.[0-9]{1,2}){0,1}'        =>'BeOS',
        '(risc os)([0-9]{1,2}\.[0-9]{1,2})'          =>'RISC OS',
        'unix'                                       =>'Unix',
        'os/2'                                       =>'OS/2',
        'freebsd'                                    =>'FreeBSD',
        'openbsd'                                    =>'OpenBSD',
        'netbsd'                                     =>'NetBSD',
        'irix'                                       =>'IRIX',
        'plan9'=>'Plan9',
        'osf'=>'OSF',
        'aix'=>'AIX',
        'GNU Hurd'=>'GNU Hurd',
        '(fedora)'=>'Linux - Fedora',
        '(kubuntu)'=>'Linux - Kubuntu',
        '(ubuntu)'=>'Linux - Ubuntu',
        '(debian)'=>'Linux - Debian',
        '(CentOS)'=>'Linux - CentOS',
        '(Mandriva).([0-9]{1,3}(\.[0-9]{1,3})?(\.[0-9]{1,3})?)'=>'Linux - Mandriva',
        '(SUSE).([0-9]{1,3}(\.[0-9]{1,3})?(\.[0-9]{1,3})?)'=>'Linux - SUSE',
        '(Dropline)'=>'Linux - Slackware (Dropline GNOME)',
        '(ASPLinux)'=>'Linux - ASPLinux',
        '(Red Hat)'=>'Linux - Red Hat',
        // Loads of Linux machines will be detected as unix.
        // Actually, all of the linux machines I've checked have the 'X11' in the User Agent.
        //'X11'=>'Unix',
        '(linux)'=>'Linux',
        '(amigaos)([0-9]{1,2}\.[0-9]{1,2})'=>'AmigaOS',
        'amiga-aweb'=>'AmigaOS',
        'amiga'=>'Amiga',
        'AvantGo'=>'PalmOS',
        //'(Linux)([0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,3}(rel\.[0-9]{1,2}){0,1}-([0-9]{1,2}) i([0-9]{1})86){1}'=>'Linux',
        //'(Linux)([0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,3}(rel\.[0-9]{1,2}){0,1} i([0-9]{1}86)){1}'=>'Linux',
        //'(Linux)([0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,3}(rel\.[0-9]{1,2}){0,1})'=>'Linux',
        '[0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,3})'=>'Linux',
        '(webtv)/([0-9]{1,2}\.[0-9]{1,2})'=>'WebTV',
        'Dreamcast'=>'Dreamcast OS',
        'GetRight'=>'Windows',
        'go!zilla'=>'Windows',
        'gozilla'=>'Windows',
        'gulliver'=>'Windows',
        'ia archiver'=>'Windows',
        'NetPositive'=>'Windows',
        'mass downloader'=>'Windows',
        'microsoft'=>'Windows',
        'offline explorer'=>'Windows',
        'teleport'=>'Windows',
        'web downloader'=>'Windows',
        'webcapture'=>'Windows',
        'webcollage'=>'Windows',
        'webcopier'=>'Windows',
        'webstripper'=>'Windows',
        'webzip'=>'Windows',
        'wget'=>'Windows',
        'Java'=>'Unknown',
        'flashget'=>'Windows',

        // delete next line if the script show not the right OS
        //'(PHP)/([0-9]{1,2}.[0-9]{1,2})'=>'PHP',
        'MS FrontPage'=>'Windows',
        '(msproxy)/([0-9]{1,2}.[0-9]{1,2})'=>'Windows',
        '(msie)([0-9]{1,2}.[0-9]{1,2})'=>'Windows',
        'libwww-perl'=>'Unix',
        'UP.Browser'=>'Windows CE',
        'NetAnts'=>'Windows',
    ];

    // https://github.com/ahmad-sa3d/php-useragent/blob/master/core/user_agent.php
    $arch_regex = '/\b(x86_64|x86-64|Win64|WOW64|x64|ia64|amd64|ppc64|sparc64|IRIX64)\b/ix';
    $arch = preg_match($arch_regex, $user_agent) ? '64' : '32';

    foreach ($os_array as $regex => $value) {
        if (preg_match('{\b('.$regex.')\b}i', $user_agent)) {
            return $value.' (x'.$arch.')';
        }
    }

    return 'Unknown';
}

?>
