<?php
// Validação
$id = isset($_POST['txt_id']) ? $_POST['txt_id'] : '';
$nome = isset($_POST['txt_nome']) ? $_POST['txt_nome'] : '';
$descricao = isset($_POST['txt_descricao']) ? $_POST['txt_descricao'] : '';
$qtd_total = isset($_POST['txt_qtd_total']) ? $_POST['txt_qtd_total'] : '';
$qtd_disp = isset($_POST['txt_qtd_disp']) ? $_POST['txt_qtd_disp'] : '';

if (empty($nome) || empty($descricao)) {
    $resposta = [
        'codigo' => 1,
        'mensagem' => 'Por favor, preencha todos os campos!'
    ];
    echo json_encode($resposta);
    exit;
}

// Banco de Dados
try {
    include '../class/BancoDeDados.php';
    $banco = new BancoDeDados;

    if ($id == 'NOVO') {
        $sql = 'INSERT INTO equipamentos (nome, descricao, quantidade_total, quantidade_disponivel) VALUES (?, ?, ?, ?)';
        $parametros = [$nome, $descricao, $qtd_total, $qtd_disp];
        $banco->ExecutarComando($sql, $parametros);

        $resposta = [
            'codigo' => 2,
        ];
    } else {
        $sql = 'UPDATE equipamentos SET nome = ?, descricao = ?, quantidade_total = ?, quantidade_disponivel = ? WHERE id_equipamento = ?';
        $parametros = [$nome, $descricao, $qtd_total, $qtd_disp, $id];
        $banco->ExecutarComando($sql, $parametros);

        $resposta = [
            'codigo' => 3,
        ];
    }

    echo json_encode($resposta);
} catch (PDOException $erro) {
    $resposta = [
        'codigo' => 1,
        'mensagem' => 'Houve uma exceção no banco de dados: ' . $erro->getMessage()
    ];
    echo json_encode($resposta);
}
