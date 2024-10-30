<?php
// Validação
$id_usuario = isset($_POST['id_usuario']) ? $_POST['id_usuario'] : '';
if (empty($id_usuario)) {
  $resposta = [
    'status' => 'erro',
    'mensagem' => 'O ID do usuário está faltando!'
  ];
  echo json_encode($resposta);
  exit;
}

try {
  include '../class/BancoDeDados.php';
  $banco = new BancoDeDados;
  $sql = 'SELECT * FROM usuarios WHERE id_usuario = ?';
  $parametros = [$id_usuario];
  $dados = $banco->consultar($sql, $parametros);

  $resposta = [
    'status' => 'sucesso',
    'dados' => $dados
  ];
  echo json_encode($resposta);
} catch (PDOException $erro) {
  $resposta = [
    'status' => 'erro',
    'mensagem' => 'Houve uma excessão no banco de dados: ' . $erro->getMessage()
  ];
  echo json_encode($resposta);
}
