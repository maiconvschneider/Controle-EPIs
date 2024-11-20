<?php
// Validação
$id = isset($_POST['id']) ? $_POST['id'] : '';
$nome = isset($_POST['nome']) ? $_POST['nome'] : '';
$descricao = isset($_POST['descricao']) ? $_POST['descricao'] : '';

if (empty($nome)) {
  $resposta = [
    'status' => 'erro',
    'mensagem' => 'Por favor, preencha o nome do departamento!'
  ];
  echo json_encode($resposta);
  exit;
}

// Banco de Dados
try {
  include '../class/BancoDeDados.php';
  $banco = new BancoDeDados;

  if ($id == 'NOVO') {
    $sql = 'INSERT INTO departamentos (nome, descricao) VALUES (?, ?)';
    $parametros = [$nome, $descricao];
    $banco->ExecutarComando($sql, $parametros);

    $resposta = [
      'status' => 'ok',
    ];
  } else {
    $sql = 'UPDATE departamentos SET nome = ?, descricao = ? WHERE id_departamento = ?';
    $parametros = [$nome, $descricao, $id];
    $banco->ExecutarComando($sql, $parametros);

    $resposta = [
      'status' => 'ok_atualizar',
    ];
  }

  echo json_encode($resposta);
} catch (PDOException $erro) {
  $resposta = [
    'status' => 'erro',
    'mensagem' => 'Houve uma exceção no banco de dados: ' . $erro->getMessage()
  ];
  echo json_encode($resposta);
}
