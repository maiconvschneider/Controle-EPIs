<?php
// Validação
$id = isset($_POST['id']) ? $_POST['id'] : '';
$colaborador = isset($_POST['colaborador']) ? $_POST['colaborador'] : '';
$data_emprestimo = isset($_POST['data_emprestimo']) ? $_POST['data_emprestimo'] : '';

if (empty($colaborador) || empty($data_emprestimo)) {
  $resposta = [
    'status' => 'erro',
    'mensagem' => 'Por favor, preencha todos os campos da aba "Informações Gerais"!'
  ];
  echo json_encode($resposta);
  exit;
}

// Banco de Dados
try {
  include '../class/BancoDeDados.php';
  $banco = new BancoDeDados();

  if ($id == 'NOVO') {
    $sql = "INSERT INTO emprestimos (id_colaborador, id_usuario, data_emprestimo, status) 
            VALUES (?, ?, ?, 'Pendente')";
    $parametros = [$colaborador, $_COOKIE['id_usuario'], $data_emprestimo];
    $banco->ExecutarComando($sql, $parametros);

    $id_emprestimo = $banco->conexao->lastInsertId();

    // verifica qtd disponivel de cada equipamento
    foreach ($_POST['equipamentos'] as $equipamento) {
      $id_equipamento = $equipamento['id_equipamento'];
      $qtd_equipamento = $equipamento['qtd_equipamento'];

      // verifica qtd disponivel
      $sql = 'SELECT quantidade_disponivel 
              FROM equipamentos 
              WHERE id_equipamento = ?';
      $resultado = $banco->Consultar($sql, [$id_equipamento]);

      if ($resultado['quantidade_disponivel'] >= $qtd_equipamento) {
        $sql = 'INSERT INTO emprestimo_equipamentos (id_emprestimo, id_equipamento, qtd_equipamento) 
                VALUES (?, ?, ?)';
        $parametros = [$id_emprestimo, $id_equipamento, $qtd_equipamento];
        $banco->ExecutarComando($sql, $parametros);

        // atualiza qtd do item
        $sql = 'UPDATE equipamentos 
                SET quantidade_disponivel = quantidade_disponivel - ? 
                WHERE id_equipamento = ?';
        $parametros = [$qtd_equipamento, $id_equipamento];
        $banco->ExecutarComando($sql, $parametros);
      } else {
        $resposta = [
          'status' => 'erro',
          'mensagem' => "Quantidade disponível insuficiente para o equipamento ID: $id_equipamento"
        ];
        echo json_encode($resposta);
        exit;
      }
    }

    $resposta = [
      'status' => 'ok',
      'mensagem' => 'Empréstimo realizado com sucesso!'
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
