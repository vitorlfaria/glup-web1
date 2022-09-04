<?php
    require_once "db/db_functions.php";

    $conn = connect_db();

    $erro = false;
    $erro_msg = '';
    $query = "SELECT id_bar as id,
              nome_bar as nome,
              local_bar as local,
              descricao_bar as descricao,
              nota_bar as nota
              FROM bares";
    $resultBares = mysqli_query($conn, $query);

    require_once "head.php";
    require_once "header.php";
?>
<main class="pagina-bares">
    <?php if($erro): ?>
        <h1><?= $erro_msg ?></h1>
    <?php else: ?>
        <div class="imagem-topo"></div>
        <div class="infos-container">
            <div class="infos-bar">
                <h1>Bares e Pubs</h1>
                <p>Os que você ama! e os que não ama tanto assim.</p>
            </div>
        </div>

        <div class="avaliacoes-container">
            <?php if(mysqli_num_rows($resultBares) > 0): ?>
                <?php while($bar = mysqli_fetch_assoc($resultBares)): ?>
                    <div class="avaliacao">
                        <a href="bar.php?id=<?= $bar['id'] ?>">
                            <div class="nome-nota">
                                <div>
                                    <p class="nome-user"><?= $bar['nome'] ?></p>
                                    <p><?= $bar['local'] ?></p>
                                </div>
                                <div class="estrelas">
                                    <?php
                                    $nota = round($bar['nota']);
                                    ?>
                                    <?php for ($i = 1; $i <= $nota; $i++): ?>
                                        <i class="fa-solid fa-star estrela-marcada"></i>
                                    <?php endfor; ?>
                                    <?php for ($i = 1; $i <= 5 - $nota; $i++): ?>
                                        <i class="fa-solid fa-star"></i>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <h3 class="nenhum-msg">Nenhum bar cadastrado até agora.</h3>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</main>
<?php require_once "footer.php" ?>
