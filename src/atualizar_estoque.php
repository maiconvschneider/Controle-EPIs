<?php   
    $formulario['id']           = isset($_POST['txt_id']) ? $_POST['txt_id'] : '';
    $formulario['nome']         = isset($_POST['txt_nome']) ? $_POST['txt_nome'] : '';
    $formulario['quantidade']   = isset($_POST['txt_quantidadeadd']) ? $_POST['txt_quantidadeadd'] : '';
    if(in_array('',$formulario))
    {
        echo
        "<script>
            alert('Existem dados faltando! Verifique');
            window.location = '../index.php?tela=produtos.php';
        </script>";
        exit;
    }
    try
    {
        include 'class/BancodeDados.php';
        $banco = new BancodeDados;
        //Verifica qual o estoque atual no banco de dados 
        $sqlqtd = 'SELECT * FROM produtos WHERE id_produto = ?';
        $parametrosqtd = [ $formulario['id']];
        $dados = $banco -> Consultar($sqlqtd,$parametrosqtd,false);
        //Soma a quantidade do banco de dados juntamente com o formulário
        $newqtd = $dados['quantidade'] + $formulario['quantidade'];
        //Atualiza quantidade de estoque no banco de dados 
        $sql = 'UPDATE produtos SET quantidade = ? WHERE id_produto = ?';
        $parametros = 
        [
            $newqtd,
            $formulario['id']
        ];
        $msg_sucesso = 'Estoque atualizado com sucesso!'; 
        $banco ->ExecutarComando($sql, $parametros);
        echo
        "<script>
            alert('$msg_sucesso');
            window.location = '../index.php?tela=produtos';
        </script>";
    }
    catch(PDOException $erro)
    {
        $msg = $erro->getMessage();
        echo
        "<script>
            alert(\"$msg\");
            window.location = '../index.php?tela=produtos';
        </script>";
    }