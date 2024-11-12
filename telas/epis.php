<div class="container-fluid py-4">
  <!-- Header with back button -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <a href="sistema.php" class="btn btn-outline-primary btn-lg rounded-circle shadow-sm">
      <i class="bi bi-arrow-left"></i>
    </a>
    <h1 class="mb-0">Gestão de Equipamentos</h1>
    <button type="button" class="btn btn-success btn-lg rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#adicionar_epi">
      <i class="bi bi-plus-lg me-2"></i>Novo Equipamento
    </button>
  </div>

  <!-- Statistics cards -->
  <div class="row mb-4">
    <div class="col-md-4">
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
              $sql = 'SELECT COUNT(*) AS total FROM equipamentos';
              $total = $banco->Consultar($sql, [], true);
              echo $total[0]['total'];
              ?>
            </h4>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
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
              $sql = 'SELECT SUM(quantidade_disponivel) AS total FROM equipamentos';
              $total = $banco->Consultar($sql, [], true);
              echo $total[0]['total'];
              ?>
            </h4>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
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
              $sql = 'SELECT SUM(quantidade_total) AS total FROM equipamentos';
              $total = $banco->Consultar($sql, [], true);
              echo $total[0]['total'];
              ?>
            </h4>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Equipment table -->
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
              $sql = 'SELECT * FROM equipamentos WHERE ativo = 1';
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
              echo "<script>alert('{$erro->getMessage()}');</script>";
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

<!-- JQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
  $('#form_epi').submit(function() {
    return false;
  });

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
        error: function(erro) {
          alert('Ocorreu um erro na requisição: ' + erro);
        }
      });
    }
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