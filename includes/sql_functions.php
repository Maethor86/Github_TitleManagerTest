<?php

// sql application specific functions

// sql technical functions
function sql_connect_to_database() {
  // connects to the database
  $serverName = "H710-MAETHOR\SQLEXPRESS";
  $connectionOptions = array(
      "Database" => "TestDatabase",
      "Uid" => "TestLogin",
      "PWD" => "TestLogin123"
      );
  // establishes the connection
  $conn = sqlsrv_connect( $serverName, $connectionOptions );
  if( $conn === false ) {
      die( sqlsrv_formatted_errors( sqlsrv_errors()));
  }
  return $conn;
}

function sql_request_query($query) {
    //bad name of this function?
    global $connection;

    $query_result = sqlsrv_query($connection, $query);
    sql_confirm_query($query_result);
    return $query_result;
}

function sql_confirm_query($result_set) {
  if (!$result_set) {
      die(sql_formatted_errors(sqlsrv_errors()));
  }
}

function sql_stringprep($string) {
  return $string;
}

function sql_release_query($query) {
  // closes the connection to the database
  sqlsrv_free_stmt($query);
}

function sql_disconnect_from_database() {
  // closes the connection to the database

  global $connection;
  sqlsrv_close($connection);
}

// misc functions
function sql_formatted_errors( $errors ) {
  // shows a list of errors when trying to connect with sql server
    echo "Error information: <br/>";

    foreach ( $errors as $error )
    {
        echo "SQLSTATE: ".$error['SQLSTATE']."<br/>";
        echo "Code: ".$error['code']."<br/>";
        echo "Message: ".$error['message']."<br/>";
    }
}

function sql_get_scope_identity($logged_user) {
  sqlsrv_next_result($logged_user);
  sqlsrv_fetch($logged_user);
  $output = sqlsrv_get_field($logged_user, 0);
  return $output;

}
// about sql
function sql_show_current_user() {
  // displays the logged in user

  global $connection;
  $sql_user_name = "SELECT CONVERT(varchar(32), SUSER_SNAME())";
  $sql_user_name_stmt = sqlsrv_query( $connection, $sql_user_name);
  if( $sql_user_name_stmt === false ) {
       echo "Error in executing query.</br>";
       die( print_r( sqlsrv_errors(), true));
  }
  // retrieve results of the query
  $sql_user_name_row = sqlsrv_fetch_array($sql_user_name_stmt);

  // display the results of the query
  echo "Currently logged in to SQL Server with SQL user: ".$sql_user_name_row[0]."</br>";
}

function sql_show_current_database() {
  global $connection;
  $sql_database_name = "SELECT CONVERT(varchar(32), DB_NAME())";
  $sql_database_name_stmt = sqlsrv_query( $connection, $sql_database_name);
  if( $sql_database_name_stmt === false ) {
       echo "Error in executing query.</br>";
       die( print_r( sqlsrv_errors(), true));
  }
  // retrieve the results of the query
  $sql_database_name_row = sqlsrv_fetch_array($sql_database_name_stmt);

  // display the results of the query
  echo "Currently using the SQL Server database: ".$sql_database_name_row[0]."</br>";
}

function sql_show_sqlversion() {
  // ask for SQL Version

  global $connection;
  $tsql= "SELECT @@Version as SQL_VERSION";
  // executes the query
  $getResults= sqlsrv_query($connection, $tsql);
  // error handling

  if ( $getResults == FALSE ) {
    die( sqlsrv_formatted_errors( sqlsrv_errors()));
  }
  while ( $row = sqlsrv_fetch_array( $getResults, SQLSRV_FETCH_ASSOC )) {
      echo ( $row['SQL_VERSION']);
      //echo ("<br/>");
  }
  sql_release_query($getResults);
}

?>
