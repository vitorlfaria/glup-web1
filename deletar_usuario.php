<?php
require "head.php";
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deletar'])) {
        require "authenticate.php";
        require "db/db_functions.php";
        $conn = connect_db();

        $query = "DELETE FROM usuarios
                  WHERE id_usuario = '$user_id'";
        if(mysqli_query($conn, $query)) {
            header("refresh:5; url=index.php");
            session_unset();
            session_destroy();
            echo "Usuário deletado com sucesso. Você será redirecionado em 5 segundos.";
        } else {
            echo "Algo deu errado, tente novamente mais tarde." . mysqli_error($conn);
        }
    }
