<?php
// Validação
$id = isset($_POST['id']) ? $_POST['id'] : '';
$colaborador = isset($_POST['colaborador']) ? $_POST['colaborador'] : '';
$data_emprestimo = isset($_POST['data_emprestimo']) ? $_POST['data_emprestimo'] : '';
$status = isset($_POST['status']) ? $_POST['status'] : '';

if (empty($colaborador) || empty($data_emprestimo) || empty($status)) {
  $resposta = [
    'codigo' => 1,
    'mensagem' => 'Por favor, preencha todos os campos e adicione ao menos um equipamento!'
  ];
  echo json_encode($resposta);
  exit;
}

// Banco de Dados
try {
  include '../class/BancoDeDados.php';
  $banco = new BancoDeDados();

  if ($id == 'NOVO') {
    $sql = 'INSERT INTO emprestimos (id_colaborador, id_usuario, data_emprestimo, status) 
            VALUES (?, ?, ?, ?)';
    $parametros = [$colaborador, $_COOKIE['id_usuario'], $data_emprestimo, $status];
    $banco->ExecutarComando($sql, $parametros);

    $id_emprestimo = $banco->conexao->lastInsertId();

    // Inserir cada equipamento relacionado ao empréstimo
    foreach ($_POST['equipamentos'] as $equipamento) {
      $id_equipamento = $equipamento['id_equipamento'];
      $qtd_equipamento = $equipamento['qtd_equipamento'];

      // Inserir na tabela emprestimo_equipamentos
      $sql = 'INSERT INTO emprestimo_equipamentos (id_emprestimo, id_equipamento, qtd_equipamento) 
              VALUES (?, ?, ?)';
      $parametros = [$id_emprestimo, $id_equipamento, $qtd_equipamento];
      $banco->ExecutarComando($sql, $parametros);

      $sql = 'UPDATE equipamentos 
              SET quantidade_disponivel = quantidade_disponivel - ? 
              WHERE id_equipamento = ?';
      $parametros = [$qtd_equipamento, $id_equipamento];
      $banco->ExecutarComando($sql, $parametros);
    }

    $resposta = [
      'codigo' => 2,
      'mensagem' => 'Empréstimo realizado com sucesso!'
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
