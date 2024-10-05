<?php
// Validação
$id_usuario = isset($_POST['id_usuario']) ? $_POST['id_usuario'] : '';
if (empty($id_usuario)) {
    $resposta = [
        'codigo' => 1,
        'mensagem' => 'O ID do usuário está faltando!'
    ];
    echo json_encode($resposta);
    exit;
}

// Banco de dados
try {
    include '../class/BancoDeDados.php';
    $banco = new BancoDeDados;
    $sql = 'DELETE FROM usuarios WHERE id_usuario = ?';
    $parametros = [$id_usuario];
    $banco->executarComando($sql, $parametros);

    $resposta = [
        'codigo' => 2,
        'mensagem' => 'Usuário removido com sucesso!'
    ];
    echo json_encode($resposta);
} catch (PDOException $erro) {
    $resposta = [
        'codigo' => 1,
        'mensagem' => 'Houve um erro ao tentar remover o usuário.'
    ];
    echo json_encode($resposta);
}
