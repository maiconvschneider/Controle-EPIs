<?php
// Validação
$id = isset($_POST['id']) ? $_POST['id'] : '';
$nome = isset($_POST['nome']) ? $_POST['nome'] : '';
$descricao = isset($_POST['descricao']) ? $_POST['descricao'] : '';

if (empty($nome) || empty($descricao)) {
  $resposta = [
    'codigo' => 1,
    'mensagem' => 'Por favor, preencha todos os campos!'
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
      'codigo' => 2,
      'mensagem' => 'Departamento cadastrado com sucesso!'
    ];
  } else {
    $sql = 'UPDATE departamentos SET nome = ?, descricao = ? WHERE id_departamento = ?';
    $parametros = [$nome, $descricao, $id];
    $banco->ExecutarComando($sql, $parametros);

    $resposta = [
      'codigo' => 3,
      'mensagem' => 'Departamento atualizado com sucesso!'
    ];
  }

  echo json_encode($resposta);
} catch (PDOException $erro) {
  $resposta = [
    'codigo' => 1,
    'mensagem' => 'Houve uma exceção no banco de dados: ' . $erro->getMessage()
  ];
  echo json_encode($resposta);
}
