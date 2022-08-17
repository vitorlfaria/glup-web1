<?php
  session_start();

  session_unset();

  session_destroy();

  header("location: " . dirname($_SERVER['SCRIPT_NAME']) . "/index.php");
