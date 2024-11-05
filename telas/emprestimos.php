<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
  <h1>Empréstimos</h1>
  <button type="button" class="w-20 btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#adicionar_emprestimo">
    <i class="bi bi-plus"></i> Novo
  </button>
</div>

<div class="table-responsive">
  <table class="table table-striped table-hover">
    <thead>
      <tr>
        <th scope="col">ID</th>
        <th scope="col">Colaborador</th>
        <th scope="col">Data Empréstimo</th>
        <th scope="col">Data Devolução</th>
        <th scope="col">Status</th>
        <th scope="col">Ações</th>
      </tr>
    </thead>
    <tbody>
      <?php
      try {
        include_once 'src/class/BancodeDados.php';
        $banco = new BancodeDados;
        $sql = 'SELECT e.*, c.nome colaborador FROM emprestimos e
                LEFT JOIN colaboradores c on c.id_colaborador = e.id_colaborador';
        $dados = $banco->Consultar($sql, [], true);
        if ($dados) {
          foreach ($dados as $linha) {
            // <a href='#' onclick='selecionar({$linha['id_emprestimo']})' class='btn btn-sm btn-outline-primary'>
            //   <i class='bi bi-eye'></i>
            // </a>
            echo
            "<tr'>
              <td>{$linha['id_emprestimo']}</td>
              <td>{$linha['colaborador']}</td>
              <td>{$linha['data_emprestimo']}</td>
              <td>{$linha['data_devolucao']}</td>
              <td>{$linha['status']}</td>                
              <td>                
                <a href='#' onclick='devolver({$linha['id_emprestimo']})' class='btn btn-sm btn-outline-primary'>
                  <i class='bi bi-arrow-repeat'></i>
                </a>
                <a href='#' onclick='excluir({$linha['id_emprestimo']})' class='btn btn-sm btn-outline-danger'>
                  <i class='bi bi-trash'></i>
                </a>
              </td>
            </tr>";
          }
        } else {
          echo
          "<tr>
              <td colspan = '6' class='text-center'> Nenhum empréstimo registrado...</td>
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
<div id="adicionar_emprestimo" class="modal fade" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <form id="form_emprestimo" method="post" enctype="multipart/form-data" onsubmit="return false">
        <div class="modal-header" style="background-color: #435d7d; color: #fff;">
          <h4 class="modal-title">Empréstimo</h4>
          <button type="reset" class="btn-close" data-bs-dismiss="modal" aria-hidden="true" style="color: #fff; font-size: 1.2rem; opacity: 0.8;"></button>
        </div>

        <!-- Nav Tabs -->
        <div class="modal-body" style="padding: 20px;">
          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
              <a class="nav-link active" id="geral-tab" data-bs-toggle="tab" href="#geral" role="tab" aria-controls="geral" aria-selected="true">Informações Gerais</a>
            </li>
            <li class="nav-item" role="presentation">
              <a class="nav-link" id="equipamentos-tab" data-bs-toggle="tab" href="#equipamentos" role="tab" aria-controls="equipamentos" aria-selected="false">Equipamentos</a>
            </li>
          </ul>

          <!-- Tab content -->
          <div class="tab-content mt-3" id="myTabContent">
            <!-- Informações Gerais Tab -->
            <div class="tab-pane fade show active" id="geral" role="tabpanel" aria-labelledby="geral-tab">
              <input type="hidden" name="txt_id" id="txt_id" value="NOVO">

              <div class="form-group row mb-3">
                <div class="col-4">
                  <label for="txt_data_emprestimo" class="form-label">Data Empréstimo</label>
                  <input type="date" class="form-control" name="txt_data_emprestimo" id="txt_data_emprestimo" style="border-radius: 5px;">
                </div>
                <div class="col-4">
                  <label for="txt_data_devolucao" class="form-label">Data Devolução</label>
                  <input type="date" class="form-control" name="txt_data_devolucao" id="txt_data_devolucao" style="border-radius: 5px;">
                </div>
                <div class="col-4">
                  <label for="txt_status" class="form-label">Status</label>
                  <select class="form-control" name="txt_status" id="txt_status" style="border-radius: 5px;">
                    <option value="Pendente">Pendente</option>
                    <option value="Devolvido">Devolvido</option>
                  </select>
                </div>
              </div>

              <div class="form-group mb-3">
                <label for="txt_colaborador" class="form-label">Colaborador</label>
                <select class="form-control" name="txt_colaborador" id="txt_colaborador" style="border-radius: 5px;" required>
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

            <!-- Equipamentos Tab -->
            <div class="tab-pane fade" id="equipamentos" role="tabpanel" aria-labelledby="equipamentos-tab">
              <div class="form-group row mb-3">
                <div class="col-6">
                  <label for="txt_equipamento" class="form-label">Equipamento</label>
                  <select class="form-control" name="txt_equipamento" id="txt_equipamento" style="border-radius: 5px;" required>
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
                <div class="col-2">
                  <label for="txt_quantidade" class="form-label">Quantidade</label>
                  <input type="number" class="form-control" name="txt_quantidade" id="txt_quantidade" style="border-radius: 5px;" min="1" required>
                </div>
                <div class="col-4 d-flex justify-content-center align-items-center">
                  <button type="button" class="btn btn-primary" id="adicionar_equipamento">Adicionar Equipamento</button>
                </div>
              </div>

              <!-- Tabela de Equipamentos -->
              <div class="form-group mt-3">
                <table class="table" id="tabela_equipamentos">
                  <thead>
                    <tr>
                      <th>Equipamento</th>
                      <th>Quantidade</th>
                      <th>Ação</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    if (isset($_POST['txt_id']) && $_POST['txt_id'] != 'NOVO') {
                      try {
                        include_once 'src/class/BancodeDados.php';
                        $banco = new BancodeDados();
                        $idEmprestimo = $_POST['txt_id'];
                        $sql = 'SELECT e.nome, ee.qtd_equipamento 
                                FROM emprestimo_equipamentos ee
                                JOIN equipamentos e ON e.id_equipamento = ee.id_equipamento
                                WHERE ee.id_emprestimo = ?';
                        $dados = $banco->Consultar($sql, [$idEmprestimo], true);

                        if ($dados) {
                          foreach ($dados as $linha) {
                            echo "<tr'>
                                    <td class='id_equipamento' style='display:none;'>{$linha['id_equipamento']}</td>
                                    <td>{$linha['nome']}</td>
                                    <td>{$linha['qtd_equipamento']}</td>
                                    <td><button type='button' class='btn btn-danger btn-sm remover_equipamento'><i class='bi bi-trash'></i></button></td>
                                  </tr>";
                          }
                        }
                      } catch (PDOException $erro) {
                        $msg = $erro->getMessage();
                        echo "<script>alert(\"$msg\");</script>";
                      }
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer" style="background-color: #f7f7f7; padding: 15px;">
          <button type="reset" class="btn" style="border-radius: 5px; padding: 10px 20px;" data-bs-dismiss="modal">Cancelar</button>
          <button class="btn btn-success" style="border-radius: 5px; padding: 10px 20px;" onclick="cadastrar()">Efetivar Empréstimo</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  // Função para adicionar equipamento na tabela
  document.getElementById('adicionar_equipamento').addEventListener('click', function() {
    let equipamento = document.getElementById('txt_equipamento');
    let quantidade = document.getElementById('txt_quantidade');
    let tableBody = document.querySelector('#tabela_equipamentos tbody');

    if (equipamento.value && quantidade.value) {
      let id_equipamento = equipamento.value;

      let row = document.createElement('tr');

      let hiddenInput = document.createElement('input');
      hiddenInput.type = 'hidden';
      hiddenInput.value = id_equipamento;
      hiddenInput.classList.add('id_equipamento');
      row.appendChild(hiddenInput);

      let equipamentoCell = document.createElement('td');
      equipamentoCell.textContent = equipamento.options[equipamento.selectedIndex].text;
      row.appendChild(equipamentoCell);

      let quantidadeCell = document.createElement('td');
      quantidadeCell.textContent = quantidade.value;
      row.appendChild(quantidadeCell);

      let actionCell = document.createElement('td');
      actionCell.innerHTML = '<button type="button" class="btn btn-danger btn-sm remover_equipamento"> <i class = "bi bi-trash" > </i></button>';
      row.appendChild(actionCell);

      tableBody.appendChild(row);

      row.querySelector('.remover_equipamento').addEventListener('click', function() {
        row.remove();
        if (tableBody.children.length === 0) {
          document.getElementById('txt_colaborador').disabled = false;
        }
      });

      equipamento.value = '';
      quantidade.value = '';
    } else {
      alert('Selecione um equipamento e informe a quantidade.');
    }
  });
</script>


<!-- JQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var modal = new bootstrap.Modal(document.getElementById('adicionar_emprestimo'));

    $('#adicionar_emprestimo').on('show.bs.modal', function() {
      var today = new Date();
      var dd = String(today.getDate()).padStart(2, '0');
      var mm = String(today.getMonth() + 1).padStart(2, '0');
      var yyyy = today.getFullYear();

      today = yyyy + '-' + mm + '-' + dd;

      // Preenche o campo de data de empréstimo com a data atual
      document.getElementById('txt_data_emprestimo').value = today;
    });
  });

  // Registro de Emprestimo
  function cadastrar() {
    var id = document.getElementById('txt_id').value;
    var colaborador = document.getElementById('txt_colaborador').value;
    var data_emprestimo = document.getElementById('txt_data_emprestimo').value;
    var status = document.getElementById('txt_status').value;

    // Pegando os equipamentos adicionados à tabela
    var equipamentos = [];
    $('#tabela_equipamentos tbody tr').each(function() {
      var idEquipamento = $(this).find('.id_equipamento').val();
      var qtdEquipamento = $(this).find('td:nth-child(3)').text();

      // Verifica se a quantidade é válida      
      if (qtdEquipamento && parseInt(qtdEquipamento) > 0) {
        var equipamento = {
          id_equipamento: idEquipamento,
          qtd_equipamento: qtdEquipamento
        };
        equipamentos.push(equipamento);
      }
    });

    // Verifica se há equipamentos para enviar
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
        'status': status,
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
          // Preenche os campos do formulário do empréstimo
          document.getElementById('txt_id').value = retorno['dados']['id_emprestimo'];
          document.getElementById('txt_colaborador').value = retorno['dados']['id_colaborador'];
          document.getElementById('txt_data_emprestimo').value = retorno['dados']['data_emprestimo'];
          document.getElementById('txt_data_devolucao').value = retorno['dados']['data_devolucao'];
          document.getElementById('txt_status').value = retorno['dados']['status'];

          // Preenche a tabela de equipamentos
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

          // Exibe o modal
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