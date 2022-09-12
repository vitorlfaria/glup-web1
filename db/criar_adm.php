<?php
    require_once "db_functions.php";

    $conn = connect_db();

    $id = 16;
    $permissao = 'ADMIN';

    $query = "INSERT INTO permissoes (id_usuario, permissao)
              VALUES ('$id', '$permissao')";
    if (mysqli_query($conn, $query)) {
        echo "Admin cadastrado com sucesso";
    } else {
        echo "Deu erro nessa merda " . mysqli_error($conn);
    }

    disconnect_db($conn);
