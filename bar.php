<?php
    require_once "db/db_functions.php";
    $id = $_GET['id'];
    $conn = connect_db();

    $erro = false;
    $query = "SELECT * FROM bares WHERE id_bar = '$id'";
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) > 0) {
        $bar = mysqli_fetch_assoc($result);

        $idBar = $bar['id_bar'];
        $nomeBar = $bar['nome_bar'];
        $localBar = $bar['local_bar'];
        $descricaoBar = $bar['descricao_bar'];
        $notaBar = $bar['nota_bar'];
    } else {
        $erro_msg = "Erro ao buscar informações do bar. Tente novamente mais tarde.";
        $erro = true;
    }

    if($idBar) {
        $query = "SELECT * FROM avaliacoes WHERE id_bar = '$idBar'";
        $resultAvaliacoes = mysqli_query($conn, $query);
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
                    <h1><?= $nomeBar ?></h1>
                    <p><?= $localBar ?></p>
                </div>
                <div class="nota-bar">
                    <span>Nota:</span>
                    <p class="nota"><?= $notaBar ?></p>
                    <i class="fa-solid fa-star estrela-marcada"></i>
                </div>
            </div>

            <div class="avaliacoes-container">
                <?php if (!empty($descricaoBar)): ?>
                    <h3>Sobre o bar:</h3>
                    <div class="descricao">
                        <p><?= $descricaoBar ?></p>
                    </div>
                <?php endif; ?>
                <div class="titulo">
                    <h2>Avaliações</h2>
                </div>
                <?php if(mysqli_num_rows($resultAvaliacoes) > 0): ?>
                    <?php while($avaliacao = mysqli_fetch_assoc($resultAvaliacoes)): ?>

                        <?php
                            $idUser = $avaliacao['id_usuario'];
                            $conn = connect_db();
                            $query = "SELECT nome, sobrenome FROM usuarios WHERE id_usuario = '$idUser'";
                            $resultUsuario = mysqli_query($conn, $query);
                            $usuario = mysqli_fetch_assoc($resultUsuario);
                            disconnect_db($conn);
                        ?>

                        <div class="avaliacao">
                            <div class="nome-nota">
                                <p class="nome-user"><?php echo $usuario['nome'] . " "; if($usuario['sobrenome']) echo $usuario['sobrenome'] ?></p>
                                <div class="estrelas">
                                    <?php
                                    $nota = round($avaliacao['nota']);
                                    ?>
                                    <?php for ($i = 1; $i <= $nota; $i++): ?>
                                        <i onclick="selecionarEstrela(this)" class="fa-solid fa-star estrela-marcada"></i>
                                    <?php endfor; ?>
                                    <?php for ($i = 1; $i <= 5 - $nota; $i++): ?>
                                        <i onclick="selecionarEstrela(this)" class="fa-solid fa-star"></i>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <?php if($avaliacao['avaliacao']): ?>
                                <p class="comentario"><?= $avaliacao['avaliacao'] ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <h3 class="nenhum-msg">Nenhum avaliação registrada até agora.</h3>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </main>
<?php require_once "footer.php" ?>
