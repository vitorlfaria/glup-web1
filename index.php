<?php
  require_once "head.php";
  require_once "header.php";
?>
	<main class="pagina-principal">
        <div class="banner">
            <h1>Avalie e converse sobre os <span>bares e pubs</span> que você ama!</h1>
            <p>E os que não ama tanto assim.</p>
        </div>
        <?php require_once "mais_populares.php";?>
        <a href="lista_bares.php">
            <h1 class="link-todos-bares">VEJA <span>TODOS OS BARES</span></h1>
        </a>
	</main>
<?php
  require_once "footer.php";
?>
