<?php
$id_equipamento = isset($_POST['id_equipamento']) ? $_POST['id_equipamento'] : '';
if (empty($id_equipamento)) {
  $resposta = [
    'status' => 'erro',
    'mensagem' => 'O ID do equipamento está faltando!'
  ];
  echo json_encode($resposta);
  exit;
}

// Banco de Dados
try {
  include '../class/BancoDeDados.php';
  $banco = new BancoDeDados;

  $sql = 'SELECT SUM(ee.qtd_equipamento) AS total 
            FROM emprestimo_equipamentos ee
            JOIN emprestimos e ON e.id_emprestimo = ee.id_emprestimo
            WHERE e.status = "Pendente" 
              AND e.ativo = 1 
              AND ee.id_equipamento = ?';
  $parametros = [$id_equipamento];
  $dados = $banco->consultar($sql, $parametros);

  if ($dados['total'] > 0) {
    $resposta = [
      'status' => 'erro',
      'mensagem' => 'Equipamento não pode ser removido pois ainda possui empréstimos!'
    ];
    echo json_encode($resposta);
    exit;
  }

  $sql = 'UPDATE equipamentos 
          SET ativo = 0
          WHERE id_equipamento = ?';
  $parametros = [$id_equipamento];
  $banco->ExecutarComando($sql, $parametros);

  $resposta = [
    'status' => 'ok',
    'mensagem' => 'Equipamento removido com sucesso!'
  ];
  echo json_encode($resposta);
} catch (PDOException $erro) {
  $resposta = [
    'status' => 'erro',
    'mensagem' => 'Houve uma exceção no banco de dados: ' . $erro->getMessage()
  ];
  echo json_encode($resposta);
}
