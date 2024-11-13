<?php
// Validação
$id_emprestimo = isset($_POST['id_emprestimo']) ? $_POST['id_emprestimo'] : '';
if (empty($id_emprestimo)) {
  $resposta = [
    'status' => 'erro',
    'mensagem' => 'O ID do empréstimo está faltando!'
  ];
  echo json_encode($resposta);
  exit;
}

// Banco de dados
try {
  include '../class/BancoDeDados.php';
  $banco = new BancoDeDados;
  $sql = 'UPDATE emprestimos 
          SET ativo = 0 
          WHERE id_emprestimo = ?';
  $parametros = [$id_emprestimo];
  $banco->executarComando($sql, $parametros);

  $resposta = [
    'status' => 'ok',
    'mensagem' => 'Empréstimo removido com sucesso!'
  ];
  echo json_encode($resposta);
} catch (PDOException $erro) {
  $resposta = [
    'status' => 'erro',
    'mensagem' => 'Houve um erro ao tentar remover o empréstimo.'
  ];
  echo json_encode($resposta);
}
