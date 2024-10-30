<?php
$idEmprestimo = isset($_POST['id_emprestimo']) ? $_POST['id_emprestimo'] : '';

try {
  include_once '../class/BancoDeDados.php';
  $banco = new BancoDeDados;

  // Primeiro, verifica o status do empréstimo
  $sql = "SELECT status 
          FROM emprestimos 
          WHERE id_emprestimo = ?";
  $parametros = [$idEmprestimo];
  $resultado = $banco->Consultar($sql, $parametros);

  if ($resultado) {
    $status = $resultado['status'];

    if ($status == 'Devolvido') {
      $retorno = [
        'status' => 'erro',
        'mensagem' => 'Este empréstimo já foi devolvido.'
      ];
      echo json_encode($retorno);
      exit;
    }

    $sql = "UPDATE emprestimos 
            SET status = 'Devolvido', data_devolucao = NOW() 
            WHERE id_emprestimo = ?";
    $parametros = [$idEmprestimo];
    $banco->ExecutarComando($sql, $parametros);

    $sql = "SELECT id_equipamento, qtd_equipamento 
            FROM emprestimo_equipamentos 
            WHERE id_emprestimo = ?";
    $equipamentos = $banco->Consultar($sql, [$idEmprestimo]);

    foreach ($equipamentos as $equipamento) {
      $sql = "UPDATE equipamentos 
              SET quantidade_disponivel = quantidade_disponivel + ? 
              WHERE id_equipamento = ?";
      $parametros = [$equipamento['qtd_equipamento'], $equipamento['id_equipamento']];
      $banco->ExecutarComando($sql, $parametros);
    }

    $retorno = [
      'status' => 'sucesso',
      'mensagem' => 'Empréstimo devolvido com sucesso!'
    ];
  } else {
    $retorno = [
      'status' => 'erro',
      'mensagem' => 'Empréstimo não encontrado.'
    ];
  }
} catch (PDOException $erro) {
  $retorno = [
    'status' => 'erro',
    'mensagem' => 'Erro ao registrar devolução: ' . $erro->getMessage()
  ];
}

echo json_encode($retorno);
