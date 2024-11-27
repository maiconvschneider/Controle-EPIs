<div class="container-fluid py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <a href="sistema.php" class="btn btn-outline-primary btn-lg rounded-circle shadow-sm">
      <i class="bi bi-arrow-left"></i>
    </a>
    <h1 class="mb-0">Gestão de Colaboradores</h1>
    <button type="button" class="btn btn-success btn-lg rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#adicionar_colaborador">
      <i class="bi bi-plus-lg me-2"></i>Novo Colaborador
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
            <h6 class="text-muted mb-1">Total de Colaboradores</h6>
            <h4 class="mb-0">
              <?php
              include_once 'src/class/BancodeDados.php';
              $banco = new BancodeDados;

              // Total de colaboradores
              $sql = 'SELECT COUNT(*) AS total 
                      FROM colaboradores';
              $total = $banco->Consultar($sql, [], true);
              echo $total[0]['total'];
              ?>
            </h4>
          </div>
        </div>
      </div>
    </div>

    <!-- Colaboradores Ativos -->
    <div class="col-md-4">
      <div class="card shadow-sm border-0 mb-3">
        <div class="card-body d-flex align-items-center">
          <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
            <i class="bi bi-person-check-fill text-success" style="font-size: 2rem;"></i>
          </div>
          <div>
            <h6 class="text-muted mb-1">Colaboradores Ativos</h6>
            <h4 class="mb-0">
              <?php
              $sql = 'SELECT COUNT(*) AS total 
                      FROM colaboradores 
                      WHERE ativo = 1';
              $total = $banco->Consultar($sql, [], true);
              echo $total[0]['total'];
              ?>
            </h4>
          </div>
        </div>
      </div>
    </div>

    <!-- Colaboradores Inativos -->
    <div class="col-md-4">
      <div class="card shadow-sm border-0 mb-3">
        <div class="card-body d-flex align-items-center">
          <div class="rounded-circle bg-danger bg-opacity-10 p-3 me-3">
            <i class="bi bi-person-x-fill text-danger" style="font-size: 2rem;"></i>
          </div>
          <div>
            <h6 class="text-muted mb-1">Colaboradores Inativos</h6>
            <h4 class="mb-0">
              <?php
              $sql = 'SELECT COUNT(*) AS total 
                      FROM colaboradores 
                      WHERE ativo = 0';
              $total = $banco->Consultar($sql, [], true);
              echo $total[0]['total'];
              ?>
            </h4>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Tabelas de Colaboradores - Ativos e Inativos -->
  <div class="card shadow-sm border-0">
    <div class="card-body">
      <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
          <a class="nav-link active" id="ativos-tab" data-bs-toggle="tab" href="#ativos" role="tab" aria-controls="ativos" aria-selected="true">
            Colaboradores Ativos
          </a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link" id="inativos-tab" data-bs-toggle="tab" href="#inativos" role="tab" aria-controls="inativos" aria-selected="false">
            Colaboradores Inativos
          </a>
        </li>
      </ul>

      <div class="tab-content mt-3" id="myTabContent">

        <!-- Colaboradores Ativos -->
        <div class="tab-pane fade show active" id="ativos" role="tabpanel" aria-labelledby="ativos-tab">
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th scope="col">ID</th>
                  <th scope="col">Nome</th>
                  <th scope="col">CPF</th>
                  <th scope="col">Nascimento</th>
                  <th scope="col">Departamento</th>
                  <th scope="col">Email</th>
                  <th scope="col" class="text-end">Ações</th>
                </tr>
              </thead>
              <tbody>
                <?php
                try {
                  include_once 'src/class/BancodeDados.php';
                  $banco = new BancodeDados;
                  $sql = 'SELECT c.*, d.nome as departamento 
                          FROM colaboradores c 
                          LEFT JOIN departamentos d ON d.id_departamento = c.id_departamento 
                          WHERE c.ativo = 1';
                  $dadosAtivos = $banco->Consultar($sql, [], true);
                  if ($dadosAtivos) {
                    foreach ($dadosAtivos as $linha) {
                      $dataNascimento = (new DateTime($linha['data_nascimento']))->format('d/m/Y');
                      echo "
                      <tr>
                        <td>{$linha['id_colaborador']}</td>
                        <td>
                          <div class='d-flex align-items-center'>
                            <div class='rounded-circle bg-light p-2 me-2'>
                              <i class='bi bi-person'></i>
                            </div>
                            {$linha['nome']}
                          </div>
                        </td>
                        <td><span class='badge rounded-pill bg-secondary'>{$linha['cpf']}</span></td>
                        <td>{$dataNascimento}</td>
                        <td>
                          <span class='badge rounded-pill bg-info text-dark'>
                            <i class='bi bi-building me-1'></i>{$linha['departamento']}
                          </span>
                        </td>
                        <td>
                          <small><i class='bi bi-envelope me-1'></i>{$linha['email']}</small>
                        </td>
                        <td class='text-end'>
                          <button onclick='atualizar({$linha['id_colaborador']})' class='btn btn-sm btn-outline-primary rounded-pill me-1'>
                            <i class='bi bi-pencil'></i>
                          </button>
                          <button onclick='excluir({$linha['id_colaborador']})' class='btn btn-sm btn-outline-danger rounded-pill'>
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
                          <p class='mt-2 mb-0'>Nenhum colaborador ativo...</p>
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

        <!-- Colaboradores Inativos -->
        <div class="tab-pane fade" id="inativos" role="tabpanel" aria-labelledby="inativos-tab">
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th scope="col">ID</th>
                  <th scope="col">Nome</th>
                  <th scope="col">CPF</th>
                  <th scope="col">Nascimento</th>
                  <th scope="col">Departamento</th>
                  <th scope="col">Email</th>
                  <th scope="col" class="text-end">Ações</th>
                </tr>
              </thead>
              <tbody>
                <?php
                try {
                  $sql = 'SELECT c.*, d.nome as departamento 
                                FROM colaboradores c 
                                LEFT JOIN departamentos d ON d.id_departamento = c.id_departamento 
                                WHERE c.ativo = 0';
                  $dados = $banco->Consultar($sql, [], true);
                  if ($dados) {
                    foreach ($dados as $linha) {
                      $dataNascimento = (new DateTime($linha['data_nascimento']))->format('d/m/Y');
                      echo "
                      <tr>
                        <td>{$linha['id_colaborador']}</td>
                        <td>
                          <div class='d-flex align-items-center'>
                            <div class='rounded-circle bg-light p-2 me-2'>
                              <i class='bi bi-person'></i>
                            </div>
                            {$linha['nome']}
                          </div>
                        </td>
                        <td><span class='badge rounded-pill bg-secondary'>{$linha['cpf']}</span></td>
                        <td>{$dataNascimento}</td>
                        <td>
                          <span class='badge rounded-pill bg-info text-dark'>
                            <i class='bi bi-building me-1'></i>{$linha['departamento']}
                          </span>
                        </td>
                        <td>
                          <small><i class='bi bi-envelope me-1'></i>{$linha['email']}</small>
                        </td>
                        <td class='text-end'>
                          <button onclick='reativar({$linha['id_colaborador']})' class='btn btn-sm btn-outline-success rounded-pill me-1'>
                            <i class='bi bi-check-circle'></i> Reativar
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
                          <p class='mt-2 mb-0'>Nenhum colaborador inativo...</p>
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
  </div>

  <!-- Modal -->
  <div id="adicionar_colaborador" class="modal fade" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content border-0 shadow">
        <form id="form_colaborador" method="post" enctype="multipart/form-data" onsubmit="return false">
          <div class="modal-header border-0" style="background: linear-gradient(135deg, #435d7d, #4a6da1);">
            <h4 class="modal-title text-white">Colaborador</h4>
            <button type="reset" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-hidden="true"></button>
          </div>

          <ul class="nav nav-tabs nav-fill" id="tabContent">
            <li class="nav-item">
              <a class="nav-link active" id="dados-pessoais-tab" data-bs-toggle="tab" href="#dados-pessoais">
                <i class="bi bi-person-vcard me-2"></i>Dados Pessoais
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="endereco-tab" data-bs-toggle="tab" href="#endereco">
                <i class="bi bi-geo-alt me-2"></i>Endereço
              </a>
            </li>
          </ul>

          <div class="tab-content">
            <div class="tab-pane fade show active" id="dados-pessoais">
              <div class="modal-body p-4">
                <input type="hidden" name="txt_id" id="txt_id" value="NOVO">

                <div class="row mb-3">
                  <div class="col-8">
                    <label for="txt_nome" class="form-label">Nome</label>
                    <div class="input-group">
                      <span class="input-group-text bg-light border-0">
                        <i class="bi bi-person"></i>
                      </span>
                      <input type="text" class="form-control border-0 bg-light" name="txt_nome" id="txt_nome" required>
                    </div>
                  </div>
                  <div class="col-4">
                    <label for="txt_cpf" class="form-label">CPF</label>
                    <div class="input-group">
                      <span class="input-group-text bg-light border-0">
                        <i class="bi bi-person-vcard"></i>
                      </span>
                      <input type="text" class="form-control border-0 bg-light" name="txt_cpf" id="txt_cpf" maxlength="14" required>
                    </div>
                  </div>
                </div>

                <div class="row mb-3">
                  <div class="col-4">
                    <label for="txt_data_nascimento" class="form-label">Data de Nascimento</label>
                    <div class="input-group">
                      <span class="input-group-text bg-light border-0">
                        <i class="bi bi-calendar-event"></i>
                      </span>
                      <input type="date" class="form-control border-0 bg-light" name="txt_data_nascimento" id="txt_data_nascimento" required>
                    </div>
                  </div>
                  <div class="col-8">
                    <label for="txt_email" class="form-label">Email</label>
                    <div class="input-group">
                      <span class="input-group-text bg-light border-0">
                        <i class="bi bi-envelope"></i>
                      </span>
                      <input type="email" class="form-control border-0 bg-light" name="txt_email" id="txt_email" required>
                    </div>
                  </div>
                </div>

                <div class="row mb-3">
                  <div class="col-12">
                    <label for="list_departamento" class="form-label">Departamento</label>
                    <div class="input-group">
                      <span class="input-group-text bg-light border-0">
                        <i class="bi bi-building"></i>
                      </span>
                      <select class="form-select border-0 bg-light" name="list_departamento" id="list_departamento" required>
                        <option value="">Escolha o departamento</option>
                        <?php
                        function getDepartamentos()
                        {
                          include_once 'src/class/BancodeDados.php';
                          $banco = new BancodeDados();

                          $sql = 'SELECT id_departamento, nome 
                                FROM departamentos 
                                WHERE ativo = 1';
                          $departamentos = $banco->Consultar($sql, [], true);

                          return $departamentos;
                        }

                        $departamentos = getDepartamentos();
                        if ($departamentos) {
                          foreach ($departamentos as $departamento) {
                            echo "<option value='{$departamento['id_departamento']}'>{$departamento['nome']}</option>";
                          }
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Endereço -->
            <div class="tab-pane fade" id="endereco">
              <div class="modal-body p-4">
                <div class="row mb-3">
                  <div class="col-4">
                    <label for="txt_cep" class="form-label">CEP</label>
                    <div class="input-group">
                      <span class="input-group-text bg-light border-0">
                        <i class="bi bi-mailbox"></i>
                      </span>
                      <input type="text" class="form-control border-0 bg-light" name="txt_cep" id="txt_cep" maxlength="9">
                      <button class="btn btn-primary rounded-end" onclick="consultarCep()">Buscar</button>
                    </div>
                  </div>
                </div>

                <div class="row mb-3">
                  <div class="col-9">
                    <label for="txt_rua" class="form-label">Rua</label>
                    <div class="input-group">
                      <span class="input-group-text bg-light border-0">
                        <i class="bi bi-signpost"></i>
                      </span>
                      <input type="text" class="form-control border-0 bg-light" name="txt_rua" id="txt_rua">
                    </div>
                  </div>
                  <div class="col-3">
                    <label for="txt_numero" class="form-label">Número</label>
                    <div class="input-group">
                      <span class="input-group-text bg-light border-0">
                        <i class="bi bi-123"></i>
                      </span>
                      <input type="text" class="form-control border-0 bg-light" name="txt_numero" id="txt_numero">
                    </div>
                  </div>
                </div>

                <div class="row mb-3">
                  <div class="col-7">
                    <label for="txt_complemento" class="form-label">Complemento</label>
                    <div class="input-group">
                      <span class="input-group-text bg-light border-0">
                        <i class="bi bi-house-add"></i>
                      </span>
                      <input type="text" class="form-control border-0 bg-light" name="txt_complemento" id="txt_complemento">
                    </div>
                  </div>
                  <div class="col-5">
                    <label for="txt_bairro" class="form-label">Bairro</label>
                    <div class="input-group">
                      <span class="input-group-text bg-light border-0">
                        <i class="bi bi-geo"></i>
                      </span>
                      <input type="text" class="form-control border-0 bg-light" name="txt_bairro" id="txt_bairro">
                    </div>
                  </div>
                </div>

                <div class="row mb-3">
                  <div class="col-2">
                    <label for="list_uf" class="form-label">UF</label>
                    <div class="input-group">
                      <span class="input-group-text bg-light border-0">
                        <i class="bi bi-map"></i>
                      </span>
                      <select class="form-select border-0 bg-light" id="list_uf" name="list_uf" onchange="carregarCidades(this.value)">
                        <option value=""></option>
                        <?php
                        $estados = [
                          "AC",
                          "AL",
                          "AP",
                          "AM",
                          "BA",
                          "CE",
                          "DF",
                          "ES",
                          "GO",
                          "MA",
                          "MT",
                          "MS",
                          "MG",
                          "PA",
                          "PB",
                          "PR",
                          "PE",
                          "PI",
                          "RJ",
                          "RN",
                          "RS",
                          "RO",
                          "RR",
                          "SC",
                          "SP",
                          "SE",
                          "TO"
                        ];
                        foreach ($estados as $uf) {
                          echo "<option value='$uf'>$uf</option>";
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-10">
                    <label for="list_cidade" class="form-label">Cidade</label>
                    <div class="input-group">
                      <span class="input-group-text bg-light border-0">
                        <i class="bi bi-building"></i>
                      </span>
                      <select class="form-select border-0 bg-light" id="list_cidade" name="list_cidade" required>
                        <option value="">Escolha...</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="modal-footer" style="background-color: #f7f7f7; padding: 15px;">
            <button type="reset" class="btn" style="border-radius: 5px; padding: 10px 20px;" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-success" style="border-radius: 5px; padding: 10px 20px;" onclick="cadastrar()">Salvar</button>
          </div>
        </form>
      </div>
    </div>
  </div>


  <!-- JQuery -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script>
    // abrir primeiro tab do modal
    $('#adicionar_colaborador').on('shown.bs.modal', function() {
      var tabDadosPessoais = new bootstrap.Tab(document.getElementById('dados-pessoais-tab'));
      tabDadosPessoais.show();
    });

    // Consultar CEP
    function consultarCep() {
      var cep = document.getElementById('txt_cep').value;
      cep = cep.replace(/[^a-zA-Z0-9]/g, '');
      $.ajax({
        type: 'get',
        dataType: 'json',
        url: 'https://viacep.com.br/ws/' + cep + '/json/',
        success: function(retorno) {
          document.getElementById('txt_rua').value = retorno.logradouro;
          document.getElementById('txt_bairro').value = retorno.bairro;
          document.getElementById('txt_complemento').value = retorno.complemento;
          document.getElementById('list_uf').value = retorno.uf;
          document.getElementById('list_uf').dispatchEvent(new Event('change'));

          setTimeout(function() {
            document.getElementById('list_cidade').value = retorno.localidade;
          }, 1000);
        },
        error: function(erro) {
          alert('Ocorreu um erro na requisição: ' + erro);
        }
      });
    }

    // Carregar Cidades
    function carregarCidades(uf) {
      $.ajax({
        type: 'get',
        dataType: 'json',
        url: 'https://servicodados.ibge.gov.br/api/v1/localidades/estados/' + uf + '/municipios',
        success: function(retorno) {
          document.getElementById('list_cidade').innerHTML = "<option value=''>Escolha...</option>";
          $.each(retorno, function(chave, valor) {
            var option = document.createElement('option');
            option.value = valor.nome;
            option.text = valor.nome;
            document.getElementById('list_cidade').appendChild(option);
          });
        },
        error: function(erro) {
          alert('Ocorreu um erro na requisição: ' + erro);
        }
      });
    }

    // Cadastro de Colaborador
    function cadastrar() {
      var id = document.getElementById('txt_id').value;
      var nome = document.getElementById('txt_nome').value;
      var cpf = document.getElementById('txt_cpf').value;
      var data_nascimento = document.getElementById('txt_data_nascimento').value;
      var departamento = document.getElementById('list_departamento').value;
      var email = document.getElementById('txt_email').value;
      var cep = document.getElementById('txt_cep').value;
      var rua = document.getElementById('txt_rua').value;
      var numero = document.getElementById('txt_numero').value;
      var bairro = document.getElementById('txt_bairro').value;
      var complemento = document.getElementById('txt_complemento').value;
      var uf = document.getElementById('list_uf').value;
      var cidade = document.getElementById('list_cidade').value;

      $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'src/colaboradores/cadastrar_colaborador.php',
        data: {
          'id': id,
          'nome': nome,
          'cpf': cpf,
          'data_nascimento': data_nascimento,
          'departamento': departamento,
          'email': email,
          'cep': cep,
          'rua': rua,
          'numero': numero,
          'bairro': bairro,
          'complemento': complemento,
          'uf': uf,
          'cidade': cidade
        },
        success: function(retorno) {
          if (retorno['status'] == 'ok') {
            alert('Colaborador cadastrado com sucesso!');
            window.location.reload();
          } else if (retorno['status'] == 'ok_atualizar') {
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
          if (retorno['status'] == 'ok') {
            document.getElementById('txt_id').value = retorno['dados']['id_colaborador'];
            document.getElementById('txt_nome').value = retorno['dados']['nome'];
            document.getElementById('txt_cpf').value = retorno['dados']['cpf'];
            document.getElementById('txt_data_nascimento').value = retorno['dados']['data_nascimento'];
            document.getElementById('list_departamento').value = retorno['dados']['id_departamento'];
            document.getElementById('txt_email').value = retorno['dados']['email'];
            document.getElementById('txt_cep').value = retorno['dados']['cep'];
            document.getElementById('txt_rua').value = retorno['dados']['endereco'];
            document.getElementById('txt_numero').value = retorno['dados']['numero'];
            document.getElementById('txt_bairro').value = retorno['dados']['bairro'];
            document.getElementById('txt_complemento').value = retorno['dados']['complemento'];
            document.getElementById('list_uf').value = retorno['dados']['uf'];
            document.getElementById('list_uf').dispatchEvent(new Event('change'));

            // tive que fazer essa gambiarra para carregar a cidade depois do evento de carregar uf ter ocorrido
            setTimeout(function() {
              document.getElementById('list_cidade').value = retorno['dados']['cidade'];
            }, 500);

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
            if (retorno['status'] == 'ok') {
              registrarLog('Exclusão de Colaborador - ID: ' + idColaborador);
              window.location.reload();
            }
          },
          error: function(erro) {
            alert('Ocorreu um erro na requisição: ' + erro);
          }
        });
      }
    }

    // Reativar Colaborador
    function reativar(idColaborador) {
      $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'src/colaboradores/reativar_colaborador.php',
        data: {
          'id_colaborador': idColaborador
        },
        success: function(retorno) {
          alert(retorno['mensagem']);
          if (retorno['status'] == 'ok') {
            registrarLog('Reativação de Colaborador - ID: ' + idColaborador);
            window.location.reload();
          }
        },
        error: function(erro) {
          alert('Ocorreu um erro na requisição: ' + erro);
        }
      });
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