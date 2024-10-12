<?php
// Validação
$id_colaborador = isset($_POST['id_colaborador']) ? $_POST['id_colaborador'] : '';
if (empty($id_colaborador)) {
  $resposta = [
    'codigo' => 1,
    'mensagem' => 'O ID do colaborador está faltando!'
  ];
  echo json_encode($resposta);
  exit;
}

// Banco de Dados
try {
  include '../class/BancoDeDados.php';
  $banco = new BancoDeDados;
  $sql = 'update colaboradores set ativo = 0 WHERE id_colaborador = ?';
  $parametros = [$id_colaborador];
  $banco->ExecutarComando($sql, $parametros);

  $resposta = [
    'codigo' => 2,
    'mensagem' => 'Colaborador removido com sucesso!'
  ];
  echo json_encode($resposta);
} catch (PDOException $erro) {
  $resposta = [
    'codigo' => 1,
    'mensagem' => 'Houve uma exceção no banco de dados: ' . $erro->getMessage()
  ];
  echo json_encode($resposta);
}
