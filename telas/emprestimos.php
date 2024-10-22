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
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="form_emprestimo" method="post" enctype="multipart/form-data">
        <div class="modal-header" style="background-color: #435d7d; color: #fff;">
          <h4 class="modal-title">Empréstimo</h4>
          <button type="reset" class="btn-close" data-bs-dismiss="modal" aria-hidden="true" style="color: #fff; font-size: 1.2rem; opacity: 0.8;"></button>
        </div>
        <div class="modal-body" style="padding: 20px;">
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
                <option value=""></option>
                <option value="Pendente">Aberto</option>
                <option value="Emprestado">Emprestado</option>
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

                $sql = 'SELECT id_colaborador, nome 
                        FROM colaboradores 
                        WHERE ativo = 1';
                $colaboradores = $banco->Consultar($sql, [], true);

                return $colaboradores;
              }

              $colaboradores = getColaboradores();
              if ($colaboradores) {
                foreach ($colaboradores as $colaborador) {
                  $id_colaborador = $colaborador['id_colaborador'];
                  $nome = $colaborador['nome'];
                  echo "<option value='$id_colaborador'>$nome</option>";
                }
              }
              ?>
            </select>
          </div>

          <div class="form-group row mb-3">
            <div class="col-8">
              <label for="txt_equipamento" class="form-label">Equipamento</label>
              <select class="form-control" name="txt_equipamento" id="txt_equipamento" style="border-radius: 5px;" required>
                <option value="">Selecione um equipamento</option>
                <?php
                function getEquipamentos()
                {
                  include_once 'src/class/BancodeDados.php';
                  $banco = new BancodeDados();

                  $sql = 'SELECT id_equipamento, nome 
                          FROM equipamentos 
                          WHERE ativo = 1';
                  $equipamentos = $banco->Consultar($sql, [], true);

                  return $equipamentos;
                }

                $equipamentos = getEquipamentos();
                if ($equipamentos) {
                  foreach ($equipamentos as $equipamento) {
                    $id_equipamento = $equipamento['id_equipamento'];
                    $nome = $equipamento['nome'];
                    echo "<option value='$id_equipamento'>$nome</option>";
                  }
                }
                ?>
              </select>
            </div>
            <div class="col-4">
              <label for="txt_quantidade" class="form-label">Quantidade</label>
              <input type="number" class="form-control" name="txt_quantidade" id="txt_quantidade" style="border-radius: 5px;" min="1" required>
            </div>
          </div>
          <button type="button" class="btn btn-primary" id="adicionar_equipamento">Adicionar Equipamento</button>

          <!-- Tabela de Equipamentos -->
          <div class="form-group mt-3">
            <table class="table table-bordered" id="tabela_equipamentos">
              <thead>
                <tr>
                  <th>Equipamento</th>
                  <th>Quantidade</th>
                  <th>Ação</th>
                </tr>
              </thead>
              <tbody>
                <?php
                // Verifique se o campo 'txt_id' é diferente de 'NOVO'
                if (isset($_POST['txt_id']) && $_POST['txt_id'] != 'NOVO') {
                  try {
                    include_once 'src/class/BancodeDados.php';
                    $banco = new BancodeDados();

                    // Aqui o id do empréstimo é obtido do campo hidden 'txt_id'
                    $idEmprestimo = $_POST['txt_id'];
                    $sql = 'SELECT e.nome, ee.qtd_equipamento 
                            FROM emprestimo_equipamentos ee
                            JOIN equipamentos e ON e.id_equipamento = ee.id_equipamento
                            WHERE ee.id_emprestimo = ?';

                    // Executa a consulta apenas se houver um ID de empréstimo válido
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
                    } else {
                      echo "<tr>
                              <td colspan='3' class='text-center'>Nenhum equipamento emprestado...</td>
                            </tr>";
                    }
                  } catch (PDOException $erro) {
                    $msg = $erro->getMessage();
                    echo "<script>alert(\"$msg\");</script>";
                  }
                } else {
                  // Caso seja um novo empréstimo, não exibe a tabela de equipamentos emprestados
                  echo "<tr>
                          <td colspan='3' class='text-center'>Novo registro - Nenhum equipamento adicionado ainda.</td>
                        </tr>";
                }
                ?>
              </tbody>
            </table>
          </div>

        </div>
        <div class="modal-footer" style="background-color: #f7f7f7; padding: 15px;">
          <button type="reset" class="btn" style="border-radius: 5px; padding: 10px 20px;" data-bs-dismiss="modal">Cancelar</button>
          <button class="btn btn-success" style="border-radius: 5px; padding: 10px 20px;" onclick="cadastrar()">Salvar</button>
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
  $('#form_emprestimo').submit(function() {
    return false
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
      var idEquipamento = $(this).find('.id_equipamento').text();
      var qtdEquipamento = $(this).find('.qtd_equipamento').text();
      console.log("ID Equipamento: " + idEquipamento + ", Qtd: " + qtdEquipamento);
      var equipamento = {
        id_equipamento: idEquipamento,
        qtd_equipamento: qtdEquipamento
      };
      equipamentos.push(equipamento);
    });

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
        if (retorno['codigo'] == 2) {
          alert('Empréstimo realizado com sucesso!');
          window.location.reload();
        } else {
          alert(retorno['mensagem']);
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        var mensagemErro = `
      Ocorreu um erro na requisição:
      \nStatus: ${jqXHR.status} - ${jqXHR.statusText}
      \nMensagem de Erro: ${errorThrown}
      \nDetalhes da Resposta: ${jqXHR.responseText}
    `;
        alert(mensagemErro);
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
          if (retorno['codigo'] == 2) {
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
          if (retorno['codigo'] == 2) {
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