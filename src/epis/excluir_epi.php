<?php
// Validação
$id_equipamento = isset($_POST['id_equipamento']) ? $_POST['id_equipamento'] : '';
if (empty($id_equipamento)) {
  $resposta = [
    'status' => 'erro',
    'mensagem' => 'O ID do equipamento está faltando!'
  ];
  echo json_encode($resposta);
  exit;
}

// Banco de Dados
try {
  include '../class/BancoDeDados.php';
  $banco = new BancoDeDados;
  $sql = 'DELETE FROM equipamentos WHERE id_equipamento = ?';
  $parametros = [$id_equipamento];
  $banco->ExecutarComando($sql, $parametros);

  $resposta = [
    'status' => 'sucesso',
    'mensagem' => 'Equipamento removido com sucesso!'
  ];
  echo json_encode($resposta);
} catch (PDOException $erro) {
  $resposta = [
    'status' => 'erro',
    'mensagem' => 'Houve uma exceção no banco de dados: ' . $erro->getMessage()
  ];
  echo json_encode($resposta);
}
