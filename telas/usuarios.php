<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1>Cadastro de Usuários</h1>
</div>
<form action="src/usuario/cadastrar_usuario.php" method="post">
    <div class="row g-4">
        <div class=col-sm-1>
            <label for="txt_id" class="form-label">ID:</label>
            <input type="text" class="form-control" name="txt_id" id="txt_id" value="NOVO" required readonly>
        </div>
        <div class="col-sm-3">
            <label for="txt_nome" class="form-label">Nome:</label>
            <input type="text" class="form-control" name="txt_nome" id="txt_nome" maxlength="355" required>
        </div>
        <div class="col-sm-3">
            <label for="txt_usuario" class="form-label">Usuário:</label>
            <input type="text" class="form-control" name="txt_usuario" id="txt_usuario" required>
        </div>
        <div class="col-sm-3">
            <label for="txt_senha" class="form-label">Senha:</label>
            <input type="text" class="form-control" name="txt_senha" id="txt_senha" required>
        </div>
        <div class="col-sm-2">
            <label for="txt_tipo" class="form-label">Tipo:</label>
            <select class="form-control" name="txt_tipo" id="txt_tipo" required>
                <option value=""></option>
                <option value="Administrador">Administrador</option>
                <option value="Usuário">Usuário</option>
            </select>
        </div>
        <div class="col-sm-15">
            <button class="w-100 btn btn-primary btn-lg" type="submit">Salvar</button>
        </div>
    </div>
</form>
<hr class="my-4">
<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Nome</th>
                <th scope="col">Usuário</th>
                <th scope="col">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            try {
                include_once 'src/class/BancodeDados.php';
                $banco = new BancodeDados;
                $sql = 'SELECT * FROM usuarios';
                $dados = $banco->Consultar($sql, [], true);
                if ($dados) {
                    foreach ($dados as $linha) {
                        echo
                        "<tr'>
                            <td>{$linha['id_usuario']}</td>
                            <td>{$linha['nome']}</td>
                            <td>{$linha['usuario']}</td>
                            <td>
                                <a href='index.php?tela=usuarios&idUsuario={$linha['id_usuario']}' class='btn btn-sm btn-outline-primary'>
                                    <i class='bi bi-pencil'></i>
                                </a>
                                <a href='#' onclick='excluirUsuario({$linha['id_usuario']})' class='btn btn-sm btn-outline-danger'>
                                    <i class='bi bi-trash'></i>
                                </a>
                            </td>
                        </tr>";
                    }
                } else {
                    echo
                    "<tr>
                        <td colspan = '6' class='text-center'> Nenhum usuário cadastrado...</td>
                    </tr>";
                }
            } catch (PDOException $erro) {
                $msg = $erro->getMessage();
                echo
                "<script>
                    alert(\"$msg\");
                </script>";
            }
            ?>
        </tbody>
    </table>
</div>