<?php
    require_once "authenticate.php";
    require_once "db/db_functions.php";
    $conn = connect_db();

    $erro = false;
    if(isset($user_id)) {
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
        if(!empty($_FILES["foto-perfil"])){
            $fileExploded = explode('.', $_FILES["foto-perfil"]["name"]);
            $fileType = $fileExploded[1];
            if($fileType != "jpeg" && $fileType != "jpg" && $fileType != "png") {
                $erro_msg = "A imagem tem que ser jpg/png. Você enviou '".$_FILES["foto-perfil"]["type"];

            } elseif($_FILES["foto-perfil"]["size"] > 1000000 ) {
                $erro_msg = "A imagem não obedece o tamanho máximo de 1MB";

            } else {
                $uploadDir = "img/user/";
                $uploadFile = $uploadDir . basename($_FILES["foto-perfil"]["name"]);
                move_uploaded_file($_FILES["foto-perfil"]["tmp_name"], $uploadFile);
                $foto_perfil = basename($_FILES["foto-perfil"]["name"]);
                $query_foto = "foto_perfil = '$foto_perfil'";
            }
        }

        if (!empty($query_nome)
            || !empty($query_sobrenome)
            || !empty($query_login)
            || !empty($query_email)
            || !empty($query_senha)
            || !empty($query_foto)
        ) {
            $query = "UPDATE usuarios SET ";
            $query_body = [];
            if(!empty($query_nome)) $query_body[] = $query_nome;
            if(!empty($query_sobrenome)) $query_body[] = $query_sobrenome;
            if(!empty($query_email)) $query_body[] = $query_email;
            if(!empty($query_login)) $query_body[] = $query_login;
            if(!empty($query_senha)) $query_body[] = $query_senha;
            if(!empty($query_foto)) $query_body[] = $query_foto;
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

    $query = "SELECT nota, avaliacao, nome_bar
              FROM avaliacoes a
              INNER JOIN bares b on a.id_bar = b.id_bar
              WHERE a.id_usuario = '$user_id'
    ";
    $resultAvaliacoes = mysqli_query($conn, $query);

    disconnect_db($conn);
    require_once "head.php";
    require_once "header.php";
?>

<main class="pagina-editar-perfil">
    <div class="sidebar">
        <div class="infos-atuais">
            <img src="./img/<?php
                                if (!empty($foto)) {
                                    echo "user/" . $foto;
                                } else {
                                    echo "placeholder-500x500.jpg";
                                }
                            ?>" alt="foto de perfil" class="foto-usuario">
            <div class="informacoes">
                <p class="nome-user"><?= $nome . " " . $sobrenome ?></p>
                <p class="email"><?= $email ?></p>
            </div>
        </div>
        <button class="deletar-usuario">Deletar usuário</button>
    </div><!-- sidebar -->

    <div class="sidebar">
        <form enctype="multipart/form-data" action="<?= $_SERVER['PHP_SELF'] ?>" method="post" class="form form-editar-perfil">
            <h2>Editar informações do cadastro</h2>
            <fieldset class="fieldset inline">
                <label for="foto-perfil" class="input input-file">
                    <i class="fa-solid fa-cloud-arrow-up"></i> Foto de perfil
                    <input type="file"
                           id="foto-perfil"
                           name="foto-perfil"
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

        <div class="perfil-avaliacoes-container">
            <div class="titulo">
                <h2>Avaliações</h2>
            </div>
            <?php if(mysqli_num_rows($resultAvaliacoes) > 0): ?>
                <?php while($avaliacao = mysqli_fetch_assoc($resultAvaliacoes)): ?>
                    <div class="avaliacao">
                        <div class="nome-nota">
                            <p class="nome-user"><?= $avaliacao['nome_bar'] ?></p>
                            <div class="estrelas">
                                <?php
                                $nota = round($avaliacao['nota']);
                                ?>
                                <?php for ($i = 1; $i <= $nota; $i++): ?>
                                    <i class="fa-solid fa-star estrela-marcada"></i>
                                <?php endfor; ?>
                                <?php for ($i = 1; $i <= 5 - $nota; $i++): ?>
                                    <i class="fa-solid fa-star"></i>
                                <?php endfor; ?>
                            </div>
                            <div class="container-editar" onclick="abrirFormEditarReview(this)">
                                <span>Editar</span>
                                <i class="fa-solid fa-pen-to-square editar-review"></i>
                            </div>
                        </div>
                        <?php if($avaliacao['avaliacao']): ?>
                            <p class="comentario"><?= $avaliacao['avaliacao'] ?></p>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <h3 class="nenhum-msg">Você ainda não fez nenhuma avaliação até agora.</h3>
            <?php endif; ?>
        </div>
    </div>
</main>

<div class="modal-deletar">
    <p class="confirmacao">Tem certeza que deseja apagar sua conta?</p>
    <p class="aviso">Essa ação não poderá ser desfeita.</p>
    <div class="btn-container">
        <form action="deletar_usuario.php" method="POST">
            <input type="submit" name="deletar" value="Deletar" class="btn btn-verde">
        </form>
        <button class="deletar-usuario">Cancelar</button>
    </div>
</div>

<script>
    const fotoInput = document.querySelector('#foto-perfil')
    fotoInput.addEventListener('change', () => {
        const labelFotoInput = document.querySelector('.input-file')
        labelFotoInput.classList.add("fundo-verde")
    })

    // Função para mostrar e esconder modal de confirmação para deletar
    const btnDeletarPerfil = document.querySelectorAll('.deletar-usuario')
    btnDeletarPerfil.forEach(btn => {
        btn.addEventListener('click', () => {
            const modalConfirmacao = document.querySelector('.modal-deletar')
            modalConfirmacao.classList.toggle('show-modal')
        })
    })

    // Função para mostrar form para editar review
    function abrirFormEditarReview(nomeBar) {

    }
</script>

<?php require_once "footer.php"; ?>
