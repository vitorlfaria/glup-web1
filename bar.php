<?php
    require_once "db/db_functions.php";
    $id = $_GET['id'];
    $conn = connect_db();

    $erro = false;
    $query = "SELECT * FROM bares WHERE id_bar = '$id'";
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) > 0) {
        $bar = mysqli_fetch_assoc($result);

        $nome_bar = $bar['nome_bar'];
        $local_bar = $bar['local_bar'];
        $descricao_bar = $bar['descricao_bar'];
        $nota_bar = $bar['nota_bar'];
    } else {
        $erro_msg = "Erro ao buscar informações do bar. Tente novamente mais tarde.";
        $erro = true;
    }

    disconnect_db($conn);
    require_once "head.php";
    require_once "header.php";
?>
    <main class="pagina-bar">
        <?php if($erro): ?>
            <h1><?= $erro_msg ?></h1>
        <?php else: ?>
            <div class="imagem-topo"></div>
            <div class="infos-container">
                <div class="infos-bar">
                    <h1><?= $nome_bar ?></h1>
                    <p><?= $local_bar ?></p>
                </div>
                <div class="nota-bar">
                    <span>Nota:</span>
                    <p class="nota"><?= $nota_bar ?></p>
                    <i class="fa-solid fa-star estrela-marcada"></i>
                </div>
            </div>
            <div class="avaliacoes-container">
                <div class="titulo">
                    <h2>Avaliações</h2>
                </div>
            </div>
        <?php endif; ?>
    </main>
<?php require_once "footer.php" ?>
