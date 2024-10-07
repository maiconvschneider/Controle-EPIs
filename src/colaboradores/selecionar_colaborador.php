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

try {
  include '../class/BancoDeDados.php';
  $banco = new BancoDeDados;
  $sql = 'SELECT * FROM colaboradores WHERE id_colaborador = ?';
  $parametros = [$id_colaborador];
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
