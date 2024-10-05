<?php
$id_equipamento = isset($_GET['idEquipamento']) ? $_GET['idEquipamento'] : '';
if (empty($id_equipamento)) {
    header('LOCATION: ../../sistema.php?tela=epis');
}
try {
    include '../class/BancodeDados.php';
    $banco = new BancodeDados;
    $sql = 'DELETE FROM equipamentos WHERE id_equipamento = ?';
    $parametros = [$id_equipamento];
    $banco->ExecutarComando($sql, $parametros);
    echo
    "<script>
        alert('Equipamento removido com sucesso!');
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
