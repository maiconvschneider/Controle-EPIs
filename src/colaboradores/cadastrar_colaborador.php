<?php
// Validação
$id = isset($_POST['id']) ? $_POST['id'] : '';
$nome = isset($_POST['nome']) ? $_POST['nome'] : '';
$matricula = isset($_POST['matricula']) ? $_POST['matricula'] : '';
$id_departamento = $_POST['departamento'] ?? null;
$email = isset($_POST['email']) ? $_POST['email'] : '';

// Endereço
$cep = isset($_POST['cep']) ? $_POST['cep'] : '';
$endereco = isset($_POST['rua']) ? $_POST['rua'] : '';
$numero = isset($_POST['numero']) ? $_POST['numero'] : '';
$complemento = isset($_POST['complemento']) ? $_POST['complemento'] : '';
$bairro = isset($_POST['bairro']) ? $_POST['bairro'] : '';
$uf = isset($_POST['uf']) ? $_POST['uf'] : '';
$cidade = isset($_POST['cidade']) ? $_POST['cidade'] : '';

if (empty($nome) || empty($matricula) || empty($id_departamento) || empty($email)) {
  $resposta = [
    'status' => 'erro',
    'mensagem' => 'Por favor, preencha todos os campos!'
  ];
  echo json_encode($resposta);
  exit;
}

// Verifica se o email é válido (by stack overflow)
// https://pt.stackoverflow.com/questions/8134/verificar-se-variável-contém-um-endereço-de-email-bem-formatado-em-php
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $resposta = [
    'status' => 'erro',
    'mensagem' => 'Por favor, insira um email válido!'
  ];
  echo json_encode($resposta);
  exit;
}

if (!empty($cep) || !empty($endereco) || !empty($bairro) || !empty($uf) || !empty($cidade) || !empty($numero) || !empty($complemento)) {
  $preencheu_endereco = true;
} else {
  $preencheu_endereco = false;
}

// Conexão com o banco de dados
try {
  include '../class/BancoDeDados.php';
  $banco = new BancoDeDados;

  if ($id == 'NOVO') { // Cadastrar
    $sql = 'INSERT INTO colaboradores (nome, matricula, id_departamento, email) VALUES (?, ?, ?, ?)';
    $parametros = [$nome, $matricula, $id_departamento, $email];
    $banco->ExecutarComando($sql, $parametros);

    // Pegar o ID do colaborador cadastrado
    $id_colaborador = $banco->conexao->lastInsertId();

    $resposta = ['status' => 'sucesso'];
  } else { // Atualizar
    $sql = 'UPDATE colaboradores SET nome = ?, matricula = ?, id_departamento = ?, email = ? WHERE id_colaborador = ?';
    $parametros = [$nome, $matricula, $id_departamento, $email, $id];
    $banco->ExecutarComando($sql, $parametros);

    $id_colaborador = $id;
    $resposta = ['status' => 'sucesso'];
  }

  // verificar se o colaborador já tem um endereço cadastrado
  $sql = 'SELECT COUNT(*) AS total_endereco FROM colaboradores_endereco WHERE id_colaborador = ?';
  $parametros = [$id_colaborador];
  $resultado = $banco->Consultar($sql, $parametros);
  $existe_endereco = $resultado['total_endereco'] > 0;

  if ($existe_endereco) { // Atualizar endereço
    $sql = 'UPDATE colaboradores_endereco SET cep = ?, endereco = ?, numero = ?, complemento = ?, bairro = ?, uf = ?, cidade = ? WHERE id_colaborador = ?';
    $parametros = [$cep, $endereco, $numero, $complemento, $bairro, $uf, $cidade, $id_colaborador];
    $banco->ExecutarComando($sql, $parametros);
  } else if ($preencheu_endereco) {
    $sql = 'INSERT INTO colaboradores_endereco (id_colaborador, cep, endereco, numero, complemento, bairro, uf, cidade) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
    $parametros = [$id_colaborador, $cep, $endereco, $numero, $complemento, $bairro, $uf, $cidade];
    $banco->ExecutarComando($sql, $parametros);
  }

  echo json_encode($resposta);
} catch (PDOException $erro) {
  $resposta = [
    'status' => 'erro',
    'mensagem' => 'Houve uma exceção no banco de dados: ' . $erro->getMessage()
  ];
  echo json_encode($resposta);
}
