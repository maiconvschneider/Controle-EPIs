<?php
$id_colaborador = isset($_GET['idColaborador']) ? $_GET['idColaborador'] : '';
if (empty($id_colaborador)) {
    header('LOCATION: ../../index.php?tela=colaboradores');
}
try {
    include '../class/BancodeDados.php';
    $banco = new BancodeDados;
    $sql = 'DELETE FROM colaboradores WHERE id_colaborador = ?';
    $parametros = [$id_colaborador];
    $banco->ExecutarComando($sql, $parametros);
    echo
    "<script>
        alert('Colaborador removido com sucesso!');
        window.location = '../../index.php?tela=colaboradores';
    </script>";
} catch (PDOException $erro) {
    $msg = $erro->getMessage();
    echo
    "<script>
        alert(\"$msg\");
        window.location = '../../index.php?tela=colaboradores';
    </script>";
}
