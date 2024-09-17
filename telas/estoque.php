<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1>Entrada de Estoque</h1>
</div>
<div class="container my-5">
    <div class="d-flex justify-content-center">
        <div class="col-md-6 col-lg-4"> <!-- Ajusta o tamanho para telas médias e grandes -->
            <form action="src/atualizar_estoque.php" method="post">
                <div class="row g-2">
                    <div class="mb-3">
                        <label for="txt_id" class="form-label">ID:</label>
                        <input type="text" class="form-control" name="txt_id" id="txt_id" required readonly>
                    </div>
                    <div class="mb-3">
                        <label for="txt_nome" class="form-label">Nome:</label>
                        <input type="text" class="form-control" name="txt_nome" id="txt_nome" maxlength="255" required readonly>
                    </div>
                    <div class="mb-3">
                        <label for="txt_quantidadeadd" class="form-label">Quantidade Adicionada:</label>
                        <input type="text" class="form-control" name="txt_quantidadeadd" id="txt_quantidadeadd" maxlength="255" required placeholder="Informe a nova quantidade...">
                    </div>
                    <div class="col-sm-20">
                        <button class="w-100 btn btn-primary btn-lg" type="submit">Salvar</button>
                        <a href="index.php?tela=produtos" class="d-block text-center mt-2">Voltar para a lista de Produtos</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
