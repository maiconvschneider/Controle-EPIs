<?php
// Validação
$id = isset($_POST['id']) ? $_POST['id'] : '';
$nome = isset($_POST['nome']) ? $_POST['nome'] : '';
$descricao = isset($_POST['descricao']) ? $_POST['descricao'] : '';
$qtd_total = isset($_POST['qtd_total']) ? $_POST['qtd_total'] : '0';
$qtd_disp = isset($_POST['qtd_disp']) ? $_POST['qtd_disp'] : '0';

if (empty($nome)) {
  $resposta = [
    'status' => 'erro',
    'mensagem' => 'Por favor, preencha o nome do equipamento!'
  ];
  echo json_encode($resposta);
  exit;
}

// Banco de Dados
try {
  include '../class/BancoDeDados.php';
  $banco = new BancoDeDados;

  if ($id == 'NOVO') {
    $sql = 'INSERT INTO equipamentos (nome, descricao, quantidade_total, quantidade_disponivel) 
            VALUES (?, ?, ?, ?)';
    $parametros = [$nome, $descricao, $qtd_total, $qtd_disp];
    $banco->ExecutarComando($sql, $parametros);

    $resposta = [
      'status' => 'ok',
    ];
  } else {
    $sql = 'UPDATE equipamentos 
            SET nome = ?, descricao = ?, quantidade_total = ?, quantidade_disponivel = ? 
            WHERE id_equipamento = ?';
    $parametros = [$nome, $descricao, $qtd_total, $qtd_disp, $id];
    $banco->ExecutarComando($sql, $parametros);

    $resposta = [
      'status' => 'ok_atualizar',
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
