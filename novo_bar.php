<?php
require_once "authenticate.php";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once "db/db_functions.php";
        require_once "sanetize.php";
        $conn = connect_db();

        if (empty($_POST['nome'])) {
            $erro_nome = 'Este campo é obrigatório.';
        } else {
            $nome_post = mysqli_real_escape_string($conn, sanitize($_POST['nome']));
            $query = "SELECT nome_bar FROM bares WHERE nome_bar = '$nome_post'";
            $result = mysqli_query($conn, $query);
            if (mysqli_num_rows($result) > 0) {
                $erro_nome = 'Já tem um bar cadastrado com esse nome.';
            } else {
                $nome = $nome_post;
            }
        }
        if (empty($_POST['local'])) {
            $erro_local = 'Este campo é obrigatório.';
        } else {
            $local = mysqli_real_escape_string($conn, sanitize($_POST['local']));
        }
        if (empty($_POST['descricao'])) {
            $erro_descricao = 'Este campo é obrigatório.';
        } else {
            $descricao = mysqli_real_escape_string($conn, sanitize($_POST['descricao']));
        }

        if (!empty($nome) && !empty($local) && !empty($descricao)) {

            $query = "INSERT INTO bares (nome_bar, local_bar, descricao_bar, nota_bar)
                  VALUES ('$nome', '$local', '$descricao', 0)";
            if (mysqli_query($conn, $query)) {
                $sucesso = 'Bar cadastrado com sucesso!';
            } else {
                $insucesso = 'Algo deu errado... ' . mysqli_error($conn);
            }

        }

        disconnect_db($conn);
    }

require_once "head.php";
require_once "header.php";
?>

<?php if(!isset($user_permissao)): ?>
    <main class="pagina-novo-review">
        <h1>Acesso restrito. <a href="index.php"><i class="fa-solid fa-person-walking-arrow-loop-left"></i></a></h1>
    </main>
<?php else: ?>
    <main class="pagina-novo-review">
        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" class="form-review">
            <h1>Cadastrar <span>novo bar</span></h1>
            <label for="login" class="<?php if (!empty($erro_nome)) {echo 'tem-erro';} ?>">
                Nome do Bar:
                <input type="text"
                       name="nome"
                       placeholder="Já sabe"
                       class="input"
                       value="<?php if(isset($nome)) echo $nome?>"
                       required
                >
                <?php if (!empty($erro_nome)): ?>
                    <span class="erro-form"><?= $erro_nome ?></span>
                <?php endif; ?>
            </label>

            <label for="login" class="<?php if (!empty($erro_local)) {echo 'tem-erro';} ?>">
                Localização:
                <input type="text"
                       name="local"
                       placeholder="Bairro - Cidade"
                       class="input"
                       value="<?php if(isset($local)) echo $local ?>"
                       required
                >
                <?php if (!empty($erro_local)): ?>
                    <span class="erro-form"><?= $erro_local ?></span>
                <?php endif; ?>
            </label>

            <label for="review" class="<?php if (!empty($erro_descricao)) {echo 'tem-erro';} ?>">
                Descrição:
                <textarea
                        name="descricao"
                        placeholder="Descreva o bar kk"
                        class="input"
                        rows="4"
                        cols="50"
                >
                    <?php if(isset($descricao)) echo $descricao?>
                </textarea>
                <?php if (!empty($erro_descricao)): ?>
                    <span class="erro-form"><?= $erro_descricao ?></span>
                <?php endif; ?>
            </label>
            <?php if (!empty($sucesso)): ?>
                <span class="sucesso">
                        <?= $sucesso ?>
                        <span class="btn-container">
                            <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn">Sim</a>
                            <a href="index.php" class="btn">Não. Ir para home.</a>
                        </span>
                    </span>
            <?php elseif (!empty($insucesso)): ?>
                <span class="insucesso"><?= $insucesso ?></span>
            <?php endif; ?>

            <button type="submit" class="btn btn-verde btn-scale">Cadastrar</button>
        </form>
    </main>
<?php endif; ?>

<?php require_once "footer.php"; ?>
