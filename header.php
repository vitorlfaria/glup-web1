<?php
    require_once "authenticate.php";
?>
<header class="header-container">
    <a href="index.php">
        <img src="img/glup-nome.svg" alt="Glup nome" class="glup-nome-header">
    </a>
    <?php if($login): ?>
        <div class="btn-container">
            <p class="nome-user">Olá <?= $user_name ?></p>
            <a href="novo_review.php" class="btn btn-nova-review btn-scale"><i class="fa-solid fa-star"></i> Fazer avaliação</a>
            <a href="logout.php" class="btn btn-verde">Logout</a>
        </div>
    <?php else: ?>
        <div class="btn-container">
            <a href="login.php" class="btn btn-verde"><i class="fa-solid fa-arrow-right-to-bracket"></i> Login</a>
            <a href="registrar.php" class="btn btn-roxo"><i class="fa-solid fa-user-plus"></i> Register</a>
        </div>
    <?php endif; ?>
</header>
