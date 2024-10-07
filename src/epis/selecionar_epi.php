<?php
// Validação
$id_equipamento = isset($_POST['id_equipamento']) ? $_POST['id_equipamento'] : '';
if (empty($id_equipamento)) {
  $resposta = [
    'codigo' => 1,
    'mensagem' => 'O ID do equipamento está faltando!'
  ];
  echo json_encode($resposta);
  exit;
}

try {
  include '../class/BancoDeDados.php';
  $banco = new BancoDeDados;
  $sql = 'SELECT * FROM equipamentos WHERE id_equipamento = ?';
  $parametros = [$id_equipamento];
  $dados = $banco->consultar($sql, $parametros);

  $resposta = [
    'codigo' => 2,
    'dados' => $dados
  ];
  echo json_encode($resposta);
} catch (PDOException $erro) {
  $resposta = [
    'codigo' => 1,
    'mensagem' => 'Houve uma exceção no banco de dados: ' . $erro->getMessage()
  ];
  echo json_encode($resposta);
}
