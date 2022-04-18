<?php

  // Collect login variable values
  include 'conf.php';

  // An attempt to create a connection to db
  $conn = new mysqli($dbname, $dbuser, $dbpass, $dbname);

  // Report whether failure or success
  if ($conn->connect_errno) {
    echo "Connection failed: \n" . $conn->connect_error;
    exit();
  } else {
    // Remove this part once you have the connection working
    echo "Connected successfully <br/>";
  }
?>