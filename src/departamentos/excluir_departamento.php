<?php
// Validação
$id_departamento = isset($_POST['id_departamento']) ? $_POST['id_departamento'] : '';
if (empty($id_departamento)) {
  $resposta = [
    'status' => 'erro',
    'mensagem' => 'O ID do departamento está faltando!'
  ];
  echo json_encode($resposta);
  exit;
}

// Banco de dados
try {
  include '../class/BancoDeDados.php';
  $banco = new BancoDeDados;
  $sql = 'UPDATE departamentos set ativo = 0 WHERE id_departamento = ?';
  $parametros = [$id_departamento];
  $banco->executarComando($sql, $parametros);

  $resposta = [
    'status' => 'sucesso',
    'mensagem' => 'Departamento removido com sucesso!'
  ];
  echo json_encode($resposta);
} catch (PDOException $erro) {
  $resposta = [
    'status' => 'erro',
    'mensagem' => 'Houve um erro ao tentar remover o departamento.'
  ];
  echo json_encode($resposta);
}
