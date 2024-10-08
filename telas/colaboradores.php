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
                                <a href='#' onclick='atualizar({$linha['id_colaborador']})' class='btn btn-sm btn-outline-primary'>
                                    <i class='bi bi-pencil'></i>
                                </a>
                                <a href='#' onclick='excluir({$linha['id_colaborador']})' class='btn btn-sm btn-outline-danger'>
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
      <form id="form_colaborador" method="post" enctype="multipart/form-data">
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
          <button class="btn btn-success" style="border-radius: 5px; padding: 10px 20px;" onclick="cadastrar()">Salvar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- JQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
  $('#form_colaborador').submit(function() {
    return false;
  });

  // Cadastro de Colaborador
  function cadastrar() {
    var id = document.getElementById('txt_id').value;
    var nome = document.getElementById('txt_nome').value;
    var matricula = document.getElementById('txt_matricula').value;
    var departamento = document.getElementById('txt_departamento').value;
    var email = document.getElementById('txt_email').value;

    $.ajax({
      type: 'post',
      dataType: 'json',
      url: 'src/colaboradores/cadastrar_colaborador.php',
      data: {
        'id': id,
        'nome': nome,
        'matricula': matricula,
        'departamento': departamento,
        'email': email
      },
      success: function(retorno) {
        if (retorno['codigo'] == 2) {
          alert('Colaborador cadastrado com sucesso!');
          window.location.reload();
        } else if (retorno['codigo'] == 3) {
          alert('Colaborador atualizado com sucesso!');
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

  // Atualizar Colaborador
  function atualizar(idColaborador) {
    $.ajax({
      type: 'post',
      dataType: 'json',
      url: 'src/colaboradores/selecionar_colaborador.php',
      data: {
        'id_colaborador': idColaborador
      },
      success: function(retorno) {
        if (retorno['codigo'] == 2) {
          document.getElementById('txt_id').value = retorno['dados']['id_colaborador'];
          document.getElementById('txt_nome').value = retorno['dados']['nome'];
          document.getElementById('txt_matricula').value = retorno['dados']['matricula'];
          document.getElementById('txt_departamento').value = retorno['dados']['departamento'];
          document.getElementById('txt_email').value = retorno['dados']['email'];

          var modal = new bootstrap.Modal(document.getElementById('adicionar_colaborador'));
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

  // Excluir Colaborador
  function excluir(idColaborador) {
    var confirmou = confirm('Você tem certeza que deseja excluir este colaborador?');
    if (confirmou) {
      $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'src/colaboradores/excluir_colaborador.php',
        data: {
          'id_colaborador': idColaborador
        },
        success: function(retorno) {
          alert(retorno['mensagem']);
          if (retorno['codigo'] == 2) {
            Log('Exclusão do colaborador ' + idColaborador);
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
  function Log(acao) {
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