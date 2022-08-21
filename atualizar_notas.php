<?php
    require_once "db/db_functions.php";
    $conn = connect_db();

    $query = "SELECT id_bar FROM bares";
    $results = mysqli_query($conn, $query);
    while($result = mysqli_fetch_assoc($results)) {
        $id_bar = $result['id_bar'];
        // Atualiza a nota do bar com a média das reviews que o bar tem
        $query = "SELECT AVG(nota) FROM avaliacoes WHERE id_bar = '$id_bar'";
        $result = mysqli_query($conn, $query);
        if(mysqli_num_rows($result) > 0) {
            $media = mysqli_fetch_assoc($result);
            $nota = $media['AVG(nota)'];

            $query = "UPDATE bares
                    SET nota_bar = '$nota'
                    WHERE id_bar = '$id_bar'"
            ;
            mysqli_query($conn, $query);
        }
    }
    disconnect_db($conn);

