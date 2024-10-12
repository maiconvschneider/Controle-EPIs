<?php
// Validação
$id_departamento = isset($_POST['id_departamento']) ? $_POST['id_departamento'] : '';
if (empty($id_departamento)) {
  $resposta = [
    'codigo' => 1,
    'mensagem' => 'O ID do departamento está faltando!'
  ];
  echo json_encode($resposta);
  exit;
}

try {
  include '../class/BancoDeDados.php';
  $banco = new BancoDeDados;
  $sql = 'SELECT * FROM departamentos WHERE id_departamento = ?';
  $parametros = [$id_departamento];
  $dados = $banco->consultar($sql, $parametros);

  $resposta = [
    'codigo' => 2,
    'dados' => $dados
  ];
  echo json_encode($resposta);
} catch (PDOException $erro) {
  $resposta = [
    'codigo' => 1,
    'mensagem' => 'Houve uma excessão no banco de dados: ' . $erro->getMessage()
  ];
  echo json_encode($resposta);
}
