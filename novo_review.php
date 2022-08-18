<?php
    require_once "db/db_functions.php";
    require_once "authenticate.php";

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
      $conn = connect_db();
      if(empty($_POST['nome_bar'])){
          $erro_nome_bar = 'O nome do bar é obrigatório';
      } else {
          $nome_bar = mysqli_real_escape_string($conn, $_POST['nome_bar']);
      }
      if(empty($_POST['local'])){
          $erro_nome_bar = 'A localização do bar é obrigatória';
      } else {
          $local = mysqli_real_escape_string($conn, $_POST['local']);
      }
      if(!empty($_POST['review'])) {
          $review = mysqli_real_escape_string($conn, $_POST['review']);
      }
      $nota = intval($_POST['nota']);

      if(!isset($editar)){
          $editar = false;
      }
      if(!empty($nome_bar) && !empty($local) && !empty($nota)) {
          $query = "SELECT * FROM reviews
                    WHERE id_user = '$user_id'
                    AND nome_bar = '$nome_bar';"
          ;
          $result = mysqli_query($conn, $query);
          if(mysqli_num_rows($result) > 0) {
              if($_POST['alterar_review'] === 'sim') {
                  $alterar_review = true;
              } elseif($_POST['alterar_review'] === 'nao') {
                  $alterar_review = false;
              } else {
                  $alterar_review = false;
                  $review_repetido = true;
              }
              if($alterar_review) {
                  $review_antiga = mysqli_fetch_assoc($result);
                  $review_id = $review_antiga['id'];
                  if (!empty($review)){
                      $query = "UPDATE reviews
                                SET nota = '$nota', review = '$review'
                                WHERE id = '$review_id'";
                  } else {
                      $query = "UPDATE reviews
                                SET nota = '$nota'
                                WHERE id = '$review_id'";
                  }
                  if(mysqli_query($conn, $query)){
                      $sucesso = "Avaliação alterada com sucesso!<br> Deseja registrar mais uma avaliação?";
                      disconnect_db($conn);
                  }
              }
          } else {
              if (!empty($review)){
                  $query = "INSERT INTO reviews (id_user, nome_bar, localizacao, nota, review)
                            VALUES ('$user_id', '$nome_bar', '$local', '$nota', '$review')";
              } else {
                  $query = "INSERT INTO reviews (id_user, nome_bar, localizacao, nota)
                            VALUES ('$user_id', '$nome_bar', '$local', '$nota')";
              }
              if(mysqli_query($conn, $query)){
                  $sucesso = "Avaliação registrada com sucesso!<br> Deseja registrar mais uma avaliação?";
                  disconnect_db($conn);
              }
          }
      }
    }
    require_once "head.php";
    require_once "header.php";
?>
    <?php if(!$login): ?>
        <main class="pagina-novo-review pt-pagina">
            <h1>Para fazer um review é preciso estar logado. <a href="login.php"><i class="fa-solid fa-arrow-right-to-bracket"></i></a></h1>
        </main>
    <?php else: ?>
        <main class="pagina-novo-review pt-pagina">
            <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" class="form-review">
                <label for="nome_bar" class="<?php if (!empty($erro_nome_bar)) {echo 'tem-erro';} ?>">
                    Nome do bar/pub: <span class="required">*</span>
                    <input type="text"
                           name="nome_bar"
                           placeholder="Nome"
                           class="input"
                           value="<?php if(isset($nome_bar)) echo $nome_bar?>"
                           required
                    >
                    <?php if (!empty($erro_nome_bar)): ?>
                        <span class="erro-form"><?= $erro_nome_bar ?></span>
                    <?php endif; ?>
                </label>

                <label for="local" class="<?php if (!empty($erro_local)) {echo 'tem-erro';} ?>">
                    Localização: <span class="required">*</span>
                    <input type="text"
                           name="local"
                           placeholder="Ex: Centro/Curitiba"
                           class="input"
                           value="<?php if(isset($local)) echo $local?>"
                           required
                    >
                    <?php if (!empty($erro_local)): ?>
                        <span class="erro-form"><?= $erro_local ?></span>
                    <?php endif; ?>
                </label>

                <div class="estrelas">
                    <p>Nota: <span class="required">*</span></p>
                    <input type="hidden" id="nota" name="nota" value="5">
                    <i onclick="selecionarEstrela(this)" data-star="1" class="fa-solid fa-star"></i>
                    <i onclick="selecionarEstrela(this)" data-star="2" class="fa-solid fa-star"></i>
                    <i onclick="selecionarEstrela(this)" data-star="3" class="fa-solid fa-star"></i>
                    <i onclick="selecionarEstrela(this)" data-star="4" class="fa-solid fa-star"></i>
                    <i onclick="selecionarEstrela(this)" data-star="5" class="fa-solid fa-star"></i>
                </div>

                <label for="review" class="<?php if (!empty($erro_local)) {echo 'tem-erro';} ?>">
                    Review:
                    <textarea
                           name="review"
                           placeholder="Fale mais sobre sua experiência com esse bar"
                           class="input"
                           rows="4"
                           cols="50"
                    ><?php if(isset($review)) echo $review?></textarea>
                </label>
                <?php if(isset($review_repetido)): ?>
                    <span class="insucesso">
                        Você já fez um review sobre esse bar. Gostaria de alterar seu review?
                        <span class="btn-container">
                            <input type="hidden" id="alterar_review" name="alterar_review">
                            <input onclick="alterarReview(this)" type="submit" value="Sim" class="btn">
                            <input onclick="alterarReview(this)" type="submit" value="Não" class="btn">
                        </span>
                    </span>
                <?php elseif (!empty($sucesso)): ?>
                    <span class="sucesso">
                        <?= $sucesso ?>
                        <span class="btn-container">
                            <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn">Sim</a>
                            <a href="home.php" class="btn">Não. Ir para home.</a>
                        </span>
                    </span>
                <?php endif; ?>

                <button type="submit" class="btn btn-verde btn-scale">Enviar review</button>
            </form>
        </main>
        <script>
            function selecionarEstrela(star) {
                const nota = star.dataset.star
                marcarNota(nota)
            }
            function marcarNota(nota) {
                document.querySelector("#nota").value = nota
                const estrelas = document.querySelectorAll("i")
                estrelas.forEach(estrela => {
                    estrela.classList.remove('estrela-marcada')
                    if(estrela.dataset.star <= nota) {
                        estrela.classList.add('estrela-marcada')
                    }
                })
            }
            <?php if(!empty($nota)): ?>
                marcarNota(<?= $nota ?>)
            <?php else: ?>
                marcarNota(5)
            <?php endif; ?>

            function alterarReview(btn) {
                if(btn.value === "Sim") {
                    document.querySelector("#alterar_review").value = "sim"
                } else {
                    document.querySelector("#alterar_review").value = "nao"
                }
            }
        </script>
    <?php endif; ?>

<?php
require_once "footer.php";
?>
