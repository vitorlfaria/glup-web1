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
            <a href="logout.php" class="btn btn-verde">LOGOUT</a>
        </div>
    <?php else: ?>
        <div class="btn-container">
            <a href="login.php" class="btn btn-verde">LOGIN</a>
            <a href="registrar.php" class="btn btn-roxo">REGISTRAR</a>
        </div>
    <?php endif; ?>
</header>
