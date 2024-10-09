<?php
// Validação
$id = isset($_POST['id']) ? $_POST['id'] : '';
$colaborador = isset($_POST['colaborador']) ? $_POST['colaborador'] : '';
$data_emprestimo = isset($_POST['data_emprestimo']) ? $_POST['data_emprestimo'] : '';
$status = isset($_POST['status']) ? $_POST['status'] : '';

if (empty($colaborador) || empty($data_emprestimo) || empty($status)) {
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
    $sql = 'INSERT INTO emprestimos (id_colaborador, id_usuario, data_emprestimo, status) VALUES (?, ?, ?, ?)';
    $parametros = [$colaborador, $_SESSION['id_usuario'], $data_emprestimo, $status];
    $banco->ExecutarComando($sql, $parametros);

    $resposta = [
      'codigo' => 2,
      'mensagem' => 'Empréstimo realizado com sucesso!'
    ];
  };

  echo json_encode($resposta);
} catch (PDOException $erro) {
  $resposta = [
    'codigo' => 1,
    'mensagem' => 'Houve uma exceção no banco de dados: ' . $erro->getMessage()
  ];
  echo json_encode($resposta);
}
