<?php
$idEmprestimo = isset($_POST['id_emprestimo']) ? $_POST['id_emprestimo'] : '';

try {
  include_once '../class/BancodeDados.php';
  $banco = new BancodeDados;

  // Atualiza o status para "Devolvido"
  $sql = "UPDATE emprestimos SET status = 'Devolvido' WHERE id_emprestimo = ?";
  $parametros = [$idEmprestimo];
  $banco->ExecutarComando($sql, $parametros);

  $retorno = [
    'codigo' => 2,
    'mensagem' => 'Empréstimo devolvido com sucesso!'
  ];
} catch (PDOException $erro) {
  $retorno = [
    'codigo' => 1,
    'mensagem' => 'Erro ao registrar devolução: ' . $erro->getMessage()
  ];
}

echo json_encode($retorno);
