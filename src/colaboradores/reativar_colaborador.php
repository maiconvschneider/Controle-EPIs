<?php
// Validação
$id_colaborador = isset($_POST['id_colaborador']) ? $_POST['id_colaborador'] : '';

if (empty($id_colaborador)) {
  $resposta = [
    'status' => 'erro',
    'mensagem' => 'O ID do colaborador está faltando!'
  ];
  echo json_encode($resposta);
  exit;
}

// Banco de Dados
try {
  include '../class/BancoDeDados.php';
  $banco = new BancoDeDados;
  $sql = 'UPDATE colaboradores 
          SET ativo = 1 
          WHERE id_colaborador = ?';
  $parametros = [$id_colaborador];
  $banco->ExecutarComando($sql, $parametros);

  $resposta = [
    'status' => 'ok',
    'mensagem' => 'Colaborador reativado com sucesso!'
  ];
  echo json_encode($resposta);
} catch (PDOException $erro) {
  $resposta = [
    'status' => 'erro',
    'mensagem' => 'Houve uma exceção no banco de dados: ' . $erro->getMessage()
  ];
  echo json_encode($resposta);
}
