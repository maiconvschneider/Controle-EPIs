<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1>Cadastro de Colaboradores</h1>
    <button type="button" class="w-20 btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#adicionar_colaborador">
        <i class="bi bi-plus"></i> Novo
    </button>
</div>

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
                                <a href='sistema.php?tela=colaboradores&acao=alterar&idColaborador={$linha['id_colaborador']}' class='btn btn-sm btn-outline-primary'>
                                    <i class='bi bi-pencil'></i>
                                </a>
                                <a href='#' onclick='excluirColaborador({$linha['id_colaborador']})' class='btn btn-sm btn-outline-danger'>
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

<!-- Modal -->
<div id="adicionar_colaborador" class="modal fade" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="form_colaborador" method="post" action="src/colaboradores/cadastrar_colaborador.php" enctype="multipart/form-data">
                <div class="modal-header" style="background-color: #435d7d; color: #fff;">
                    <h4 class="modal-title">Colaborador</h4>
                    <button type="reset" class="btn-close" data-bs-dismiss="modal" aria-hidden="true" style="color: #fff; font-size: 1.2rem; opacity: 0.8;"></button>
                </div>
                <div class="modal-body" style="padding: 20px;">
                    <input type="hidden" name="txt_id" id="txt_id" value="NOVO">

                    <div class="form-group mb-3">
                        <label for="txt_nome" class="form-label">Nome</label>
                        <input type="text" class="form-control" name="txt_nome" id="txt_nome" required style="border-radius: 5px;">
                    </div>
                    <div class="form-group mb-3">
                        <label for="txt_matricula" class="form-label">Matrícula</label>
                        <input type="text" class="form-control" name="txt_matricula" id="txt_matricula" required style="border-radius: 5px;">
                    </div>
                    <div class="form-group mb-3">
                        <label for="txt_departamento" class="form-label">Departamento</label>
                        <input type="text" class="form-control" name="txt_departamento" id="txt_departamento" required style="border-radius: 5px;">
                    </div>
                    <div class="form-group mb-3">
                        <label for="txt_email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="txt_email" id="txt_email" required style="border-radius: 5px;">
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

<?php
if (isset($_GET['acao']) && $_GET['acao'] == 'alterar') {
    $id_colaborador = isset($_GET['idColaborador']) ? $_GET['idColaborador'] : '';
    if (!empty($id_colaborador)) {
        try {
            include_once 'src/class/BancoDeDados.php';
            $banco = new BancodeDados;
            $sql = 'SELECT * FROM colaboradores WHERE id_colaborador = ?';
            $parametros = [$id_colaborador];
            $dados = $banco->consultar($sql, $parametros);

            // Verifica se há dados
            if (!empty($dados)) {
                echo "<script>                    
                    document.addEventListener('DOMContentLoaded', function() {
                        var modalElement = document.getElementById('adicionar_colaborador');
                        var modal = new bootstrap.Modal(modalElement);
                        modal.show();

                        document.getElementById('txt_id').value           = '{$dados['id_colaborador']}';
                        document.getElementById('txt_nome').value         = '{$dados['nome']}';
                        document.getElementById('txt_matricula').value    = '{$dados['matricula']}';
                        document.getElementById('txt_departamento').value = '{$dados['departamento']}';
                        document.getElementById('txt_email').value        = '{$dados['email']}';
                    });
                </script>";
            } else {
                echo "<script>alert('Dados não encontrados.');</script>";
            }
        } catch (PDOException $erro) {
            echo "<script>alert('Erro: " . htmlspecialchars($erro->getMessage(), ENT_QUOTES) . "');</script>";
        }
    }
}
?>