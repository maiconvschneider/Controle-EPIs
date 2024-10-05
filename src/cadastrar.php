<?php
// Validação
$nome = isset($_POST['nome']) ? $_POST['nome'] : '';
$usuario = isset($_POST['usuario']) ? $_POST['usuario'] : '';
$senha = isset($_POST['senha']) ? $_POST['senha'] : '';

if (empty($nome) || empty($usuario) || empty($senha)) {
  $resposta = [
    'codigo' => 1,
    'mensagem' => 'Por favor preencha todos os campos!'
  ];
  echo json_encode($resposta);
  exit;
}

// Banco de Dados
try {
  include 'class/BancoDeDados.php';
  $banco = new BancoDeDados;

  $sql = 'INSERT INTO usuarios (nome, usuario, senha, tipo) VALUES (?, ?, ?, ?)';
  $parametros = [$nome, $usuario, $senha, 'U'];

  $banco->ExecutarComando($sql, $parametros);

  $resposta = [
    'codigo' => 2,
    'mensagem' => 'Usuário cadastrado com sucesso!'
  ];
  echo json_encode($resposta);
} catch (PDOException $erro) {
  $resposta = [
    'codigo' => 1,
    'mensagem' => 'Houve uma exceção no banco de dados: ' . $erro->getMessage()
  ];
  echo json_encode($resposta);
}
