<?php
$id_usuario = isset($_GET['idUsuario']) ? $_GET['idUsuario'] : '';
if (empty($id_usuario)) {
    header('LOCATION: ../../index.php?tela=usuarios');
}
try {
    include '../class/BancodeDados.php';
    $banco = new BancodeDados;
    $sql = 'DELETE FROM usuarios WHERE id_usuario = ?';
    $parametros = [$id_usuario];
    $banco->ExecutarComando($sql, $parametros);
    echo
    "<script>
        alert('Usuário removido com sucesso!');
        window.location = '../../index.php?tela=usuarios';
    </script>";
} catch (PDOException $erro) {
    $msg = $erro->getMessage();
    echo
    "<script>
        alert(\"$msg\");
        window.location = '../../index.php?tela=usuarios';
    </script>";
}
