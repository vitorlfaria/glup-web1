<?php
  session_start();
  if (isset($_SESSION["user_id"]) && isset($_SESSION["user_nome"]) && isset($_SESSION["user_login"])) {
    $login = true;
    $user_id = $_SESSION["user_id"];
    $user_name = $_SESSION["user_nome"];
    $user_login = $_SESSION["user_login"];
    $user_permissao = isset($_SESSION['user_permissao']) ?: null;
  }
  else{
    $login = false;
  }
