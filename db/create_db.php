<?php
  include_once "credentials.php";

  //Cria conexão
  $conn = mysqli_connect($host, $user, $password);

  //Checa a conexão
  if(!$conn) {
    die("A conexão com o banco de dados falhou: " . mysqli_connect_error());
  }

  //Cria a base de dados
  $query = "CREATE DATABASE $dbname";
  if(mysqli_query($conn, $query)){
    echo "Base de dados criada com sucesso.<br>";
  } else {
    echo "Erro ao criar base de dados: " . mysqli_error($conn) . "<br>";
  }

  //Seleciona a base de dados
  $query = "USE $dbname";
  if(mysqli_query($conn, $query)){
    echo "Base de dados selecionada com sucesso.<br>";
  } else {
    echo "Erro ao selecionar base de dados: " . mysqli_error($conn) . "<br>";
  }

  //Cria a tabela de usuários
  $query = "CREATE TABLE usuarios(
      id_usuario INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      nome VARCHAR(20) NOT NULL,
      sobrenome VARCHAR(30) NOT NULL,
      email VARCHAR(60) NOT NULL,
      senha VARCHAR(40) NOT NULL,
      foto_perfil VARCHAR(40),
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
      updates_at TIMESTAMP,
      deleted_at TIMESTAMP
  )";
  if(mysqli_query($conn, $query)){
    echo "Tabela de usuários criada com sucesso.<br>";
  } else {
    echo "Erro ao criar tabela de usuários: " . mysqli_error($conn) . "<br>";
  }

  //Cria a tabela de bares
  $query = "CREATE TABLE bares(
        id_bar INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY ,
        nome_bar VARCHAR(30) NOT NULL,
        local_bar VARCHAR(50) NOT NULL,
        descricao_bar VARCHAR(200),
        nota_bar FLOAT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME,
        deleted_at DATETIME
    );";
  if(mysqli_query($conn, $query)){
    echo "Tabela de bares criada com sucesso.<br>";
  } else {
    echo "Erro ao criar tabela de bares: " . mysqli_error($conn) . "<br>";
  }

    //Insere alguns bares iniciais na tabela bares
    $query = "INSERT INTO bares (nome_bar, local_bar)
        VALUES ('Natividade', 'Jardim das Américas - Curitiba'),
               ('Bar do Pedrão', 'Jardim das Américas - Curitiba'),
               ('Casa Verde', 'Jardim das Américas - Curitiba'),
               ('Bar O.I.D.E', 'Centro - Curitiba'),
               ('Garden', 'Largo da Ordem - Curitiba'),
               ('Zodiac', 'Largo da Ordem - Curitiba'),
               ('Quintal do Monge', 'Largo da Ordem - Curitiba')
    ";
    if(mysqli_query($conn, $query)){
        echo "Bares iniciais cadastrados com sucesso.<br>";
    } else {
        echo "Erro ao cadastrar bares iniciais: " . mysqli_error($conn) . "<br>";
    }

  //Cria a tabela de avaliações
  $query = "CREATE TABLE avaliacoes(
        id_avaliacao INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        id_usuario INT(6) UNSIGNED NOT NULL,
        id_bar INT(6) UNSIGNED NOT NULL,
        nota INT(5) NOT NULL,
        avaliacao VARCHAR(500),
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME,
        deleted_at DATETIME,
        FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
        FOREIGN KEY (id_bar) REFERENCES bares(id_bar)
    );";
  if(mysqli_query($conn, $query)){
    echo "Tabela de avaliações criada com sucesso.<br>";
  } else {
    echo "Erro ao criar tabela de avaliações: " . mysqli_error($conn) . "<br>";
  }

  mysqli_close($conn);
