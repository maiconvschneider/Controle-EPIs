<?php
session_start();

try {
  include 'class/BancoDeDados.php';
  $banco = new BancoDeDados;

  $sql = 'INSERT INTO logs (id_usuario, acao) VALUES (?, ?)';
  $parametros = [$_SESSION['id_usuario'], $_POST['acao']];
  $banco->ExecutarComando($sql, $parametros);

  $resposta = [
    'status' => 'sucesso',
  ];

  echo json_encode($resposta);
} catch (PDOException $erro) {
  $resposta = [
    'status' => 'erro',
    'mensagem' => 'Houve uma exceção no banco de dados: ' . $erro->getMessage()
  ];
  echo json_encode($resposta);
}
