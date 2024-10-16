<?php
// Validação
$usuario = isset($_POST['usuario']) ? $_POST['usuario'] : '';
$senha = isset($_POST['senha']) ? $_POST['senha'] : '';
$lembrar = isset($_POST['lembrar']) ? $_POST['lembrar'] : '';

if (empty($usuario) || empty($senha)) {
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

  // Definir o SQL e os parâmetros
  $sql = 'SELECT id_usuario, nome
          FROM usuarios 
          WHERE usuario = ? AND senha = ?';
  $parametros = [$usuario, $senha];

  // Consultar os dados
  $dados_usuario = $banco->consultar($sql, $parametros);

  // Login
  if ($dados_usuario) {
    // Sessão
    session_start();
    date_default_timezone_set('America/Sao_Paulo');
    $_SESSION['logado'] = true;
    $_SESSION['id_usuario'] = $dados_usuario['id_usuario'];
    $_SESSION['nome_usuario'] = $dados_usuario['nome'];
    $_SESSION['data_hora_login'] = date('d/m/Y H:i:s');

    // Definir cookies
    if ($lembrar === 'true') {
      $tempo = time() + (86400); // 1 dia
      setcookie('id_usuario', $dados_usuario['id_usuario'], $tempo, "/");
      setcookie('nome_usuario', $dados_usuario['nome'], $tempo, "/");
    }

    // Login autenticado com suscesso
    $resposta = [
      'codigo' => 2
    ];
    echo json_encode($resposta);
  } else {
    $resposta = [
      'codigo' => 1,
      'mensagem' => 'Usuário ou senha incorretos! Verifique.'
    ];
    echo json_encode($resposta);
  }
} catch (PDOException $erro) {
  $resposta = [
    'codigo' => 1,
    'mensagem' => 'Houve uma excessão no banco de dados: ' . $erro->getMessage()
  ];
  echo json_encode($resposta);
}
