<?php

$errors = array();

function has_presence($value) {
  // returns TRUE or false

  // checks to see if form element is present (not empty)
  return isset($value) && $value !== "";
}


function field_validation($to_be_val, $fields_required, $errors) {
  // returns an array of errors (possibly empty)

  // validation
  foreach ($fields_required as $field) {
    $value = trim($to_be_val[$field]);
    if (!has_presence($value)) {
      $errors[$field] =  ucfirst(str_replace("_", " ", $field)) . " can't be blank";
    }
  }
  return $errors;
}



function form_errors($errors=array()) {
  // returns string of the output

  // outputs all the form errors
  $output = "";
  if (!empty($errors)) {
    $output .= "<div class=\"error\">";
    $output .= "Please fix the following errors";
    $output .= "<ul>";
    foreach ($errors as $key => $error) {
      $output .= "<li>{$error}</li>";
    }
    $output .= "</ul>";
    $output .= "</div>";
  }
  return $output;
}

?>
