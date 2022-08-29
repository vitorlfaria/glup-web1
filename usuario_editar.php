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
    <form action="<?= $_SERVER['PHP_SELF'] ?>" class="form form-editar-perfil">
        <fieldset class="fieldset inline">
            <label for="foto-perfil" class="input input-file">
                <i class="fa-solid fa-cloud-arrow-up"></i> Foto de perfil
                <input type="file" id="foto-perfil" name="foto-perfil" accept="image/*" class="input" hidden>
            </label>
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

        <fieldset class="fieldset inline">
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
        </fieldset>

        <fieldset class="fieldset inline">
            <label for="senha" class="<?php if (!empty($erro_senha)) {echo 'tem-erro';} ?>">
                Senha antiga:
                <input type="password"
                       name="senha-antiga"
                       placeholder="Crie uma senha"
                       class="input"
                       required
                >
                <?php if (!empty($erro_senha)): ?>
                    <span class="erro-form"><?= $erro_senha ?></span>
                <?php endif; ?>
            </label>

            <label for="senha" class="<?php if (!empty($erro_senha)) {echo 'tem-erro';} ?>">
                Senha nova:
                <input type="password"
                       name="senha-nova"
                       placeholder="Crie uma senha"
                       class="input"
                       required
                >
                <?php if (!empty($erro_senha)): ?>
                    <span class="erro-form"><?= $erro_senha ?></span>
                <?php endif; ?>
            </label>

            <label for="senha-conf" class="<?php if (!empty($erro_senha_conf)) {echo 'tem-erro';} ?>">
                Confirme a senha nova:
                <input type="password" name="senha-nova-conf" placeholder="Confirmação de senha" class="input" required>
                <?php if (!empty($erro_senha_conf)): ?>
                    <span class="erro-form"><?= $erro_senha_conf ?></span>
                <?php endif; ?>
            </label>
        </fieldset>
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
