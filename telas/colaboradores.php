<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1>Cadastro de Colaboradores</h1>
</div>
<form action="src/cadastrar_usuario.php" method="post">
    <div class="row g-4">
        <div class=col-sm-1>
            <label for="txt_id" class="form-label">ID:</label>
            <input type="text" class="form-control" name="txt_id" id="txt_id" value="NOVO" required readonly>
        </div>
        <div class="col-sm-3">
            <label for="txt_nome" class="form-label">Nome:</label>
            <input type="text" class="form-control" name="txt_nome" id="txt_nome" maxlength="255" required>
        </div>
        <div class="col-sm-2">
            <label for="txt_matricula" class="form-label">Matrícula:</label>
            <input type="tex" class="form-control" name="txt_matricula" id="txt_matricula" required>
        </div>
        <div class="col-sm-3">
            <label for="txt_departamento" class="form-label">Departamento:</label>
            <input type="text" class="form-control" name="txt_departamento" id="txt_departamento" required>
        </div>
        <div class="col-sm-3">
            <label for="txt_email" class="form-label">Email:</label>
            <input type="text" class="form-control" name="txt_email" id="txt_email" required>
        </div>
        <div class="col-sm-15">
            <button class="w-100 btn btn-primary btn-lg" type="submit">Salvar</button>
        </div>
</form>
</div>
<hr class="my-4">
<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Nome</th>
                <th scope="col">Matrícula</th>
                <th scope="col">Departamento</th>
                <th scope="col">Email</th>
                <th scope="col">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            try {
                include_once 'src/class/BancodeDados.php';
                $banco = new BancodeDados;
                $sql = 'SELECT * FROM colaboradores';
                $dados = $banco->Consultar($sql, [], true);
                if ($dados) {
                    foreach ($dados as $linha) {
                        echo
                        "<tr'>
                            <td>{$linha['id_colaborador']}</td>
                            <td>{$linha['nome']}</td>
                            <td>{$linha['matricula']}</td>
                            <td>{$linha['departamento']}</td>
                            <td>{$linha['email']}</td>
                            <td>
                                <a href='index.php?tela=colaboradores&idColaborador={$linha['id_colaborador']}' class='btn btn-sm btn-outline-primary'>
                                    <i class='bi bi-pencil'></i>
                                </a>
                                <a href='#' onclick='excluir({$linha['id_colaborador']})' class='btn btn-sm btn-outline-danger'>
                                    <i class='bi bi-trash'></i>
                                </a>
                            </td>
                        </tr>";
                    }
                } else {
                    echo
                    "<tr>
                        <td colspan = '6' class='text-center'> Nenhum colaborador cadastrado...</td>
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