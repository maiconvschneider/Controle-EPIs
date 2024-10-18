<?php
session_start();

if (!isset($_SESSION['logado'])) {
  header('LOCATION: index.php');
  exit;
}

$nomeUsuario = isset($_SESSION['nome_usuario']) ? $_SESSION['nome_usuario'] : 'Usuário';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Controle de EPI's</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
  <header class="navbar sticky-top bg-dark flex-md-nowrap p-0 shadow" data-bs-theme="dark">
    <ul class="navbar-nav flex-row d-md-none">
      <li class="nav-item text-nowrap">
        <button class="nav-link px-3 text-white" type="button" data-bs-toggle="offcanvas"
          data-bs-target="#sidebarMenu">
          <svg class="bi">
            <use xlink:href="#list">
          </svg>
        </button>
      </li>
    </ul>
  </header>
  <div class="container-fluid">
    <div class="row">
      <div class="sidebar col-md-3 col-lg-2 p-0 bg-light border-end shadow-sm" style="height: 100vh;">
        <div class="offcanvas-md offcanvas-end bg-light" tabindex="-1" id="sidebarMenu">
          <div class="offcanvas-body d-flex flex-column p-0 pt-lg-3 overflow-y-auto" style="height: 100vh;">
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-2" href="sistema.php" style="padding: 15px; font-weight: 500;">
                  <i class="bi bi-house-door"></i> Home
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-2" href="sistema.php?tela=usuarios" style="padding: 15px; font-weight: 500;">
                  <i class="bi bi-people"></i> Usuários
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-2" href="sistema.php?tela=departamentos" style="padding: 15px; font-weight: 500;">
                  <i class="bi bi-grid"></i> Departamentos
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-2" href="sistema.php?tela=colaboradores" style="padding: 15px; font-weight: 500;">
                  <i class="bi bi-person-badge"></i> Colaboradores
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-2" href="sistema.php?tela=epis" style="padding: 15px; font-weight: 500;">
                  <i class="bi bi-shield"></i> EPI's
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-2" href="sistema.php?tela=emprestimos" style="padding: 15px; font-weight: 500;">
                  <i class="bi bi-arrow-left-right"></i> Empréstimos
                </a>
              </li>
            </ul>
            <hr class="my-3">
            <ul class="nav flex-column mb-auto">
              <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-2" href="#" onclick="sair()" style="padding: 15px; font-weight: 500;">
                  <i class="bi bi-box-arrow-right"></i> Sair
                </a>
              </li>
            </ul>
            <div class="footer text-center mt-auto p-3">
              <hr>
              <h5><?php echo $nomeUsuario; ?></h5>
              <p><?php echo isset($_SESSION['data_hora_login']) ? $_SESSION['data_hora_login'] : ''; ?></p>
            </div>
          </div>
        </div>
      </div>
      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <!-- Script para importar as telas do sistema -->
        <?php
        $tela = isset($_GET['tela']) ? $_GET['tela'] : '';
        switch ($tela) {
          case 'usuarios':
            include 'telas/usuarios.php';
            break;

          case 'departamentos':
            include 'telas/departamentos.php';
            break;

          case 'colaboradores':
            include 'telas/colaboradores.php';
            break;

          case 'epis':
            include 'telas/epis.php';
            break;

          case 'emprestimos':
            include 'telas/emprestimos.php';
            break;

          default:
            echo '<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class>Bem-vindo ao sistema, ' . $nomeUsuario . '!</h1>
                  </div>';

            echo '<div class="d-flex justify-content-center align-items-center">
                  <img src="img/logo.jpg" alt="logo" style="width: 90%; max-width: 1300px;">
                </div>';
            break;
        }
        ?>
      </main>
    </div>
  </div>

  <!-- JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script>
    function sair() {
      var confirmou = confirm('Deseja realmente sair do sistema?');
      if (confirmou) {
        window.location = 'src/logout.php';
      }
    }
  </script>
</body>

</html>