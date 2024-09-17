<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Controle de EPI's</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
            <div class="sidebar border border-right col-md-3 col-lg-2 p-0 bg-body-tertiary">
                <div class="offcanvas-md offcanvas-end bg-body-tertiary" tabindex="-1" id="sidebarMenu">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="">TEItech</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu"></button>
                    </div>
                    <div class="offcanvas-body d-md-flex flex-column p-0 pt-lg-3 overflow-y-auto">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2" href="index.php">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2" href="index.php?tela=usuarios">Usuários</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2" href="index.php?tela=colaboradores">Colaboradores</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2" href="index.php?tela=epis">EPI's</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2" href="index.php?tela=emprestimos">Empréstimos</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <!-- Script para importar as telas do sistema -->
                <?php
                $tela = isset($_GET['tela']) ? $_GET['tela'] : '';
                echo $tela;
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
        function excluir(idUsuario) {
            var confirmou = confirm('Deseja realmente exlcuir este usuário?');
            if (confirmou) {
                window.location = 'src/excluir_usuario.php?idUsuario=' + idUsuario;
            }
        }
    </script>
    <?php
    if (isset($_GET['idUsuario'])) {
        $id_usuario = $_GET['idUsuario'];
        try {
            include_once 'src/class/BancodeDados.php';
            $banco = new BancodeDados;
            $sql = 'SELECT *  FROM usuarios WHERE id_usuario = ?';
            $parametros = [$id_usuario];
            $dados = $banco->Consultar($sql, $parametros);
            if ($dados) {
                echo
                "<script>
                        document.getElementById('txt_id').value = '{$dados['id_usuario']}';
                        document.getElementById('txt_nome').value = '{$dados['nome']}';
                        document.getElementById('txt_usuario').value = '{$dados['usuario']}';
                        document.getElementById('txt_senha').value = '{$dados['senha']}';
                    </script>";
            }
        } catch (PDOException $erro) {
            $msg = $erro->getMessage();
            echo
            "<script>
                    alert(\"$msg\");
                </script>";
        }
    }
    ?>
</body>

</html>