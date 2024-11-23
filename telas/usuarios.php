<div class="container-fluid py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <a href="sistema.php" class="btn btn-outline-primary btn-lg rounded-circle shadow-sm">
      <i class="bi bi-arrow-left"></i>
    </a>
    <h1 class="mb-0">Gestão de Usuários</h1>
    <button type="button" class="btn btn-success btn-lg rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#adicionar_usuario">
      <i class="bi bi-plus-lg me-2"></i>Novo Usuário
    </button>
  </div>

  <!-- Estatísticas -->
  <div class="row mb-4">
    <div class="col-md-4">
      <div class="card shadow-sm border-0 mb-3">
        <div class="card-body d-flex align-items-center">
          <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
            <i class="bi bi-people-fill text-primary" style="font-size: 2rem;"></i>
          </div>
          <div>
            <h6 class="text-muted mb-1">Total de Usuários</h6>
            <h4 class="mb-0" id="total-usuarios">
              <?php
              include_once 'src/class/BancodeDados.php';
              $banco = new BancodeDados;

              // Total de usuários
              $sql = 'SELECT COUNT(*) AS total FROM usuarios';
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
            <i class="bi bi-person-check-fill text-success" style="font-size: 2rem;"></i>
          </div>
          <div>
            <h6 class="text-muted mb-1">Administradores</h6>
            <h4 class="mb-0">
              <?php
              include_once 'src/class/BancodeDados.php';
              $banco = new BancodeDados;

              // Total de usuários administradores
              $sql = 'SELECT COUNT(*) AS total FROM usuarios WHERE tipo = "A"';
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
          <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3">
            <i class="bi bi-person-fill text-info" style="font-size: 2rem;"></i>
          </div>
          <div>
            <h6 class="text-muted mb-1">Usuários Comuns</h6>
            <h4 class="mb-0">
              <?php
              include_once 'src/class/BancodeDados.php';
              $banco = new BancodeDados;

              // Total de usuários administradores
              $sql = 'SELECT COUNT(*) AS total FROM usuarios WHERE tipo = "U"';
              $total = $banco->Consultar($sql, [], true);
              echo $total[0]['total'];
              ?>
            </h4>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Tabela de usuários -->
  <div class="card shadow-sm border-0">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th scope="col">ID</th>
              <th scope="col">Nome</th>
              <th scope="col">Usuário</th>
              <th scope="col">Tipo</th>
              <th scope="col" class="text-end">Ações</th>
            </tr>
          </thead>
          <tbody>
            <?php
            try {
              include_once 'src/class/BancodeDados.php';
              $banco = new BancodeDados;
              $sql = "SELECT u.*,
                      CASE WHEN u.tipo = 'U' THEN 'Usuário'
                           WHEN u.tipo = 'A' THEN 'Administrador'
                      END AS tipo_extenso
                      FROM usuarios u
                      WHERE ativo = 1";
              $dados = $banco->Consultar($sql, [], true);
              if ($dados) {
                foreach ($dados as $linha) {
                  $tipoBadgeClass = $linha['tipo'] === 'A' ? 'bg-success' : 'bg-info';
                  echo "
                  <tr>
                    <td>{$linha['id_usuario']}</td>
                    <td>
                      <div class='d-flex align-items-center'>
                        <div class='rounded-circle bg-light p-2 me-2'>
                          <i class='bi bi-person'></i>
                        </div>
                        {$linha['nome']}
                      </div>
                    </td>
                    <td>{$linha['usuario']}</td>
                    <td><span class='badge rounded-pill {$tipoBadgeClass}'>{$linha['tipo_extenso']}</span></td>
                    <td class='text-end'>
                      <button onclick='atualizar({$linha['id_usuario']})' class='btn btn-sm btn-outline-primary rounded-pill me-1'>
                        <i class='bi bi-pencil'></i>
                      </button>
                      <button onclick='excluir({$linha['id_usuario']})' class='btn btn-sm btn-outline-danger rounded-pill'>
                        <i class='bi bi-trash'></i>
                      </button>
                    </td>
                  </tr>";
                }
              } else {
                echo "
                <tr>
                  <td colspan='5' class='text-center py-4'>
                    <div class='text-muted'>
                      <i class='bi bi-inbox-fill' style='font-size: 2rem;'></i>
                      <p class='mt-2 mb-0'>Nenhum usuário cadastrado...</p>
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
<div id="adicionar_usuario" class="modal fade" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <form id="form_usuario" method="post" enctype="multipart/form-data" onsubmit="return false">
        <div class="modal-header border-0" style="background: linear-gradient(135deg, #435d7d, #4a6da1);">
          <h4 class="modal-title text-white">Usuário</h4>
          <button type="reset" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-hidden="true"></button>
        </div>
        <div class="modal-body p-4">
          <input type="hidden" name="txt_id" id="txt_id" value="NOVO">

          <div class="mb-3">
            <label for="txt_nome" class="form-label">Nome</label>
            <div class="input-group">
              <span class="input-group-text bg-light border-0">
                <i class="bi bi-person"></i>
              </span>
              <input type="text" class="form-control border-0 bg-light" name="txt_nome" id="txt_nome">
            </div>
          </div>
          <div class="mb-3">
            <label for="txt_usuario" class="form-label">Usuário</label>
            <div class="input-group">
              <span class="input-group-text bg-light border-0">
                <i class="bi bi-at"></i>
              </span>
              <input type="text" class="form-control border-0 bg-light" name="txt_usuario" id="txt_usuario">
            </div>
          </div>
          <div class="mb-3">
            <label for="txt_senha" class="form-label">Senha</label>
            <div class="input-group">
              <span class="input-group-text bg-light border-0">
                <i class="bi bi-key"></i>
              </span>
              <input type="password" class="form-control border-0 bg-light" name="txt_senha" id="txt_senha">
            </div>
          </div>
          <div class="mb-3">
            <label for="txt_tipo" class="form-label">Tipo</label>
            <div class="input-group">
              <span class="input-group-text bg-light border-0">
                <i class="bi bi-shield"></i>
              </span>
              <select class="form-select border-0 bg-light" name="txt_tipo" id="txt_tipo">
                <option value=""></option>
                <option value="Administrador">Administrador</option>
                <option value="Usuário">Usuário</option>
              </select>
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
  // Cadastro de Usuário
  function cadastrar() {
    var id = document.getElementById('txt_id').value;
    var nome = document.getElementById('txt_nome').value;
    var usuario = document.getElementById('txt_usuario').value;
    var senha = document.getElementById('txt_senha').value;
    var tipo = (document.getElementById('txt_tipo').value == 'Administrador') ? 'A' : 'U';

    $.ajax({
      type: 'post',
      dataType: 'json',
      url: 'src/usuarios/cadastrar_usuario.php',
      data: {
        'id': id,
        'nome': nome,
        'usuario': usuario,
        'senha': senha,
        'tipo': tipo
      },
      success: function(retorno) {
        if (retorno['status'] == 'ok') {
          alert('Usuário cadastrado com sucesso!');
          window.location.reload();
        } else if (retorno['status'] == 'ok_atualizar') {
          alert('Usuário atualizado com sucesso!');
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

  // Atualizar Usuário
  function atualizar(idUsuario) {
    $.ajax({
      type: 'post',
      dataType: 'json',
      url: 'src/usuarios/selecionar_usuario.php',
      data: {
        'id_usuario': idUsuario
      },
      success: function(retorno) {
        if (retorno['status'] == 'ok') {
          document.getElementById('txt_id').value = retorno['dados']['id_usuario'];
          document.getElementById('txt_nome').value = retorno['dados']['nome'];
          document.getElementById('txt_usuario').value = retorno['dados']['usuario'];
          document.getElementById('txt_senha').value = retorno['dados']['senha'];
          document.getElementById('txt_tipo').value = (retorno['dados']['tipo'] == 'A') ? 'Administrador' : 'Usuário';

          var modal = new bootstrap.Modal(document.getElementById('adicionar_usuario'));
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

  // Excluir Usuário
  function excluir(idUsuario) {
    var confirmou = confirm('Você tem certeza que deseja excluir este usuário?');
    if (confirmou) {
      $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'src/usuarios/excluir_usuario.php',
        data: {
          'id_usuario': idUsuario
        },
        success: function(retorno) {
          alert(retorno['mensagem']);
          if (retorno['status'] == 'ok') {
            registrarLog('Exclusão de Usuário - ID: ' + idUsuario);
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