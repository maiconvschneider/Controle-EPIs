<?php
// Validação
$id = isset($_POST['id']) ? $_POST['id'] : '';
$nome = isset($_POST['nome']) ? $_POST['nome'] : '';
$matricula = isset($_POST['matricula']) ? $_POST['matricula'] : '';
$departamento = isset($_POST['departamento']) ? $_POST['departamento'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';

if (empty($nome) || empty($matricula) || empty($departamento) || empty($email)) {
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
        $sql = 'INSERT INTO colaboradores (nome, matricula, departamento, email) VALUES (?, ?, ?, ?)';
        $parametros = [$nome, $matricula, $departamento, $email];
        $banco->ExecutarComando($sql, $parametros);

        $resposta = [
            'codigo' => 2,
        ];
    } else {
        $sql = 'UPDATE colaboradores SET nome = ?, matricula = ?, departamento = ?, email = ? WHERE id_colaborador = ?';
        $parametros = [$nome, $matricula, $departamento, $email, $id];
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
