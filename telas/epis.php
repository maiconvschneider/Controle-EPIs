<div class="container-fluid py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <a href="sistema.php" class="btn btn-outline-primary btn-lg rounded-circle shadow-sm">
      <i class="bi bi-arrow-left"></i>
    </a>
    <h1 class="mb-0">Gestão de Equipamentos</h1>
    <button type="button" class="btn btn-success btn-lg rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#adicionar_epi">
      <i class="bi bi-plus-lg me-2"></i>Novo Equipamento
    </button>
  </div>

  <!-- Estatísticas -->
  <div class="row mb-4">
    <div class="col-md-3">
      <div class="card shadow-sm border-0 mb-3">
        <div class="card-body d-flex align-items-center">
          <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
            <i class="bi bi-tools text-primary" style="font-size: 2rem;"></i>
          </div>
          <div>
            <h6 class="text-muted mb-1">Total de Equipamentos</h6>
            <h4 class="mb-0">
              <?php
              include_once 'src/class/BancodeDados.php';
              $banco = new BancodeDados;

              // Total de equipamentos
              $sql = 'SELECT COUNT(*) AS total 
                      FROM equipamentos
                      WHERE ativo = 1';
              $total = $banco->Consultar($sql, [], true);
              echo $total[0]['total'];
              ?>
            </h4>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm border-0 mb-3">
        <div class="card-body d-flex align-items-center">
          <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
            <i class="bi bi-check-circle-fill text-success" style="font-size: 2rem;"></i>
          </div>
          <div>
            <h6 class="text-muted mb-1">Total Disponível</h6>
            <h4 class="mb-0">
              <?php
              include_once 'src/class/BancodeDados.php';
              $banco = new BancodeDados;

              // soma de equipamentos disponíveis
              $sql = 'SELECT SUM(quantidade_disponivel) AS total 
                      FROM equipamentos
                      WHERE ativo = 1';
              $total = $banco->Consultar($sql, [], true);
              echo $total[0]['total'];
              ?>
            </h4>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm border-0 mb-3">
        <div class="card-body d-flex align-items-center">
          <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
            <i class="bi bi-cash-stack" style="font-size: 2rem;"></i>
          </div>
          <div>
            <h6 class="text-muted mb-1">Total em Empréstimo</h6>
            <h4 class="mb-0">
              <?php
              include_once 'src/class/BancodeDados.php';
              $banco = new BancodeDados;

              // soma de equipamentos em emprestimos
              $sql = 'SELECT SUM(ee.qtd_equipamento) AS total 
                      FROM emprestimo_equipamentos ee
                      JOIN emprestimos e ON e.id_emprestimo = ee.id_emprestimo
                      WHERE e.status = "Pendente" and e.ativo = 1';
              $total = $banco->Consultar($sql, [], true);
              echo $total[0]['total'];
              ?>
            </h4>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm border-0 mb-3">
        <div class="card-body d-flex align-items-center">
          <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
            <i class="bi bi-box-fill text-warning" style="font-size: 2rem;"></i>
          </div>
          <div>
            <h6 class="text-muted mb-1">Total em Estoque</h6>
            <h4 class="mb-0">
              <?php
              include_once 'src/class/BancodeDados.php';
              $banco = new BancodeDados;

              // soma de equipamentos totais
              $sql = 'SELECT SUM(quantidade_total) AS total 
                      FROM equipamentos
                      WHERE ativo = 1';
              $total = $banco->Consultar($sql, [], true);
              echo $total[0]['total'];
              ?>
            </h4>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Tabela de Equipamentos -->
  <div class="card shadow-sm border-0">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th scope="col">ID</th>
              <th scope="col">Nome</th>
              <th scope="col">Descrição</th>
              <th scope="col">Qtd Total</th>
              <th scope="col">Qtd Disponível</th>
              <th scope="col" class="text-end">Ações</th>
            </tr>
          </thead>
          <tbody>
            <?php
            try {
              include_once 'src/class/BancodeDados.php';
              $banco = new BancodeDados;
              $sql = 'SELECT * 
                      FROM equipamentos 
                      WHERE ativo = 1';
              $dados = $banco->Consultar($sql, [], true);
              if ($dados) {
                foreach ($dados as $linha) {
                  echo "
                  <tr>
                    <td>{$linha['id_equipamento']}</td>
                    <td>
                      <div class='d-flex align-items-center'>
                        <div class='rounded-circle bg-light p-2 me-2'>
                          <i class='bi bi-tools'></i>
                        </div>
                        {$linha['nome']}
                      </div>
                    </td>
                    <td>{$linha['descricao']}</td>
                    <td>{$linha['quantidade_total']}</td>
                    <td>
                      <span class='badge rounded-pill bg-" . ($linha['quantidade_disponivel'] > 0 ? 'success' : 'danger') . "'>
                        {$linha['quantidade_disponivel']}
                      </span>
                    </td>
                    <td class='text-end'>
                      <button onclick='gerarCodigoBarras({$linha['id_equipamento']})' class='btn btn-sm btn-outline-info rounded-pill me-1' title='Gerar código de barras'>
                        <i class='bi bi-upc-scan'></i>
                      </button>                      
                      <button onclick=\"ajustarEstoque({$linha['id_equipamento']}, '{$linha['nome']}', {$linha['quantidade_total']})\" class='btn btn-sm btn-outline-warning rounded-pill me-1' title='Ajustar estoque'>
                        <i class='bi bi-box-seam'></i>
                      </button>
                      <button onclick='atualizar({$linha['id_equipamento']})' class='btn btn-sm btn-outline-primary rounded-pill me-1'>
                        <i class='bi bi-pencil'></i>
                      </button>
                      <button onclick='excluir({$linha['id_equipamento']})' class='btn btn-sm btn-outline-danger rounded-pill'>
                        <i class='bi bi-trash'></i>
                      </button>
                    </td>
                  </tr>";
                }
              } else {
                echo "
                <tr>
                  <td colspan='6' class='text-center py-4'>
                    <div class='text-muted'>
                      <i class='bi bi-inbox-fill' style='font-size: 2rem;'></i>
                      <p class='mt-2 mb-0'>Nenhum equipamento cadastrado...</p>
                    </div>
                  </td>
                </tr>";
              }
            } catch (PDOException $erro) {
              echo "<script>
                      alert('{$erro->getMessage()}');
                    </script>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div id="adicionar_epi" class="modal fade" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <form id="form_epi" method="post" enctype="multipart/form-data" onsubmit="return false">
        <div class="modal-header border-0" style="background: linear-gradient(135deg, #435d7d, #4a6da1);">
          <h4 class="modal-title text-white">Equipamento</h4>
          <button type="reset" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-hidden="true"></button>
        </div>
        <div class="modal-body p-4">
          <input type="hidden" name="txt_id" id="txt_id" value="NOVO">

          <div class="mb-3">
            <label for="txt_nome" class="form-label">Nome</label>
            <div class="input-group">
              <span class="input-group-text bg-light border-0">
                <i class="bi bi-tools"></i>
              </span>
              <input type="text" class="form-control border-0 bg-light" name="txt_nome" id="txt_nome">
            </div>
          </div>
          <div class="mb-3">
            <label for="txt_descricao" class="form-label">Descrição</label>
            <div class="input-group">
              <span class="input-group-text bg-light border-0">
                <i class="bi bi-card-text"></i>
              </span>
              <input type="text" class="form-control border-0 bg-light" name="txt_descricao" id="txt_descricao">
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="txt_qtd_total" class="form-label">Quantidade Total</label>
              <div class="input-group">
                <span class="input-group-text bg-light border-0">
                  <i class="bi bi-box"></i>
                </span>
                <input type="number" class="form-control border-0 bg-light" name="txt_qtd_total" id="txt_qtd_total">
              </div>
            </div>
            <div class="col-md-6 mb-3">
              <label for="txt_qtd_disp" class="form-label">Quantidade Disponível</label>
              <div class="input-group">
                <span class="input-group-text bg-light border-0">
                  <i class="bi bi-check-circle"></i>
                </span>
                <input type="number" class="form-control border-0 bg-light" name="txt_qtd_disp" id="txt_qtd_disp">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer border-0 bg-light">
          <button type="reset" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-success rounded-pill px-4" onclick="cadastrar()">Salvar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal para ajustar quantidade -->
<div id="ajuste_estoque" class="modal fade" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <form id="formAjusteEstoque" method="POST" action="src/actions/ajustar_estoque.php">
        <div class="modal-header border-0" style="background: linear-gradient(135deg, #435d7d, #4a6da1);">
          <h4 class="modal-title text-white">Ajuste de Estoque</h4>
          <button type="reset" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-hidden="true"></button>
        </div>
        <div class="modal-body p-4">
          <input type="hidden" id="id_equipamento" name="id_equipamento">

          <div class="mb-3">
            <label for="nome_equipamento" class="form-label">Equipamento</label>
            <div class="input-group">
              <span class="input-group-text bg-light border-0">
                <i class="bi bi-tools"></i>
              </span>
              <input type="text" class="form-control border-0 bg-light" id="nome_equipamento" readonly>
            </div>
          </div>

          <div class="mb-3 row">
            <div class="col-md-6">
              <label for="qtd_atual" class="form-label">Quantidade Atual</label>
              <div class="input-group">
                <span class="input-group-text bg-light border-0">
                  <i class="bi bi-box"></i>
                </span>
                <input type="number" class="form-control border-0 bg-light" id="qtd_atual" readonly>
              </div>
            </div>

            <div class="col-md-6">
              <label for="nova_quantidade" class="form-label">Nova Quantidade</label>
              <div class="input-group">
                <span class="input-group-text bg-light border-0">
                  <i class="bi bi-box-seam"></i>
                </span>
                <input type="number" class="form-control border-0 bg-light" name="nova_quantidade" id="nova_quantidade" required min="0">
              </div>
            </div>
          </div>

        </div>
        <div class="modal-footer border-0 bg-light">
          <button type="reset" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-success rounded-pill px-4" onclick="ajustarQtdEstoque()">Confirmar Ajuste</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal para código de barras -->
<div id="codigo_barras" class="modal fade" data-bs-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Código de Barras</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <img src="" id="img_barras">
    </div>
  </div>
</div>

<!-- JQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
  function ajustarEstoque(id, nome, qtd_total) {
    document.getElementById('id_equipamento').value = id;
    document.getElementById('nome_equipamento').value = nome;
    document.getElementById('qtd_atual').value = qtd_total;

    const modal = new bootstrap.Modal(document.getElementById('ajuste_estoque'));
    modal.show();
  }

  function ajustarQtdEstoque() {
    var idEquipamento = document.getElementById('id_equipamento').value;
    var novaQuantidade = document.getElementById('nova_quantidade').value;

    if (novaQuantidade === '') {
      alert('Por favor, insira a nova quantidade!');
      return;
    }

    if (isNaN(novaQuantidade) || novaQuantidade <= 0) {
      alert('Por favor, insira uma quantidade válida (maior que zero)!');
      return;
    }

    $.ajax({
      type: 'POST',
      dataType: 'json',
      url: 'src/epis/ajustar_estoque_epi.php',
      data: {
        'id_equipamento': idEquipamento,
        'nova_quantidade': novaQuantidade
      },
      success: function(response) {
        if (response.status == 'ok') {
          alert('Ajuste de estoque realizado com sucesso!')
          var modal = bootstrap.Modal.getInstance(document.getElementById('ajuste_estoque'));
          modal.hide();
          window.location.reload();
        } else {
          alert('Erro ao ajustar o estoque: ' + response.mensagem);
        }
      },
      error: function(erro) {
        alert('Ocorreu um erro ao ajustar o estoque: ' + erro);
      }
    });
  }


  // Cadastro de Equipamento
  function cadastrar() {
    var id = document.getElementById('txt_id').value;
    var nome = document.getElementById('txt_nome').value;
    var descricao = document.getElementById('txt_descricao').value;
    var qtd_total = document.getElementById('txt_qtd_total').value;
    var qtd_disp = document.getElementById('txt_qtd_disp').value;

    $.ajax({
      type: 'post',
      dataType: 'json',
      url: 'src/epis/cadastrar_epi.php',
      data: {
        'id': id,
        'nome': nome,
        'descricao': descricao,
        'qtd_total': qtd_total,
        'qtd_disp': qtd_disp
      },
      success: function(retorno) {
        if (retorno['status'] == 'ok') {
          alert('Equipamento cadastrado com sucesso!');
          window.location.reload();
        } else if (retorno['status'] == 'ok_atualizar') {
          alert('Equipamento atualizado com sucesso!');
          window.location.reload();
        } else {
          alert(retorno['mensagem']);
        }
      },
      error: function(erro) {
        alert('Ocorreu um erro na requisição: ' + erro);
      }
    });
  }

  // Atualizar Equipamento
  function atualizar(idEquipamento) {
    $.ajax({
      type: 'post',
      dataType: 'json',
      url: 'src/epis/selecionar_epi.php',
      data: {
        'id_equipamento': idEquipamento
      },
      success: function(retorno) {
        if (retorno['status'] == 'ok') {
          document.getElementById('txt_id').value = retorno['dados']['id_equipamento'];
          document.getElementById('txt_nome').value = retorno['dados']['nome'];
          document.getElementById('txt_descricao').value = retorno['dados']['descricao'];
          document.getElementById('txt_qtd_total').value = retorno['dados']['quantidade_total'];
          document.getElementById('txt_qtd_disp').value = retorno['dados']['quantidade_disponivel'];

          var modal = new bootstrap.Modal(document.getElementById('adicionar_epi'));
          modal.show();
        } else {
          alert(retorno['mensagem']);
        }
      },
      error: function(erro) {
        alert('Ocorreu um erro na requisição: ' + erro);
      }
    });
  }

  // Excluir Equipamento
  function excluir(idEquipamento) {
    var confirmou = confirm('Você tem certeza que deseja excluir este equipamento?');
    if (confirmou) {
      $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'src/epis/excluir_epi.php',
        data: {
          'id_equipamento': idEquipamento
        },
        success: function(retorno) {
          alert(retorno['mensagem']);
          if (retorno['status'] == 'ok') {
            registrarLog('Exclusão de Equipamento - ID: ' + idEquipamento);
            window.location.reload();
          }
        },
        error: function(jqXHR, textStatus, errorThrown) {
          // Mensagem detalhada do erro
          console.error('Erro na requisição AJAX:', {
            status: jqXHR.status, // Código HTTP (ex: 404, 500)
            statusText: jqXHR.statusText, // Texto do status (ex: "Not Found")
            responseText: jqXHR.responseText, // Resposta do servidor (se disponível)
            textStatus: textStatus, // Texto adicional sobre o erro (ex: "parsererror")
            errorThrown: errorThrown // Erro detalhado (ex: "SyntaxError: Unexpected token <")
          });
          alert(`Erro ao tentar excluir o equipamento.\nStatus: ${jqXHR.status} - ${jqXHR.statusText}\nDetalhes: ${errorThrown}`);
        }
      });
    }
  }

  function gerarCodigoBarras(id_equipamento) {
    var barcodeUrl = 'https://www.barcodesinc.com/generator/image.php?code=' + id_equipamento + '&style=197&type=C128B&width=300&height=100&xres=1&font=3';
    $('#img_barras').attr('src', barcodeUrl);
    var modal = new bootstrap.Modal(document.getElementById('codigo_barras'));
    modal.show();
  }

  // Adicionar Logs
  function registrarLog(acao) {
    $.ajax({
      type: 'post',
      dataType: 'json',
      url: 'src/logs.php',
      data: {
        'acao': acao
      },
      error: function(erro) {
        alert('Ocorreu um erro na requisição: ' + erro);
      }
    });
  }
</script>

<script>
  //https://developer.mozilla.org/en-US/docs/Web/API/Document/DOMContentLoaded_event
  document.addEventListener("DOMContentLoaded", function() {
    const btnSalvar = document.querySelector("#btn_salvar");

    function validaQtd() {
      const qtdTotal = parseInt(document.getElementById("txt_qtd_total").value) || 0;
      const qtdDisp = parseInt(document.getElementById("txt_qtd_disp").value) || 0;

      if (qtdDisp > qtdTotal) {
        document.getElementById("txt_qtd_disp").setCustomValidity("A quantidade disponível não pode ser maior que a quantidade total.");
        document.getElementById("txt_qtd_disp").reportValidity();
        btnSalvar.disabled = true;
      } else {
        document.getElementById("txt_qtd_disp").setCustomValidity("");
        btnSalvar.disabled = false;
      }
    }

    // Adiciona eventos para validar quando o valor é alterado
    document.getElementById("txt_qtd_total").addEventListener("input", validaQtd);
    document.getElementById("txt_qtd_disp").addEventListener("input", validaQtd);
  });
</script>