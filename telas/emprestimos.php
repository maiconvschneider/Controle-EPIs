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
              <input type="text" class="form-control" name="txt_data_emprestimo" id="txt_data_emprestimo" style="border-radius: 5px;">
            </div>
            <div class="col-4">
              <label for="txt_data_devolucao" class="form-label">Data Devolução</label>
              <input type="text" class="form-control" name="txt_data_devolucao" id="txt_data_devolucao" style="border-radius: 5px;">
            </div>
            <div class="col-4">
              <label for="txt_status" class="form-label">Status</label>
              <select class="form-control" name="txt_status" id="txt_status" style="border-radius: 5px;">
                <option value=""></option>
                <option value="Pendente">Pendente</option>
                <option value="Emprestado">Emprestado</option>
                <option value="Devolvido">Devolvido</option>
              </select>
            </div>
          </div>
          <div class="form-group mb-3">
            <label for="txt_colaborador" class="form-label">Colaborador</label>
            <input type="text" class="form-control" name="txt_colaborador" id="txt_colaborador" style="border-radius: 5px;">
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

    $.ajax({
      type: 'post',
      dataType: 'json',
      url: 'src/emprestimos/cadastrar_emprestimo.php',
      data: {
        'id': id,
        'colaborador': colaborador,
        'data_emprestimo': data_emprestimo,
        'status': status
      },
      success: function(retorno) {
        if (retorno['codigo'] == 2) {
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