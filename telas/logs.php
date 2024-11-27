<div class="container-fluid py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <a href="sistema.php" class="btn btn-outline-primary btn-lg rounded-circle shadow-sm">
      <i class="bi bi-arrow-left"></i>
    </a>
    <h1 class="mb-0">Histórico de Logs</h1>
    <div></div>
  </div>

  <!-- Estatísticas -->
  <div class="row mb-4">
    <div class="col-md-4">
      <div class="card shadow-sm border-0 mb-3">
        <div class="card-body d-flex align-items-center">
          <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3">
            <i class="bi bi-journal-text text-info" style="font-size: 2rem;"></i>
          </div>
          <div>
            <h6 class="text-muted mb-1">Total de Logs</h6>
            <h4 class="mb-0">
              <?php
              include_once 'src/class/BancodeDados.php';
              $banco = new BancodeDados;

              // Total de logs
              $sql = 'SELECT COUNT(*) AS total 
                      FROM logs';
              $total = $banco->Consultar($sql, [], true);
              echo $total[0]['total'];
              ?>
            </h4>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Logs do Sistema -->
  <div class="card shadow-sm border-0">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th scope="col">ID</th>
              <th scope="col">Usuário</th>
              <th scope="col">Ação</th>
              <th scope="col">Data</th>
            </tr>
          </thead>
          <tbody>
            <?php
            try {
              include_once 'src/class/BancodeDados.php';
              $banco = new BancodeDados;
              $sql = 'SELECT l.id_log, usuarios.nome AS usuario, l.acao, l.data 
                      FROM logs l 
                      LEFT JOIN usuarios ON l.id_usuario = usuarios.id_usuario
                      ORDER BY l.data DESC';
              $dados = $banco->Consultar($sql, [], true);
              if ($dados) {
                foreach ($dados as $linha) {
                  echo "
                  <tr>
                    <td>{$linha['id_log']}</td>
                    <td>
                      <div class='d-flex align-items-center'>
                        <div class='rounded-circle bg-light p-2 me-2'>
                          <i class='bi bi-person'></i>
                        </div>
                        {$linha['usuario']}
                      </div>
                    </td>
                    <td>{$linha['acao']}</td>
                    <td>" . date('d/m/Y H:i:s', strtotime($linha['data'])) . "</td>
                  </tr>";
                }
              } else {
                echo "
                <tr>
                  <td colspan='4' class='text-center py-4'>
                    <div class='text-muted'>
                      <i class='bi bi-inbox-fill' style='font-size: 2rem;'></i>
                      <p class='mt-2 mb-0'>Nenhum log encontrado...</p>
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
  </div>
</div>