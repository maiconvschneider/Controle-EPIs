<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MyEPI's Cadastro</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      margin: 0;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background-color: #f4f6f9;
    }

    .form-cadastro {
      width: 100%;
      max-width: 420px;
      padding: 30px;
      background-color: #ffffff;
      border-radius: 10px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .form-cadastro h2 {
      font-size: 2rem;
      color: #435d7d;
      margin-bottom: 20px;
    }

    .form-cadastro .form-floating input {
      border-radius: 10px;
    }

    .form-cadastro button {
      border-radius: 10px;
      font-size: 1rem;
    }

    .form-cadastro .text-muted {
      color: #6c757d;
    }

    .form-cadastro .text-body-secondary {
      color: #adb5bd;
    }

    .form-cadastro .btn-primary {
      background-color: #435d7d;
      border-color: #435d7d;
    }

    .form-cadastro .btn-primary:hover {
      background-color: #3a506b;
      border-color: #3a506b;
    }
  </style>
</head>

<body>
  <main class="form-cadastro">
    <form id="form-cadastro" onsubmit="return false">
      <h2 class="text-center">MyEPI's Cadastro</h2>
      <hr>
      <div class="form-floating mb-3">
        <input type="text" class="form-control" id="txt_nome" required>
        <label for="txt_nome">Nome</label>
      </div>
      <div class="form-floating mb-3">
        <input type="text" class="form-control" id="txt_usuario" required>
        <label for="txt_usuario">Usuário</label>
      </div>
      <div class="form-floating mb-3">
        <input type="password" class="form-control" id="txt_senha" required>
        <label for="txt_senha">Senha</label>
      </div>
      <hr>
      <button type="button" class="btn btn-primary w-100 py-2" onclick="cadastrar()">Cadastrar</button>
      <p class="mt-3 text-body-secondary text-center"><a href="index.php">Já tem uma conta? Faça login</a></p>
      <p class="mt-5 mb-3 text-body-secondary text-center">MyEPI's &copy; 2024</p>
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
          'id': 'NOVO',
          'nome': nome,
          'usuario': usuario,
          'senha': senha
        },
        success: function(retorno) {
          if (retorno['status'] == 'ok') {
            alert('Usuário cadastrado com sucesso!');
            window.location = 'index.php';
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