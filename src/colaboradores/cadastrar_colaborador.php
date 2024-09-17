<?php
$formulario['id']           = isset($_POST['txt_id']) ? $_POST['txt_id'] : '';
$formulario['nome']         = isset($_POST['txt_nome']) ? $_POST['txt_nome'] : '';
$formulario['matricula']    = isset($_POST['txt_matricula']) ? $_POST['txt_matricula'] : '';
$formulario['departamento'] = isset($_POST['txt_departamento']) ? $_POST['txt_departamento'] : '';
$formulario['email']        = isset($_POST['txt_email']) ? $_POST['txt_email'] : '';
if (in_array('', $formulario)) {
    echo
    "<script>
        alert('Existem dados faltando! Verifique');
        window.location = '../index.php?tela=colaboradores.php';
    </script>";
    exit;
}
try {
    include 'class/BancodeDados.php';
    $banco = new BancodeDados;
    if ($formulario['id'] == 'NOVO') {
        $sql = 'INSERT INTO  colaboradores (nome, matricula, departamento) VALUES (?,?,?)';
        $parametros =
            [
                $formulario['nome'],
                $formulario['matricula'],
                $formulario['departamento'],
                $formulario['email']
            ];
        $msg_sucesso = 'Dados cadastrados com sucesso!';
    } else {
        $sql = 'UPDATE colaboradores SET nome = ?, matricula = ?, departamento = ?, email = ?  WHERE id_colaborador = ?';
        $parametros =
            [
                $formulario['nome'],
                $formulario['matricula'],
                $formulario['departamento'],
                $formulario['email'],
                $formulario['id']
            ];
        $msg_sucesso = 'Dados alterados com sucesso!';
    }
    $banco->ExecutarComando($sql, $parametros);
    echo
    "<script>
            alert('$msg_sucesso');
            window.location = '../index.php?tela=colaboradores';
        </script>";
} catch (PDOException $erro) {
    $msg = $erro->getMessage();
    echo
    "<script>
            alert(\"$msg\");
            window.location = '../index.php?tela=colaboradores';
        </script>";
}
