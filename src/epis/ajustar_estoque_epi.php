<?php
session_start();
$id_equipamento = isset($_POST['id_equipamento']) ? $_POST['id_equipamento'] : '';
$nova_quantidade = isset($_POST['nova_quantidade']) ? $_POST['nova_quantidade'] : '';

if (empty($id_equipamento) || empty($nova_quantidade)) {
  $resposta = [
    'status' => 'erro',
    'mensagem' => 'Por favor, preencha todos os campos!'
  ];
  echo json_encode($resposta);
  exit;
}

try {
  include '../class/BancoDeDados.php';
  $banco = new BancodeDados;

  $sql = 'SELECT quantidade_total, quantidade_disponivel 
          FROM equipamentos 
          WHERE id_equipamento = :id';
  $dados = $banco->Consultar($sql, [':id' => $id_equipamento], true);

  if (!$dados) {
    $resposta = [
      'status' => 'erro',
      'mensagem' => 'Equipamento não encontrado!'
    ];
    echo json_encode($resposta);
    exit;
  }

  $qtd_total = $dados[0]['quantidade_total'];
  $qtd_disponivel = $dados[0]['quantidade_disponivel'];

  $qtd_emprestada = $qtd_total - $qtd_disponivel;
  $nova_qtd_disponivel = max(0, $nova_quantidade - $qtd_emprestada);

  $sql = 'UPDATE equipamentos SET 
            quantidade_total = :nova_quantidade,
            quantidade_disponivel = :nova_disponivel 
            WHERE id_equipamento = :id';
  $params = [
    ':nova_quantidade' => $nova_quantidade,
    ':nova_disponivel' => $nova_qtd_disponivel,
    ':id' => $id_equipamento
  ];

  $banco->ExecutarComando($sql, $params);

  // gravar log
  $sql = 'INSERT INTO logs (id_usuario, acao) 
          VALUES (?, ?)';
  $acao = "Ajuste de estoque para o equipamento ID $id_equipamento. Quantidade anterior: $qtd_total, Nova quantidade total: $nova_quantidade.";
  $parametros = [$_SESSION['id_usuario'], $acao];
  $banco->ExecutarComando($sql, $parametros);

  $resposta = [
    'status' => 'ok',
    'mensagem' => 'Estoque ajustado com sucesso!'
  ];
  echo json_encode($resposta);
} catch (PDOException $erro) {
  $resposta = [
    'status' => 'erro',
    'mensagem' => 'Houve uma exceção no banco de dados: ' . $erro->getMessage()
  ];
  echo json_encode($resposta);
}
