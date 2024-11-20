<?php
session_start();
if (isset($_SESSION['logado'])) {
  header('LOCATION: sistema.php');
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MyEPI's Login</title>
  <!-- <link href="assets/css/index.css" rel="stylesheet"> -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <style>
    body {
      margin: 0;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background-color: #f4f6f9;
    }

    .form-login {
      width: 100%;
      max-width: 420px;
      padding: 30px;
      background-color: #ffffff;
      border-radius: 10px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .form-login h2 {
      font-size: 2rem;
      color: #435d7d;
      margin-bottom: 20px;
    }

    .form-login .form-floating input {
      border-radius: 10px;
    }

    .form-login button {
      border-radius: 10px;
      font-size: 1rem;
    }

    .form-login .text-muted {
      color: #6c757d;
    }

    .form-login .text-body-secondary {
      color: #adb5bd;
    }

    .form-login .btn-primary {
      background-color: #435d7d;
      border-color: #435d7d;
    }

    .form-login .btn-primary:hover {
      background-color: #3a506b;
      border-color: #3a506b;
    }
  </style>
</head>

<body>
  <main class="form-login">
    <form id="form-login" onsubmit="return false">
      <h2 class="text-center">MyEPI's Login</h2>
      <hr>
      <div class="form-floating mb-3">
        <input type="text" class="form-control" id="txt_usuario" required>
        <label for="txt_usuario">Usuário</label>
      </div>
      <div class="form-floating mb-3">
        <input type="password" class="form-control" id="txt_senha" required>
        <label for="txt_senha">Senha</label>
      </div>
      <button type="button" class="btn btn-primary w-100 py-2" onclick="entrar()">Entrar</button>
      <p class="mt-3 text-body-secondary text-center">
        <a href="cadastro-usuario.php">Não tem uma conta? Cadastre-se</a>
      </p>
      <p class="mt-5 mb-3 text-body-secondary text-center">MyEPI's &copy; 2024</p>
    </form>
  </main>

  <!-- JQuery -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <!-- Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Login
    function entrar() {
      var usuario = document.getElementById('txt_usuario').value;
      var senha = document.getElementById('txt_senha').value;

      $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'src/login.php',
        data: {
          'usuario': usuario,
          'senha': senha
        },
        success: function(retorno) {
          if (retorno['status'] == 'ok') {
            window.location = 'sistema.php';
          } else {
            alert(retorno['mensagem']);
          }
        },
        error: function(erro) {
          alert('Ocorreu um erro na requisição: ' + erro);
        }
      });
    }
  </script>
</body>

</html>