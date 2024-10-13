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
        $sql = 'SELECT c.*, d.nome as departamento 
                FROM colaboradores c 
                LEFT JOIN departamentos d on d.id_departamento = c.id_departamento 
                WHERE c.ativo = 1';
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
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <form id="form_colaborador" method="post" enctype="multipart/form-data">
        <div class="modal-header" style="background-color: #435d7d; color: #fff;">
          <h4 class="modal-title">Colaborador</h4>
          <button type="reset" class="btn-close" data-bs-dismiss="modal" aria-hidden="true" style="color: #fff; font-size: 1.2rem; opacity: 0.8;"></button>
        </div>

        <!-- Divisao por tabs -->
        <ul class="nav nav-tabs" id="tabContent">
          <li class="nav-item">
            <a class="nav-link active" id="dados-pessoais-tab" data-bs-toggle="tab" href="#dados-pessoais">*Dados Pessoais</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="endereco-tab" data-bs-toggle="tab" href="#endereco">Endereço</a>
          </li>
        </ul>

        <div class="tab-content">
          <!-- Dados Pessoais -->
          <div class="tab-pane fade show active" id="dados-pessoais">
            <div class="modal-body" style="padding: 20px;">
              <input type="hidden" name="txt_id" id="txt_id" value="NOVO">
              <div class="form-group row mb-3">
                <div class="col-8">
                  <label for="txt_nome" class="form-label">Nome</label>
                  <input type="text" class="form-control" name="txt_nome" id="txt_nome" required style="border-radius: 5px;">
                </div>
                <div class="col-4">
                  <label for="txt_matricula" class="form-label">Matrícula</label>
                  <input type="text" class="form-control" name="txt_matricula" id="txt_matricula" required style="border-radius: 5px;">
                </div>
              </div>
              <div class="form-group row mb-3">
                <div class="col-6">
                  <label for="list_departamento" class="form-label">Departamento</label>
                  <select class="form-select" name="list_departamento" id="list_departamento" required style="border-radius: 5px;">
                    <option value="">Escolha o departamento</option>
                    <?php
                    function getDepartamentos()
                    {
                      include_once 'src/class/BancodeDados.php';
                      $banco = new BancodeDados();

                      $sql = 'SELECT id_departamento, nome FROM departamentos';
                      $departamentos = $banco->Consultar($sql, [], true);

                      return $departamentos;
                    }

                    $departamentos = getDepartamentos();
                    if ($departamentos) {
                      foreach ($departamentos as $departamento) {
                        $id_departamento = $departamento['id_departamento'];
                        $nome = $departamento['nome'];

                        echo "<option value='$id_departamento'>$nome</option>";
                      }
                    }
                    ?>
                  </select>
                </div>
                <div class="col-6">
                  <label for="txt_email" class="form-label">Email</label>
                  <input type="email" class="form-control" name="txt_email" id="txt_email" required style="border-radius: 5px;">
                </div>
              </div>
            </div>
          </div>

          <!-- Dados de Endereço -->
          <div class="tab-pane fade" id="endereco">
            <div class="modal-body" style="padding: 20px;">
              <div class="form-group row mb-3">
                <div class="col-4">
                  <label for="txt_cep" class="form-label">CEP</label>
                  <div class="input-group">
                    <input type="text" class="form-control" name="txt_cep" id="txt_cep" style="border-radius: 5px;" maxlength="9">
                    <button class="btn btn-primary" onclick="consultarCep()">Buscar</button>
                  </div>
                </div>
              </div>
              <div class="form-group row mb-3">
                <div class="col-9">
                  <label for="txt_rua" class="form-label">Rua</label>
                  <input type="text" class="form-control" name="txt_rua" id="txt_rua" style="border-radius: 5px;">
                </div>
                <div class="col-3">
                  <label for="txt_numero" class="form-label">Número</label>
                  <input type="text" class="form-control" name="txt_numero" id="txt_numero" style="border-radius: 5px;">
                </div>
              </div>
              <div class="form-group row mb-3">
                <div class="col-7">
                  <label for="txt_complemento" class="form-label">Complemento</label>
                  <input type="text" class="form-control" name="txt_complemento" id="txt_complemento" style="border-radius: 5px;">
                </div>
                <div class="col-5">
                  <label for="txt_bairro" class="form-label">Bairro</label>
                  <input type="text" class="form-control" name="txt_bairro" id="txt_bairro" style="border-radius: 5px;">
                </div>
              </div>
              <div class="form-group row mb-3">
                <div class="col-2">
                  <label for="list_uf" class="form-label">UF</label>
                  <select class="form-select" id="list_uf" name="list_uf" onchange="carregarCidades(this.value)">
                    <option></option>
                    <option value="AC">AC</option>
                    <option value="AL">AL</option>
                    <option value="AP">AP</option>
                    <option value="AM">AM</option>
                    <option value="BA">BA</option>
                    <option value="CE">CE</option>
                    <option value="DF">DF</option>
                    <option value="ES">ES</option>
                    <option value="GO">GO</option>
                    <option value="MA">MA</option>
                    <option value="MT">MT</option>
                    <option value="MS">MS</option>
                    <option value="MG">MG</option>
                    <option value="PA">PA</option>
                    <option value="PB">PB</option>
                    <option value="PR">PR</option>
                    <option value="PE">PE</option>
                    <option value="PI">PI</option>
                    <option value="RJ">RJ</option>
                    <option value="RN">RN</option>
                    <option value="RS">RS</option>
                    <option value="RO">RO</option>
                    <option value="RR">RR</option>
                    <option value="SC">SC</option>
                    <option value="SP">SP</option>
                    <option value="SE">SE</option>
                    <option value="TO">TO</option>
                  </select>
                </div>
                <div class="col-10">
                  <label for="list_cidade" class="form-label">Cidade</label>
                  <select class="form-select" id="list_cidade" name="list_cidade" style="border-radius: 5px;">
                    <option value="">Escolha...</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Botões de navegação -->
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
  $('#form_colaborador').submit(function() {
    return false;
  });

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

        // Atrasando a execução do comando
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
    var matricula = document.getElementById('txt_matricula').value;
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
        'matricula': matricula,
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
      error: function(jqXHR, textStatus, errorThrown) {
        var errorMessage = `
                Ocorreu um erro na requisição:
                \nStatus: ${jqXHR.status} - ${jqXHR.statusText}
                \nErro: ${textStatus}
                \nMensagem: ${errorThrown}
                \nResposta do servidor: ${jqXHR.responseText}
            `;
        console.error(errorMessage);
        alert(errorMessage);
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
          if (retorno['codigo'] == 2) {
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