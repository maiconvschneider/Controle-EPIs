<?php
// Verificação de sessão
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
  <title>Cadastro - EXEMPLO</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      margin: 0;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background-color: #f8f9fa;
    }

    .form-cadastro {
      width: 100%;
      max-width: 400px;
      padding: 20px;
      background-color: white;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
  </style>
</head>

<body>
  <main class="form-cadastro">
    <form id="form-cadastro">
      <h1 class="h3 mb-3 fw-normal">Cadastrar Usuário</h1>
      <div class="form-floating">
        <input type="text" class="form-control" id="txt_nome" required>
        <label for="txt_nome">Nome</label>
      </div>
      <div class="form-floating">
        <input type="text" class="form-control" id="txt_usuario" required>
        <label for="txt_usuario">Usuário</label>
      </div>
      <div class="form-floating">
        <input type="password" class="form-control" id="txt_senha" required>
        <label for="txt_senha">Senha</label>
      </div>
      <hr>
      <button type="button" class="btn btn-primary w-100 py-2" onclick="cadastrar()">Cadastrar</button>
      <p class="mt-5 mb-3 text-body-secondary text-center"><a href="index.php">Já tem uma conta? Faça login</a></p>
    </form>
  </main>

  <!-- JQuery -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script>
    function cadastrar() {
      var nome = document.getElementById('txt_nome').value;
      var usuario = document.getElementById('txt_usuario').value;
      var senha = document.getElementById('txt_senha').value;

      $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'src/usuarios/cadastrar_usuario.php',
        data: {
          'nome': nome,
          'usuario': usuario,
          'senha': senha
        },
        success: function(retorno) {
          if (retorno['codigo'] == 2) {
            alert('Usuário cadastrado com sucesso!');
            window.location = '../index.php';
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