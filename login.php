<?php
  require_once "./db/db_functions.php";
  require_once "sanetize.php";

  $erro = false;
  $login = $senha = $erro_msg = "";

  if($_SERVER['REQUEST_METHOD'] === 'POST') {
	  if(empty($_POST['login'])) {
		  $erro_login = 'O login é obrigatório.';
	  } else {
		  $login = sanitize($_POST['login']);
	  }
      if(empty($_POST['senha'])) {
		  $erro_login = 'A senha é obrigatória.';
	  } else {
		  $senha = $_POST['senha'];
	  }

      if(!empty($login) && !empty($senha)) {
          $conn = connect_db();

          $login = mysqli_real_escape_string($conn, $login);
          $senha = mysqli_real_escape_string($conn, $senha);
          $senha = md5($senha);

          $query = "SELECT id_usuario, nome, login, senha FROM usuarios WHERE login = '$login'";
          $result = mysqli_query($conn, $query);
          if(mysqli_num_rows($result) == 0 ){
            $erro_msg = "Usuário ou senha incorreto.";
            $erro = true;
          } else {
              $user = mysqli_fetch_assoc($result);

              if($user['senha'] !== $senha) {
	              $erro_msg = "Usuário ou senha incorreto.";
	              $erro = true;
              } else {
                  session_start();
                  $_SESSION["user_id"] = $user['id_usuario'];
                  $_SESSION["user_nome"] = $user['nome'];
                  $_SESSION["user_login"] = $user['login'];

                  disconnect_db($conn);
	              header("refresh:1; url=home.php");
                  exit();
              }
          }
      }
  }
require_once "head.php";
?>

<main class="pagina-registro">
    <a href="index.php">
        <img src="img/glup-logo.svg" alt="Logo glup" class="logo-registro">
    </a>
  <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" class="form sombra">
	<label for="login" class="<?php if (!empty($erro_login)) {echo 'tem-erro';} ?>">
	  Login:
	  <input type="text"
			 name="login"
			 placeholder="Seu usuário"
			 class="input"
			 value="<?php if(isset($login)) echo $login?>"
			 required
	  >
        <?php if (!empty($erro_senha)): ?>
            <span class="erro-form"><?= $erro_senha ?></span>
        <?php endif; ?>
	</label>

	<label for="senha" class="<?php if (!empty($erro_senha)) {echo 'tem-erro';} ?>">
	  Senha:
	  <input type="password"
			 name="senha"
			 placeholder="Sua senha"
			 class="input"
			 required
	  >
        <?php if (!empty($erro_senha)): ?>
            <span class="erro-form"><?= $erro_senha ?></span>
        <?php endif; ?>
	</label>

	  <?php if ($erro): ?>
            <span class="insucesso"><?= $erro_msg ?></span>
	  <?php endif; ?>

	<button type="submit" class="btn btn-verde btn-scale">LOGAR</button>
  </form>
  <p class="copy">&copy GLUP - 2022</p>
</main>
