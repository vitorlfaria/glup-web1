<?php
    require_once "db/db_functions.php";

    $conn = connect_db();
    $query = "SELECT id_bar, nome_bar, local_bar, nota_bar FROM bares
              ORDER BY nota_bar DESC
              LIMIT 3";
    $reviews = mysqli_query($conn, $query);
    $i = 0;
?>

<section class="mais-populares">
    <div class="titulo">
        <h2>TOP! Bares mais <span>bem avaliados</span></h2>
        <hr>
    </div>
    <?php if(mysqli_num_rows($reviews) > 0): ?>
        <div class="cards-container">
            <?php while ($review = mysqli_fetch_assoc($reviews)): ?>
                <a href="bar.php?id=<?= $review['id_bar'] ?>">
                    <div class="card-popular">
                        <h3 class="nome"><?= $review['nome_bar'] ?></h3>
                        <p class="local"><?= $review['local_bar'] ?></p>
                        <div class="estrelas">
                            <?php
                                $nota = round($review['nota_bar']);
                            ?>
                            <?php for ($i = 1; $i <= $nota; $i++): ?>
                                <i onclick="selecionarEstrela(this)" class="fa-solid fa-star estrela-marcada"></i>
                            <?php endfor; ?>
                            <?php for ($i = 1; $i <= 5 - $nota; $i++): ?>
                                <i onclick="selecionarEstrela(this)" class="fa-solid fa-star"></i>
                            <?php endfor; ?>
                        </div>
                    </div>
                </a>
            <?php endWhile; ?>
        </div>
    <?php else: ?>
        <h1>Nenhuma avaliação feita ainda</h1>
    <?php endif; ?>
</section>

<script>
</script>
