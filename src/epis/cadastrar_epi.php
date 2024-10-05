<?php
$formulario['id']         = isset($_POST['txt_id']) ? $_POST['txt_id'] : '';
$formulario['nome']       = isset($_POST['txt_nome']) ? $_POST['txt_nome'] : '';
$formulario['descricao']  = isset($_POST['txt_descricao']) ? $_POST['txt_descricao'] : '';
$formulario['qtd_total']  = isset($_POST['txt_qtd_total']) ? $_POST['txt_qtd_total'] : '';
$formulario['qtd_disp']   = isset($_POST['txt_qtd_disp']) ? $_POST['txt_qtd_disp'] : '';

if (in_array('', $formulario)) {
    echo
    "<script>
        alert('Existem dados faltando! Verifique');
        window.location = '../sistema.php?tela=equipamentos.php';
    </script>";
    exit;
}
try {
    include '../class/BancodeDados.php';
    $banco = new BancodeDados;
    if ($formulario['id'] == 'NOVO') {
        $sql = 'INSERT INTO equipamentos (nome, descricao, quantidade_total, quantidade_disponivel) VALUES (?,?,?,?)';
        $parametros =
            [
                $formulario['nome'],
                $formulario['descricao'],
                $formulario['qtd_total'],
                $formulario['qtd_disp']
            ];
        $msg_sucesso = 'Dados cadastrados com sucesso!';
    } else {
        $sql = 'UPDATE equipamentos SET nome = ?, descricao = ?, quantidade_total = ?, quantidade_disponivel = ? WHERE id_equipamento = ?';
        $parametros =
            [
                $formulario['nome'],
                $formulario['descricao'],
                $formulario['qtd_total'],
                $formulario['qtd_disp'],
                $formulario['id']
            ];
        $msg_sucesso = 'Dados alterados com sucesso!';
    }
    $banco->ExecutarComando($sql, $parametros);
    echo
    "<script>
        alert('$msg_sucesso');
        window.location = '../../sistema.php?tela=epis';
    </script>";
} catch (PDOException $erro) {
    $msg = $erro->getMessage();
    echo
    "<script>
        alert(\"$msg\");
        window.location = '../../sistema.php?tela=epis';
    </script>";
}
