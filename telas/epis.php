<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1>Cadastro de Equipamentos</h1>
    <button type="button" class="w-20 btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#adicionar_epi">
        <i class="bi bi-plus"></i> Novo
    </button>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Nome</th>
                <th scope="col">Descricão</th>
                <th scope="col">Qtd Total</th>
                <th scope="col">Qtd Disponível</th>
                <th scope="col">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            try {
                include_once 'src/class/BancodeDados.php';
                $banco = new BancodeDados;
                $sql = 'SELECT * FROM equipamentos';
                $dados = $banco->Consultar($sql, [], true);
                if ($dados) {
                    foreach ($dados as $linha) {
                        echo
                        "<tr'>
                            <td>{$linha['id_equipamento']}</td>
                            <td>{$linha['nome']}</td>
                            <td>{$linha['descricao']}</td>
                            <td>{$linha['quantidade_total']}</td>
                            <td>{$linha['quantidade_disponivel']}</td>
                            <td>
                                <a href='index.php?tela=epis&acao=alterar&idEquipamento={$linha['id_equipamento']}' class='btn btn-sm btn-outline-primary'>
                                    <i class='bi bi-pencil'></i>
                                </a>
                                <a href='#' onclick='excluirEquipamento({$linha['id_equipamento']})' class='btn btn-sm btn-outline-danger'>
                                    <i class='bi bi-trash'></i>
                                </a>
                            </td>
                        </tr>";
                    }
                } else {
                    echo
                    "<tr>
                        <td colspan = '6' class='text-center'> Nenhum equipamento cadastrado...</td>
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
<div id="adicionar_epi" class="modal fade" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="form_epi" method="post" action="src/epis/cadastrar_epi.php" enctype="multipart/form-data">
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
                        <label for="txt_descricao" class="form-label">Descricão</label>
                        <input type="text" class="form-control" name="txt_descricao" id="txt_descricao" required style="border-radius: 5px;">
                    </div>
                    <div class="form-group row mb-3">
                        <div class="col-6">
                            <label for="txt_qtd_total" class="form-label">Qtd. Total</label>
                            <input type="number" class="form-control" name="txt_qtd_total" id="txt_qtd_total" required style="border-radius: 5px;">
                        </div>
                        <div class="col-6">
                            <label for="txt_qtd_disp" class="form-label">Qtd. Disponível</label>
                            <input type="number" class="form-control" name="txt_qtd_disp" id="txt_qtd_disp" required style="border-radius: 5px;">
                        </div>
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
    $id_equipamento = isset($_GET['idEquipamento']) ? $_GET['idEquipamento'] : '';
    if (!empty($id_equipamento)) {
        try {
            include_once 'src/class/BancoDeDados.php';
            $banco = new BancodeDados;
            $sql = 'SELECT * FROM equipamentos WHERE id_equipamento = ?';
            $parametros = [$id_equipamento];
            $dados = $banco->consultar($sql, $parametros);

            // Verifica se há dados
            if (!empty($dados)) {
                echo "<script>                    
                    document.addEventListener('DOMContentLoaded', function() {
                        var modalElement = document.getElementById('adicionar_epi');
                        var modal = new bootstrap.Modal(modalElement);
                        modal.show();

                        document.getElementById('txt_id').value        = '{$dados['id_equipamento']}';
                        document.getElementById('txt_nome').value      = '{$dados['nome']}';
                        document.getElementById('txt_descricao').value = '{$dados['descricao']}';
                        document.getElementById('txt_qtd_total').value = '{$dados['quantidade_total']}';
                        document.getElementById('txt_qtd_disp').value  = '{$dados['quantidade_disponivel']}';
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