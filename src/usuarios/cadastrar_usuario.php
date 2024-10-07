<?php
// Validação
$id = isset($_POST['id']) ? $_POST['id'] : '';
$nome = isset($_POST['nome']) ? $_POST['nome'] : '';
$usuario = isset($_POST['usuario']) ? $_POST['usuario'] : '';
$senha = isset($_POST['senha']) ? $_POST['senha'] : '';
$tipo = isset($_POST['tipo']) ? $_POST['tipo'] : 'U';

if (empty($nome) || empty($usuario) || empty($senha)) {
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
    $sql = 'INSERT INTO usuarios (nome, usuario, senha, tipo) VALUES (?, ?, ?, ?)';
    $parametros = [$nome, $usuario, $senha, $tipo];
    $banco->ExecutarComando($sql, $parametros);

    $resposta = [
      'codigo' => 2,
    ];
  } else {
    $sql = 'UPDATE usuarios SET nome = ?, usuario = ?, senha = ?, tipo = ? WHERE id_usuario = ?';
    $parametros = [$nome, $usuario, $senha, $tipo, $id];
    $banco->ExecutarComando($sql, $parametros);

    $resposta = [
      'codigo' => 3,
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
