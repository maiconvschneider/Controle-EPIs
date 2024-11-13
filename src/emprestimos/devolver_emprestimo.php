<?php
$idEmprestimo = isset($_POST['id_emprestimo']) ? $_POST['id_emprestimo'] : '';

if (!$idEmprestimo) {
  echo json_encode([
    'status' => 'erro',
    'mensagem' => 'ID do empréstimo inválido ou não fornecido.'
  ]);
  exit;
}

try {
  include_once '../class/BancoDeDados.php';
  $banco = new BancoDeDados;

  $banco->iniciarTransacao();

  // Verifica o status do empréstimo
  $sql = 'SELECT status, data_devolucao 
            FROM emprestimos 
            WHERE id_emprestimo = ?';
  $parametros = [$idEmprestimo];
  $resultado = $banco->Consultar($sql, $parametros);

  if (!$resultado) {
    throw new Exception('Empréstimo não encontrado.');
  }

  if ($resultado['status'] === 'Devolvido') {
    throw new Exception('Este empréstimo já foi devolvido.');
  }

  // Atualiza o status do empréstimo
  $sql =
  "UPDATE emprestimos 
          SET status = 'Devolvido', 
              data_devolucao = NOW() 
          WHERE id_emprestimo = ?";
  $banco->ExecutarComando($sql, [$idEmprestimo]);

  // Recupera todos os equipamentos do empréstimo
  $sql = 'SELECT id_equipamento, qtd_equipamento 
            FROM emprestimo_equipamentos 
            WHERE id_emprestimo = ?';
  $equipamentos = $banco->Consultar($sql, [$idEmprestimo], true);

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

  $banco->confirmarTransacao();

  echo json_encode([
    'status' => 'ok',
    'mensagem' => 'Empréstimo devolvido com sucesso!'
  ]);
} catch (Exception $erro) {
  if (isset($banco)) {
    $banco->reverterTransacao();
  }

  echo json_encode([
    'status' => 'erro',
    'mensagem' => $erro->getMessage()
  ]);
} finally {
  if (isset($banco)) {
    $banco->fecharConexao();
  }
}