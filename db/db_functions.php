<?php
  require_once "credentials.php";
  function connect_db(){
    global $host, $user, $password, $dbname;
    $conn = mysqli_connect($host, $user, $password, $dbname);

    if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
    }

    return($conn);
  }

  function disconnect_db($conn){
    mysqli_close($conn);
  }
