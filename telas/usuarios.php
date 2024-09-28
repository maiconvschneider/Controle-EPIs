<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1>Cadastro de Usuários</h1>
    <button type="button" class="w-20 btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#adicionar_usuario">
        <i class="bi bi-plus"></i> Novo
    </button>
</div>

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

<!-- Modal -->
<div id="adicionar_usuario" class="modal fade" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="form_usuario" method="post" action="../src/usuario/cadastrar_usuario.php" enctype="multipart/form-data">
                <div class="modal-header" style="background-color: #435d7d; color: #fff;">
                    <h4 class="modal-title">Usuário</h4>
                    <button type="reset" class="btn-close" data-bs-dismiss="modal" aria-hidden="true" style="color: #fff; font-size: 1.2rem; opacity: 0.8;"></button>
                </div>
                <div class="modal-body" style="padding: 20px;">
                    <input type="hidden" name="txt_id" id="txt_id" value="NOVO">

                    <div class="form-group mb-3">
                        <label for="txt_nome" class="form-label">Nome</label>
                        <input type="text" class="form-control" name="txt_nome" id="txt_nome" required style="border-radius: 5px;">
                    </div>
                    <div class="form-group mb-3">
                        <label for="txt_usuario" class="form-label">Usuário</label>
                        <input type="text" class="form-control" name="txt_usuario" id="txt_usuario" required style="border-radius: 5px;">
                    </div>
                    <div class="form-group mb-3">
                        <label for="txt_senha" class="form-label">Senha</label>
                        <input type="password" class="form-control" name="txt_senha" id="txt_senha" required style="border-radius: 5px;">
                    </div>
                    <div class="form-group mb-3">
                        <label for="txt_tipo" class="form-label">Tipo</label>
                        <select class="form-control" name="txt_tipo" id="txt_tipo" required style="border-radius: 5px;">
                            <option value=""></option>
                            <option value="Administrador">Administrador</option>
                            <option value="Usuário">Usuário</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer" style="background-color: #f7f7f7; padding: 15px;">
                    <button type="reset" class="btn" style="border-radius: 5px; padding: 10px 20px;" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success" style="border-radius: 5px; padding: 10px 20px;">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>