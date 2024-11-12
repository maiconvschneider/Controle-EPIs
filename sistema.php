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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    .dashboard-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 1.5rem;
      padding: 1.5rem;
      max-width: 1200px;
      margin: 0 auto;
    }

    .module-card {
      background: white;
      border-radius: 10px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
      text-decoration: none;
      color: inherit;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 2rem;
      cursor: pointer;
    }

    .module-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      color: inherit;
    }

    .module-icon {
      width: 60px;
      height: 60px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 12px;
      margin-bottom: 1rem;
      transition: transform 0.3s ease;
    }

    .module-card:hover .module-icon {
      transform: scale(1.1);
    }

    .module-icon i {
      font-size: 1.8rem;
      color: white;
    }

    .module-title {
      font-size: 1.2rem;
      font-weight: 500;
      text-align: center;
      margin: 0;
    }

    .bg-home {
      background-color: #4A90E2;
    }

    .bg-users {
      background-color: #50C878;
    }

    .bg-departments {
      background-color: #9B59B6;
    }

    .bg-employees {
      background-color: #F1C40F;
    }

    .bg-epis {
      background-color: #E74C3C;
    }

    .bg-loans {
      background-color: #5D4FBF;
    }

    .bg-exit {
      background-color: #7F8C8D;
    }

    .welcome-section {
      text-align: center;
      padding: 2rem;
      margin-bottom: 1rem;
    }

    .welcome-section h1 {
      font-size: 2rem;
      color: #2c3e50;
      margin-bottom: 1rem;
    }

    body {
      background-color: #f8f9fa;
      min-height: 100vh;
    }

    .user-info {
      text-align: center;
      margin-top: 2rem;
      color: #666;
    }
  </style>
</head>

<body>
  <div class="container-fluid">
    <main class="px-md-4">
      <?php
      $tela = isset($_GET['tela']) ? $_GET['tela'] : '';

      if (empty($tela)) {
        // Mostrar dashboard quando não houver tela específica
      ?>
        <div class="welcome-section">
          <h1>Bem-vindo ao MyEPI's</h1>
        </div>

        <div class="dashboard-grid">
          <a href="sistema.php" class="module-card">
            <div class="module-icon bg-home">
              <i class="bi bi-house-door"></i>
            </div>
            <h2 class="module-title">Home</h2>
          </a>

          <a href="sistema.php?tela=usuarios" class="module-card">
            <div class="module-icon bg-users">
              <i class="bi bi-people"></i>
            </div>
            <h2 class="module-title">Usuários</h2>
          </a>

          <a href="sistema.php?tela=departamentos" class="module-card">
            <div class="module-icon bg-departments">
              <i class="bi bi-grid"></i>
            </div>
            <h2 class="module-title">Departamentos</h2>
          </a>

          <a href="sistema.php?tela=colaboradores" class="module-card">
            <div class="module-icon bg-employees">
              <i class="bi bi-person-badge"></i>
            </div>
            <h2 class="module-title">Colaboradores</h2>
          </a>

          <a href="sistema.php?tela=epis" class="module-card">
            <div class="module-icon bg-epis">
              <i class="bi bi-shield"></i>
            </div>
            <h2 class="module-title">EPI's</h2>
          </a>

          <a href="sistema.php?tela=emprestimos" class="module-card">
            <div class="module-icon bg-loans">
              <i class="bi bi-arrow-left-right"></i>
            </div>
            <h2 class="module-title">Empréstimos</h2>
          </a>

          <a href="#" onclick="sair(); return false;" class="module-card">
            <div class="module-icon bg-exit">
              <i class="bi bi-box-arrow-right"></i>
            </div>
            <h2 class="module-title">Sair</h2>
          </a>
        </div>

        <div class="user-info">
          <h5><?php echo $nomeUsuario; ?></h5>
          <p><?php echo isset($_SESSION['data_hora_login']) ? $_SESSION['data_hora_login'] : ''; ?></p>
        </div>
      <?php
      } else {
        // Carregar as telas específicas baseado no parâmetro
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
        }
      }
      ?>
    </main>
  </div>

  <!-- JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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