<?php
    require_once "db/db_functions.php";

    $conn = connect_db();
    $query = "SELECT DISTINCT nome_bar, id, localizacao, nota FROM reviews
            ORDER BY nota DESC
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
                <?php
                    $notas[$i] = $review['nota'];
                    $i++
                ?>
                <div class="card-popular">
                    <img src="https://images.unsplash.com/photo-1566417713940-fe7c737a9ef2?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2029&q=80"
                         alt="foto bar" class="foto">
                    <h3 class="nome"><?= $review['nome_bar'] ?></h3>
                    <p class="local"><?= $review['localizacao'] ?></p>
                    <input type="hidden" class="nota_bar" value="<?= $review['nota'] ?>">
                    <div class="estrelas">
                        <i onclick="selecionarEstrela(this)" data-star="1" class="fa-solid fa-star estrela-marcada"></i>
                        <i onclick="selecionarEstrela(this)" data-star="2" class="fa-solid fa-star estrela-marcada"></i>
                        <i onclick="selecionarEstrela(this)" data-star="3" class="fa-solid fa-star estrela-marcada"></i>
                        <i onclick="selecionarEstrela(this)" data-star="4" class="fa-solid fa-star estrela-marcada"></i>
                        <i onclick="selecionarEstrela(this)" data-star="5" class="fa-solid fa-star"></i>
                    </div>
                </div>
            <?php endWhile; ?>
        </div>
    <?php else: ?>
        <h1>Nenhuma avaliação feita ainda</h1>
    <?php endif; ?>
</section>

<script>
    const notas = <?= json_encode($notas) ?>;
    console.log(notas)
</script>
