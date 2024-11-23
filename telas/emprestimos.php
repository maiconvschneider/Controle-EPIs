<div class="container-fluid py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <a href="sistema.php" class="btn btn-outline-primary btn-lg rounded-circle shadow-sm">
      <i class="bi bi-arrow-left"></i>
    </a>
    <h1 class="mb-0">Gestão de Empréstimos</h1>
    <button type="button" class="w-20 btn btn-success btn-lg rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#adicionar_emprestimo">
      <i class="bi bi-plus-lg me-2"></i>Novo Empréstimo
    </button>
  </div>

  <!-- Estatísticas -->
  <div class="row mb-4">
    <div class="col-md-4">
      <div class="card shadow-sm border-0 mb-3">
        <div class="card-body d-flex align-items-center">
          <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
            <i class="bi bi-bookmark-check text-primary" style="font-size: 2rem;"></i>
          </div>
          <div>
            <h6 class="text-muted mb-1">Total de Empréstimos</h6>
            <h4 class="mb-0">
              <?php
              include_once 'src/class/BancodeDados.php';
              $banco = new BancodeDados;

              // Total de empréstimos
              $sql = 'SELECT COUNT(*) AS total FROM emprestimos WHERE ativo = 1';
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
            <i class="bi bi-hourglass-split text-warning" style="font-size: 2rem;"></i>
          </div>
          <div>
            <h6 class="text-muted mb-1">Empréstimos Abertos</h6>
            <h4 class="mb-0">
              <?php
              include_once 'src/class/BancodeDados.php';
              $banco = new BancodeDados;

              // Total de empréstimos abertos
              $sql = 'SELECT COUNT(*) as total FROM emprestimos WHERE status != "Devolvido" and ativo = 1';
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
            <h6 class="text-muted mb-1">Empréstimos Concluídos</h6>
            <h4 class="mb-0">
              <?php
              include_once 'src/class/BancodeDados.php';
              $banco = new BancodeDados;

              // Total de empréstimos devolvidos
              $sql = 'SELECT COUNT(*) as total FROM emprestimos WHERE status = "Devolvido" and ativo = 1';
              $total = $banco->Consultar($sql, [], true);
              echo $total[0]['total'];
              ?>
            </h4>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Tabela de Empréstimos -->
  <div class="card shadow-sm border-0">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th scope="col">ID</th>
              <th scope="col">Colaborador</th>
              <th scope="col">Data Empréstimo</th>
              <th scope="col">Data Devolução</th>
              <th scope="col">Status</th>
              <th scope="col" class="text-end">Ações</th>
            </tr>
          </thead>
          <tbody>
            <?php
            try {
              $sql =
                'SELECT e.*, c.nome AS colaborador FROM emprestimos e
                      LEFT JOIN colaboradores c on c.id_colaborador = e.id_colaborador
                      WHERE e.ativo = 1';
              $dados = $banco->Consultar($sql, [], true);
              if ($dados) {
                foreach ($dados as $linha) {
                  $dataEmprestimoFormatada = date('d/m/Y H:i', strtotime($linha['data_emprestimo']));

                  // Verifica se a data de devolução está preenchida
                  $dataDevolucaoFormatada = !empty($linha['data_devolucao'])
                    ? date('d/m/Y H:i', strtotime($linha['data_devolucao']))
                    : '';

                  echo "
                  <tr>
                    <td>{$linha['id_emprestimo']}</td>
                    <td>{$linha['colaborador']}</td>
                    <td>{$dataEmprestimoFormatada}</td>
                    <td>{$dataDevolucaoFormatada}</td>
                    <td>
                      <span class='badge rounded-pill bg-" . ($linha['status'] == 'Devolvido' ? 'success' : 'warning') . "'>
                        {$linha['status']}
                      </span>
                    </td>
                    <td class='text-end'>
                      <button onclick='devolver({$linha['id_emprestimo']})' class='btn btn-sm btn-outline-primary rounded-pill me-1' title='Devolver'>
                        <i class='bi bi-arrow-repeat'></i>
                      </button>
                      <button onclick='excluir({$linha['id_emprestimo']})' class='btn btn-sm btn-outline-danger rounded-pill'>
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
                      <p class='mt-2 mb-0'>Nenhum empréstimo registrado...</p>
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
<div id="adicionar_emprestimo" class="modal fade" data-bs-backdrop="static" tabindex="-1" aria-labelledby="adicionar_emprestimoLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg"> <!-- Aumentei para modal-lg -->
    <div class="modal-content shadow-sm border-0">
      <form id="form_emprestimo" method="post" enctype="multipart/form-data" onsubmit="return false">
        <div class="modal-header" style="background-color: #435d7d; color: #fff;">
          <h5 class="modal-title" id="adicionar_emprestimoLabel">Empréstimo</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true" style="opacity: 0.8;"></button>
        </div>

        <div class="modal-body" style="padding: 20px;">
          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
              <a class="nav-link active" id="geral-tab" data-bs-toggle="tab" href="#geral" role="tab" aria-controls="geral" aria-selected="true">
                <i class="bi bi-info-circle me-2"></i>Informações Gerais
              </a>
            </li>
            <li class="nav-item" role="presentation">
              <a class="nav-link" id="equipamentos-tab" data-bs-toggle="tab" href="#equipamentos" role="tab" aria-controls="equipamentos" aria-selected="false">
                <i class="bi bi-boxes me-2"></i>Equipamentos
              </a>
            </li>
          </ul>

          <div class="tab-content mt-3" id="myTabContent">
            <div class="tab-pane fade show active" id="geral" role="tabpanel" aria-labelledby="geral-tab">
              <input type="hidden" name="txt_id" id="txt_id" value="NOVO">

              <div class="row mb-3">
                <div class="col-md-8">
                  <label for="txt_usuario" class="form-label">Usuário Responsável</label>
                  <input type="text" class="form-control bg-light" name="txt_usuario" id="txt_usuario" value="<?php echo $_SESSION['nome_usuario']; ?>" readonly>
                </div>
                <div class="col-md-4">
                  <label for="txt_data_emprestimo" class="form-label">Data Empréstimo</label>
                  <input type="date" class="form-control" name="txt_data_emprestimo" id="txt_data_emprestimo" required>
                </div>
              </div>

              <div class="form-group mb-3">
                <label for="txt_colaborador" class="form-label">Colaborador</label>
                <select class="form-select" name="txt_colaborador" id="txt_colaborador" required>
                  <option value="">Selecione um colaborador</option>
                  <?php
                  function getColaboradores()
                  {
                    include_once 'src/class/BancodeDados.php';
                    $banco = new BancodeDados();
                    $sql = 'SELECT id_colaborador, nome FROM colaboradores WHERE ativo = 1';
                    return $banco->Consultar($sql, [], true);
                  }

                  $colaboradores = getColaboradores();
                  if ($colaboradores) {
                    foreach ($colaboradores as $colaborador) {
                      echo "<option value='{$colaborador['id_colaborador']}'>{$colaborador['nome']}</option>";
                    }
                  }
                  ?>
                </select>
              </div>
            </div>

            <div class="tab-pane fade" id="equipamentos" role="tabpanel" aria-labelledby="equipamentos-tab">
              <div class="card mb-3">
                <div class="card-body">
                  <div class="row g-3 align-items-end">
                    <div class="col-md-7">
                      <label for="txt_equipamento" class="form-label">Equipamento</label>
                      <select class="form-select" name="txt_equipamento" id="txt_equipamento" required>
                        <option value="">Selecione um equipamento</option>
                        <?php
                        function getEquipamentos()
                        {
                          include_once 'src/class/BancodeDados.php';
                          $banco = new BancodeDados();
                          $sql = 'SELECT id_equipamento, nome FROM equipamentos WHERE ativo = 1';
                          return $banco->Consultar($sql, [], true);
                        }

                        $equipamentos = getEquipamentos();
                        if ($equipamentos) {
                          foreach ($equipamentos as $equipamento) {
                            echo "<option value='{$equipamento['id_equipamento']}'>{$equipamento['nome']}</option>";
                          }
                        }
                        ?>
                      </select>
                    </div>
                    <div class="col-md-2">
                      <label for="txt_quantidade" class="form-label">Quantidade</label>
                      <input type="number" class="form-control" name="txt_quantidade" id="txt_quantidade" min="1" required>
                    </div>
                    <div class="col-md-3">
                      <button type="button" class="btn btn-primary w-100" id="adicionar_equipamento">
                        <i class="bi bi-plus-lg"></i> Adicionar
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <div class="card">
                <div class="card-body p-0">
                  <table class="table table-hover mb-0" id="tabela_equipamentos">
                    <thead class="table-light">
                      <tr>
                        <th>Equipamento</th>
                        <th style="width: 120px;">Quantidade</th>
                        <th style="width: 100px;">Ação</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer" style="background-color: #f7f7f7;">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            <i class="bi bi-x-circle"></i> Cancelar
          </button>
          <button type="button" class="btn btn-success" onclick="cadastrar()">
            <i class="bi bi-check-circle"></i> Efetivar Empréstimo
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  // Função para adicionar equipamento na tabela
  document.getElementById('adicionar_equipamento').addEventListener('click', function() {
    // Seleciona os elementos necessários
    const equipamento = document.getElementById('txt_equipamento');
    const quantidade = document.getElementById('txt_quantidade');
    const tableBody = document.querySelector('#tabela_equipamentos tbody');

    // Verifica se o equipamento e a quantidade são válidos
    if (equipamento.value && quantidade.value && parseInt(quantidade.value) > 0) {
      const id_equipamento = equipamento.value;
      const nome_equipamento = equipamento.options[equipamento.selectedIndex].text;
      const quantidade_valida = quantidade.value;

      const row = document.createElement('tr');
      const hiddenInput = document.createElement('input');

      hiddenInput.type = 'hidden';
      hiddenInput.value = id_equipamento;
      hiddenInput.classList.add('id_equipamento');
      row.appendChild(hiddenInput);

      // equipamento
      const equipamentoCell = document.createElement('td');
      equipamentoCell.textContent = nome_equipamento;
      row.appendChild(equipamentoCell);

      // quantidade
      const quantidadeCell = document.createElement('td');
      quantidadeCell.textContent = quantidade_valida;
      row.appendChild(quantidadeCell);

      // ação (remover)
      const actionCell = document.createElement('td');
      actionCell.innerHTML = '<button type="button" class="btn btn-danger btn-sm remover_equipamento"><i class="bi bi-trash"></i></button>';
      row.appendChild(actionCell);
      tableBody.appendChild(row);

      // Adiciona o evento de remoção à linha
      row.querySelector('.remover_equipamento').addEventListener('click', function() {
        row.remove();
      });

      // Limpa os campos após adicionar
      equipamento.value = '';
      quantidade.value = '';
    } else {
      alert('Selecione um equipamento e informe uma quantidade válida.');
    }
  });
</script>


<!-- JQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var modal = new bootstrap.Modal(document.getElementById('adicionar_emprestimo'));

    $('#adicionar_emprestimo').on('show.bs.modal', function() {
      var dataAtual = new Date();
      var dd = String(dataAtual.getDate()).padStart(2, '0');
      var mm = String(dataAtual.getMonth() + 1).padStart(2, '0');
      var yyyy = dataAtual.getFullYear();

      dataAtual = yyyy + '-' + mm + '-' + dd;

      // Preenche o campo de data de empréstimo com a data atual
      document.getElementById('txt_data_emprestimo').value = dataAtual;
    });
  });

  function cadastrar() {
    var id = document.getElementById('txt_id').value;
    var colaborador = document.getElementById('txt_colaborador').value;
    var data_emprestimo = document.getElementById('txt_data_emprestimo').value;

    // Pegando os equipamentos adicionados à tabela
    var equipamentos = [];
    $('#tabela_equipamentos tbody tr').each(function() {
      var idEquipamento = $(this).find('.id_equipamento').val();
      var qtdEquipamento = $(this).find('td:nth-child(3)').text().trim();

      // Verifica se a quantidade é válida      
      if (idEquipamento && qtdEquipamento && parseInt(qtdEquipamento) > 0) {
        var equipamento = {
          id_equipamento: idEquipamento,
          qtd_equipamento: qtdEquipamento
        };
        equipamentos.push(equipamento);
      }
    });

    if (equipamentos.length === 0) {
      alert('Por favor, adicione ao menos um equipamento.');
      return;
    }

    $.ajax({
      type: 'post',
      dataType: 'json',
      url: 'src/emprestimos/cadastrar_emprestimo.php',
      data: {
        'id': id,
        'colaborador': colaborador,
        'data_emprestimo': data_emprestimo,
        'equipamentos': equipamentos
      },
      success: function(retorno) {
        if (retorno['status'] == 'ok') {
          alert('Empréstimo realizado com sucesso!');
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

  // Devolver Emprestimo
  function devolver(idEmprestimo) {
    var confirmou = confirm('Você tem certeza que deseja marcar este empréstimo como devolvido?');
    if (confirmou) {
      $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'src/emprestimos/devolver_emprestimo.php',
        data: {
          'id_emprestimo': idEmprestimo
        },
        success: function(retorno) {
          alert(retorno['mensagem']);
          if (retorno['status'] == 'ok') {
            registrarLog('Devolução de Empréstimo - ID: ' + idEmprestimo);
            window.location.reload();
          }
        },
        error: function(erro) {
          alert('Ocorreu um erro na requisição: ' + erro);
        }
      });
    }
  }

  function selecionar(idEmprestimo) {
    $.ajax({
      type: 'post',
      dataType: 'json',
      url: 'src/emprestimos/selecionar_emprestimo.php',
      data: {
        'id_emprestimo': idEmprestimo
      },
      success: function(retorno) {
        if (retorno['status'] == 'ok') {
          document.getElementById('txt_id').value = retorno['dados']['id_emprestimo'];
          document.getElementById('txt_colaborador').value = retorno['dados']['id_colaborador'];
          document.getElementById('txt_data_emprestimo').value = retorno['dados']['data_emprestimo'];
          document.getElementById('txt_data_devolucao').value = retorno['dados']['data_devolucao'];
          document.getElementById('txt_status').value = retorno['dados']['status'];

          $('#tabela_equipamentos tbody').html('');
          retorno['equipamentos'].forEach(function(equipamento) {
            var linha = `<tr>
                     <td class='id_equipamento' style='display:none;'>${equipamento.id_equipamento}</td>
                     <td>${equipamento.nome_equipamento}</td>
                     <td>${equipamento.qtd_equipamento}</td>
                     <td><button type='button' class='btn btn-danger btn-sm remover_equipamento'><i class='bi bi-trash'></i></button></td>
                   </tr>`;
            $('#tabela_equipamentos tbody').append(linha);
          });

          var modal = new bootstrap.Modal(document.getElementById('adicionar_emprestimo'));
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

  // Excluir Emprestimo
  function excluir(idEmprestimo) {
    var confirmou = confirm('Você tem certeza que deseja excluir este empréstimo?');
    if (confirmou) {
      $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'src/emprestimos/excluir_emprestimo.php',
        data: {
          'id_emprestimo': idEmprestimo
        },
        success: function(retorno) {
          alert(retorno['mensagem']);
          if (retorno['status'] == 'ok') {
            registrarLog('Exclusão de Empréstimo - ID: ' + idEmprestimo);
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