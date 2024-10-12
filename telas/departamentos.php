<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
  <h1>Cadastro de Departamentos</h1>
  <button type="button" class="w-20 btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#adicionar_departamento">
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
        <th scope="col">Ações</th>
      </tr>
    </thead>
    <tbody>
      <?php
      try {
        include_once 'src/class/BancodeDados.php';
        $banco = new BancodeDados;
        $sql = 'SELECT * FROM departamentos';
        $dados = $banco->Consultar($sql, [], true);
        if ($dados) {
          foreach ($dados as $linha) {
            echo
            "<tr'>
              <td>{$linha['id_departamento']}</td>
              <td>{$linha['nome']}</td>
              <td>{$linha['descricao']}</td>
              <td>
                <a href='#' onclick='atualizar({$linha['id_departamento']})' class='btn btn-sm btn-outline-primary'>
                  <i class='bi bi-pencil'></i>
                </a>
                <a href='#' onclick='excluir({$linha['id_departamento']})' class='btn btn-sm btn-outline-danger'>
                  <i class='bi bi-trash'></i>
                </a>
              </td>
            </tr>";
          }
        } else {
          echo
          "<tr>
            <td colspan = '6' class='text-center'> Nenhum departamento cadastrado...</td>
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
<div id="adicionar_departamento" class="modal fade" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="form_departamento" method="post" enctype="multipart/form-data">
        <div class="modal-header" style="background-color: #435d7d; color: #fff;">
          <h4 class="modal-title">Departamento</h4>
          <button type="reset" class="btn-close" data-bs-dismiss="modal" aria-hidden="true" style="color: #fff; font-size: 1.2rem; opacity: 0.8;"></button>
        </div>
        <div class="modal-body" style="padding: 20px;">
          <input type="hidden" name="txt_id" id="txt_id" value="NOVO">

          <div class="form-group mb-3">
            <label for="txt_nome" class="form-label">Nome</label>
            <input type="text" class="form-control" name="txt_nome" id="txt_nome" style="border-radius: 5px;">
          </div>
          <div class="form-group mb-3">
            <label for="txt_descricao" class="form-label">Descricão</label>
            <input type="text" class="form-control" name="txt_descricao" id="txt_descricao" style="border-radius: 5px;">
          </div>
        </div>
        <div class="modal-footer" style="background-color: #f7f7f7; padding: 15px;">
          <button type="reset" class="btn" style="border-radius: 5px; padding: 10px 20px;" data-bs-dismiss="modal">Cancelar</button>
          <button class="btn btn-success" style="border-radius: 5px; padding: 10px 20px;" onclick="cadastrar()">Salvar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- JQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
  $('#form_departamento').submit(function() {
    return false
  });

  // Cadastro de Departamento
  function cadastrar() {
    var id = document.getElementById('txt_id').value;
    var nome = document.getElementById('txt_nome').value;
    var descricao = document.getElementById('txt_descricao').value;

    $.ajax({
      type: 'post',
      dataType: 'json',
      url: 'src/departamentos/cadastrar_departamento.php',
      data: {
        'id': id,
        'nome': nome,
        'descricao': descricao
      },
      success: function(retorno) {
        if (retorno['codigo'] == 2) {
          alert('Departamento cadastrado com sucesso!');
          window.location.reload();
        } else if (retorno['codigo'] == 3) {
          alert('Departamento atualizado com sucesso!');
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

  // Atualizar Departamento
  function atualizar(idDepartamento) {
    $.ajax({
      type: 'post',
      dataType: 'json',
      url: 'src/departamentos/selecionar_departamento.php',
      data: {
        'id_departamento': idDepartamento
      },
      success: function(retorno) {
        if (retorno['codigo'] == 2) {
          document.getElementById('txt_id').value = retorno['dados']['id_usuario'];
          document.getElementById('txt_nome').value = retorno['dados']['nome'];
          document.getElementById('txt_descricao').value = retorno['dados']['descricao'];

          var modal = new bootstrap.Modal(document.getElementById('adicionar_departamento'));
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

  // Excluir Departamento
  function excluir(idDepartamento) {
    var confirmou = confirm('Você tem certeza que deseja excluir este departamento?');
    if (confirmou) {
      $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'src/departamentos/excluir_departamento.php',
        data: {
          'id_departamento': idDepartamento
        },
        success: function(retorno) {
          alert(retorno['mensagem']);
          if (retorno['codigo'] == 2) {
            registrarLog('Exclusão de Departamento - ID: ' + idDepartamento);
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