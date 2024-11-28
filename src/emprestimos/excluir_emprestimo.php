<?php
$id_emprestimo = isset($_POST['id_emprestimo']) ? $_POST['id_emprestimo'] : '';
if (empty($id_emprestimo)) {
  $resposta = [
    'status' => 'erro',
    'mensagem' => 'O ID do empréstimo está faltando!'
  ];
  echo json_encode($resposta);
  exit;
}

try {
  include '../class/BancoDeDados.php';
  $banco = new BancoDeDados;

  $sql = 'SELECT status 
          FROM emprestimos 
          WHERE id_emprestimo = ?';
  $resultado = $banco->Consultar($sql, [$id_emprestimo]);

  if (!$resultado) {
    $resposta = [
      'status' => 'erro',
      'mensagem' => 'Empréstimo não encontrado!'
    ];
    echo json_encode($resposta);
    exit;
  }

  // Verificar se o empréstimo está pendente
  if ($resultado['status'] === 'Pendente') {
    $sql_update_emprestimo = 'UPDATE emprestimos 
                               SET status = "Devolvido", 
                                   data_devolucao = NOW() 
                               WHERE id_emprestimo = ?';
    $banco->executarComando($sql_update_emprestimo, [$id_emprestimo]);

    $sql = 'SELECT id_equipamento, qtd_equipamento 
            FROM emprestimo_equipamentos 
            WHERE id_emprestimo = ?';
    $equipamentos = $banco->Consultar($sql, [$id_emprestimo], true);

    if (!$equipamentos) {
      throw new Exception('Nenhum equipamento encontrado para este empréstimo.');
    }

    // Atualiza o estoque de cada equipamento
    foreach ($equipamentos as $equipamento) {
      $sql = 'UPDATE equipamentos 
                SET quantidade_disponivel = quantidade_disponivel + ? 
                WHERE id_equipamento = ?';
      $parametros = [$equipamento['qtd_equipamento'], $equipamento['id_equipamento']];
      $resultado = $banco->ExecutarComando($sql, $parametros);

      if (!$resultado) {
        throw new Exception('Erro ao atualizar o estoque do equipamento ID: ' . $equipamento['id_equipamento']);
      }
    }
  }

  // Desativar o empréstimo
  $sql_desativar = 'UPDATE emprestimos 
                    SET ativo = 0 
                    WHERE id_emprestimo = ?';
  $banco->executarComando($sql_desativar, [$id_emprestimo]);

  $resposta = [
    'status' => 'ok',
    'mensagem' => 'Empréstimo removido com sucesso!'
  ];
  echo json_encode($resposta);
} catch (PDOException $erro) {
  $resposta = [
    'status' => 'erro',
    'mensagem' => 'Houve um erro ao tentar remover o empréstimo: ' . $erro->getMessage()
  ];
  echo json_encode($resposta);
}
