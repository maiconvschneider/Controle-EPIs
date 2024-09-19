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
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6 text-white text-center" href="#">Controle de EPI's</a>
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
                    <div class="offcanvas-body d-md-flex flex-column p-0 pt-lg-3 overflow-y-auto">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2" href="index.php" style="padding: 15px; font-weight: 500;">
                                    <i class="bi bi-house-door"></i> Home
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2" href="index.php?tela=usuarios" style="padding: 15px; font-weight: 500;">
                                    <i class="bi bi-people"></i> Usuários
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2" href="index.php?tela=colaboradores" style="padding: 15px; font-weight: 500;">
                                    <i class="bi bi-person-badge"></i> Colaboradores
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2" href="index.php?tela=epis" style="padding: 15px; font-weight: 500;">
                                    <i class="bi bi-shield"></i> EPI's
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2" href="index.php?tela=emprestimos" style="padding: 15px; font-weight: 500;">
                                    <i class="bi bi-arrow-left-right"></i> Empréstimos
                                </a>
                            </li>
                        </ul>
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
                            <h1 class="h2"><strong>Bem-vindo ao sistema!</h1>
                        </div>';
                        break;
                }
                ?>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        function excluirUsuario(idUsuario) {
            var confirmou = confirm('Deseja realmente exlcuir este usuário?');
            if (confirmou) {
                window.location = 'src/usuario/excluir_usuario.php?idUsuario=' + idUsuario;
            }
        }

        function excluirColaborador(idColaborador) {
            var confirmou = confirm('Deseja realmente exlcuir este colaborador?');
            if (confirmou) {
                window.location = 'src/colaboradores/excluir_colaborador.php?idColaborador=' + idColaborador;
            }
        }
    </script>
</body>

</html>