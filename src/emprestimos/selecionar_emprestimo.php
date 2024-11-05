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

try {
  include '../class/BancoDeDados.php';
  $banco = new BancoDeDados;

  // SQL para buscar os dados do empréstimo e equipamentos
  $sql = 'SELECT e.id_emprestimo, e.id_colaborador, e.id_usuario, e.data_devolucao, e.data_emprestimo, e.status,
                 ee.id_equipamento, eq.nome nome_equipamento, ee.qtd_equipamento,
                 e.id_colaborador, c.nome nome_colaborador
            FROM emprestimos e
            JOIN emprestimo_equipamentos ee on ee.id_emprestimo = e.id_emprestimo
            JOIN colaboradores c on c.id_colaborador = e.id_colaborador
            JOIN equipamentos eq on eq.id_equipamento = ee.id_equipamento
            WHERE e.id_emprestimo = ?';
  $parametros = [$id_emprestimo];
  $dados = $banco->consultar($sql, $parametros);

  if ($dados) {
    // Processando a resposta para separar os dados de equipamentos
    $emprestimo = [
      'id_emprestimo' => $dados[0]['id_emprestimo'],
      'id_colaborador' => $dados[0]['id_colaborador'],
      'nome_colaborador' => $dados[0]['nome_colaborador'],
      'id_usuario' => $dados[0]['id_usuario'],
      'data_emprestimo' => explode(' ', $dados[0]['data_emprestimo'])[0],
      'data_devolucao' => $dados[0]['data_devolucao'] ? explode(' ', $dados[0]['data_devolucao'])[0] : null,
      'status' => $dados[0]['status']
    ];

    // listando equipamentos
    $equipamentos = [];
    foreach ($dados as $linha) {
      $equipamentos[] = [
        'id_equipamento' => $linha['id_equipamento'],
        'nome_equipamento' => $linha['nome_equipamento'],
        'qtd_equipamento' => $linha['qtd_equipamento']
      ];
    }

    // Retornando a resposta organizada
    $resposta = [
      'status' => 'ok',
      'dados' => $emprestimo,
      'equipamentos' => $equipamentos
    ];
  } else {
    $resposta = [
      'status' => 'erro',
      'mensagem' => 'Empréstimo não encontrado.'
    ];
  }

  echo json_encode($resposta);
} catch (PDOException $erro) {
  $resposta = [
    'status' => 'erro',
    'mensagem' => 'Houve uma exceção no banco de dados: ' . $erro->getMessage()
  ];
  echo json_encode($resposta);
}
