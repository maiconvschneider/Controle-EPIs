<?php
// Validação
$id_emprestimo = isset($_POST['id_emprestimo']) ? $_POST['id_emprestimo'] : '';
if (empty($id_emprestimo)) {
  $resposta = [
    'codigo' => 1,
    'mensagem' => 'O ID do empréstimo está faltando!'
  ];
  echo json_encode($resposta);
  exit;
}

// Banco de dados
try {
  include '../class/BancoDeDados.php';
  $banco = new BancoDeDados;
  $sql = 'DELETE FROM emprestimos WHERE id_emprestimo = ?';
  $parametros = [$id_emprestimo];
  $banco->executarComando($sql, $parametros);

  $resposta = [
    'codigo' => 2,
    'mensagem' => 'Empréstimo removido com sucesso!'
  ];
  echo json_encode($resposta);
} catch (PDOException $erro) {
  $resposta = [
    'codigo' => 1,
    'mensagem' => 'Houve um erro ao tentar remover o usuário.'
  ];
  echo json_encode($resposta);
}
