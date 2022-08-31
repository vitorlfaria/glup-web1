<?php
    require_once "authenticate.php";
    require_once "db/db_functions.php";
    $conn = connect_db();

    $erro = false;
    if($user_id) {
        $query = "SELECT * FROM usuarios WHERE id_usuario = '$user_id'";
        $result = mysqli_query($conn, $query);
        if(mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);

            $nome = $user['nome'];
            $sobrenome = $user['sobrenome'];
            $email = $user['email'];
            $login = $user['login'];
            $foto = $user['foto_perfil'] ?: null;
        } else {
            $erro = true;
            $erro_msg = "Você precisa estar logado para acessar seu perfil.";
        }
    }

    $erro_senha_antiga = false;
    $erro_senha_conf = false;
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        if(!empty($_POST['nome'])) {
            $editar_nome = mysqli_real_escape_string($conn, $_POST['nome']);
            $query_nome = "nome = '$editar_nome'";
        }
        if(!empty($_POST['sobrenome'])) {
            $editar_sobrenome = mysqli_real_escape_string($conn, $_POST['sobrenome']);
            $query_sobrenome = "sobrenome = '$editar_sobrenome'";
        }
        if(!empty($_POST['login'])) {
            $editar_login = mysqli_real_escape_string($conn, $_POST['login']);
            $query_login = "login = '$editar_login'";
        }
        if(!empty($_POST['email'])) {
            $editar_email = mysqli_real_escape_string($conn, $_POST['email']);
            $query_email = "email = '$editar_email'";
        }
        if(!empty($_POST['senha-antiga'])) {
            $senha_antiga = mysqli_real_escape_string($conn, $_POST['senha-antiga']);
            $senha_antiga = md5($senha_antiga);
        }
        if(!empty($_POST['senha-nova'])) {
            $editar_senha = mysqli_real_escape_string($conn, $_POST['senha-nova']);
        }
        if(!empty($_POST['senha-nova-conf'])) {
            $senha_nova_conf = mysqli_real_escape_string($conn, $_POST['senha-nova-conf']);
        }

        if(!empty($senha_antiga)) {
            $query = "SELECT senha FROM usuarios WHERE id_usuario = '$user_id'";
            $result = mysqli_query($conn, $query);
            $senha_salva = mysqli_fetch_assoc($result);

            if ($senha_antiga !== $senha_salva['senha']) {
                $erro_senha_antiga = true;
                $erro_msg = "A senha antiga não coincide com a cadastrada";
            } elseif ($editar_senha !== $senha_nova_conf) {
                $erro_senha_conf = true;
                $erro_msg = "A senha nova e a confirmação são diferentes";
            } else {
                $editar_senha = md5($editar_senha);
                $query_senha = "senha = '$editar_senha'";
            }
        }

        if (!empty($query_nome)
            || !empty($query_sobrenome)
            || !empty($query_login)
            || !empty($query_email)
            || !empty($query_senha)
        ) {
            $query = "UPDATE usuarios SET ";
            $query_body = [];
            if(!empty($query_nome)) $query_body[] = $query_nome;
            if(!empty($query_sobrenome)) $query_body[] = $query_sobrenome;
            if(!empty($query_email)) $query_body[] = $query_email;
            if(!empty($query_login)) $query_body[] = $query_login;
            if(!empty($query_senha)) $query_body[] = $query_senha;
            $query_condition = " WHERE id_usuario = '$user_id'";

            $query_string_body = implode(', ', $query_body);
            $query .= $query_string_body;
            $query .= $query_condition;

            if(mysqli_query($conn, $query)) {
                $sucesso = "Informações atualizadas com sucesso!";
            } else {
                $erro_msg = "algo deu errado, tente novamente mais tarde. " . mysqli_error($conn);
            }
        }
    }

    disconnect_db($conn);
    require_once "head.php";
    require_once "header.php";
?>

<main class="pagina-editar-perfil">
    <div class="infos-atuais">
        <img src="./img/placeholder-usuario-500x500.jpg" alt="foto de perfil" class="foto-usuario">
        <div class="informacoes">
            <p class="nome-user"><?= $nome . " " . $sobrenome ?></p>
            <p class="email"><?= $email ?></p>
        </div>
    </div>
    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" class="form form-editar-perfil">
        <h2>Editar informações do cadastro</h2>
        <fieldset class="fieldset inline">
            <label for="foto-perfil" class="input input-file">
                <i class="fa-solid fa-cloud-arrow-up"></i> Foto de perfil
                <input type="file"
                       id="foto-perfil"
                       name="foto-perfil"
                       accept="image/*"
                       class="input"
                       hidden>
            </label>
            <label for="nome">
                Nome:
                <input type="text"
                       name="nome"
                       placeholder="Insira seu nome"
                       value="<?php if(!empty($editar_nome)) echo $editar_nome ?>"
                       class="input"
                >
            </label>

            <label for="sobrenome">
                Sobrenome:
                <input type="text"
                       name="sobrenome"
                       placeholder="Insira seu sobrenome"
                       value="<?php if(!empty($editar_sobrenome)) echo $editar_sobrenome ?>"
                       class="input"
                >
            </label>
        </fieldset>

        <fieldset class="fieldset inline">
            <label for="login">
                Usuário:
                <input type="text"
                       name="login"
                       placeholder="Insira seu email"
                       value="<?php if(!empty($editar_login)) echo $editar_login ?>"
                       class="input"
                >
            </label>

            <label for="email">
                Email:
                <input type="email"
                       name="email"
                       placeholder="Insira seu email"
                       value="<?php if(!empty($editar_email)) echo $editar_email ?>"
                       class="input"
                >
            </label>
        </fieldset>

        <fieldset class="fieldset inline">
            <label for="senha-antiga" class="<?php if (!empty($erro_senha_antiga)) {echo 'tem-erro';} ?>">
                Senha antiga:
                <input type="password"
                       name="senha-antiga"
                       placeholder="Digite sua senha antiga"
                       class="input"
                >
            </label>

            <label for="senha-nova">
                Senha nova:
                <input type="password"
                       name="senha-nova"
                       placeholder="Crie uma senha nova"
                       class="input"
                >
            </label>

            <label for="senha-nova-conf" class="<?php if (!empty($erro_senha_conf)) {echo 'tem-erro';} ?>">
                Confirme a senha nova:
                <input type="password"
                       name="senha-nova-conf"
                       placeholder="Confirmação de senha nova"
                       class="input"
                >
            </label>
        </fieldset>
        <?php if (!empty($erro_msg)): ?>
            <span class="insucesso"><?= $erro_msg ?></span>
        <?php elseif (!empty($sucesso)): ?>
            <span class="sucesso"><?= $sucesso ?></span>
        <?php endif; ?>
        <button type="submit" class="btn btn-verde btn-scale">SALVAR</button>
    </form>
</main>

<script>
    const fotoInput = document.querySelector('#foto-perfil')

    fotoInput.addEventListener('change', () => {
        const labelFotoInput = document.querySelector('.input-file')
        labelFotoInput.classList.add("fundo-verde")
    })
</script>

<?php require_once "footer.php"; ?>
