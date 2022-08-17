<?php
    require_once "sanetize.php";
    require_once "./db/db_functions.php";

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        if(empty($_POST['nome'])) {
            $erro_nome = 'O nome é obrigatório.';
        } else {
            $nome = sanitize($_POST['nome']);
        }
	    if(empty($_POST['sobrenome'])) {
		    $erro_sobrenome = 'O sobrenome é obrigatório.';
	    } else {
		    $sobrenome = sanitize($_POST['sobrenome']);
	    }
        if(empty($_POST['login'])) {
            $erro_login = 'O login é obrigatório.';
        } else {
            $login = sanitize($_POST['login']);
        }
        if(empty($_POST['email'])) {
            $erro_email = 'O email é obrigatório.';
        } else {
            $email = $_POST['email'];
        }
        if(empty($_POST['senha'])) {
            $erro_senha = 'A senha é obrigatória.';
        } else {
            $senha = $_POST['senha'];
        }
        if(empty($_POST['senha-conf'])) {
            $erro_senha_conf = 'A confirmação de senha é obrigatória.';
        } else {
            if($_POST['senha-conf'] !== $_POST['senha']) {
              $erro_senha_conf = 'Há divergência entre a senha e a confirmação.';
            }
        }

        if (!empty($nome) && !empty($sobrenome) && !empty($login) && !empty($email) && !empty($senha)) {

          $conn = connect_db();

          $nome = mysqli_real_escape_string($conn, $nome);
          $sobrenome = mysqli_real_escape_string($conn, $sobrenome);
          $email = mysqli_real_escape_string($conn, $email);
          $login = mysqli_real_escape_string($conn, $login);
          $senha = mysqli_real_escape_string($conn, $senha);
          $senha = md5($senha);

          $query = "SELECT * FROM usuarios WHERE email = '$email'";
          $email_check = mysqli_query($conn, $query);
          if(mysqli_num_rows($email_check) > 0) {
              $insucesso = "Esse email já está sendo usado";
          } else {
            $query = "INSERT INTO usuarios (nome, sobrenome, login, email, senha)
                VALUES ('$nome', '$sobrenome', '$login', '$email', '$senha')";
            if(mysqli_query($conn, $query)){
              $sucesso = "Usuário criado com sucesso!<br>Você será redirecionado para fazer login em 3 segundos.";
              disconnect_db($conn);
              header("refresh:3; url=login.php");
              exit();
            } else {
              $insucesso = "Erro ao registrar usuário, tente novamente mais tarde.";
            }
          }
        }
    }
?>

<?php include_once "head.php"; ?>

<main class="pagina-registro">
    <a href="index.php">
        <img src="img/glup-logo.svg" alt="Logo glup" class="logo-registro">
    </a>
  <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" class="form sombra">
    <fieldset class="fieldset inline">
        <label for="nome" class="<?php if (!empty($erro_nome)) {echo 'tem-erro';} ?>">
            Nome:
            <input type="text"
                   name="nome"
                   placeholder="Insira seu nome"
                   class="input"
                   value="<?php if(isset($nome)) echo $nome?>"
                   required
            >

            <?php if (!empty($erro_nome)): ?>
                <span class="erro-form"><?= $erro_nome ?></span>
            <?php endif; ?>
        </label>

        <label for="sobrenome" class="<?php if (!empty($erro_sobrenome)) {echo 'tem-erro';} ?>">
            Sobrenome:
            <input type="text"
                   name="sobrenome"
                   placeholder="Insira seu sobrenome"
                   class="input"
                   value="<?php if(isset($sobrenome)) echo $sobrenome?>"
                   required
            >

            <?php if (!empty($erro_sobrenome)): ?>
                <span class="erro-form"><?= $erro_sobrenome ?></span>
            <?php endif; ?>
        </label>
    </fieldset>

      <label for="login" class="<?php if (!empty($erro_login)) {echo 'tem-erro';} ?>">
          Usuário:
          <input type="text"
                 name="login"
                 placeholder="Insira seu email"
                 class="input"
                 value="<?php if(isset($login)) echo $login?>"
                 required
          >
			  <?php if (!empty($erro_login)): ?>
            <span class="erro-form"><?= $erro_login ?></span>
			  <?php endif; ?>
      </label>

    <label for="email" class="<?php if (!empty($erro_email)) {echo 'tem-erro';} ?>">
      Email:
      <input type="email"
             name="email"
             placeholder="Insira seu email"
             class="input"
             value="<?php if(isset($email)) echo $email?>"
             required
      >
      <?php if (!empty($erro_email)): ?>
          <span class="erro-form"><?= $erro_email ?></span>
      <?php endif; ?>
    </label>

    <label for="senha" class="<?php if (!empty($erro_senha)) {echo 'tem-erro';} ?>">
      Senha:
      <input type="password"
             name="senha"
             placeholder="Crie uma senha"
             class="input"
             required
      >
      <?php if (!empty($erro_senha)): ?>
          <span class="erro-form"><?= $erro_senha ?></span>
      <?php endif; ?>
    </label>

    <label for="senha-conf" class="<?php if (!empty($erro_senha_conf)) {echo 'tem-erro';} ?>">
      Confirme a senha:
      <input type="password" name="senha-conf" placeholder="Confirmação de senha" class="input" required>
      <?php if (!empty($erro_senha_conf)): ?>
          <span class="erro-form"><?= $erro_senha_conf ?></span>
      <?php endif; ?>
    </label>

      <?php if (!empty($sucesso)): ?>
        <span class="sucesso"><?= $sucesso ?></span>
      <?php elseif (!empty($insucesso)): ?>
        <span class="insucesso"><?= $insucesso ?></span>
      <?php endif; ?>

      <button type="submit" class="btn btn-verde btn-hover">REGISTRAR</button>
  </form>
    <p class="copy">&copy GLUP - 2022</p>
</main>
