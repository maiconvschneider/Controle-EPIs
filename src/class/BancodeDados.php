<?php
class BancodeDados
{
  public $conexao;

  function __construct()
  {
    $this->conexao = new PDO('mysql:host=localhost;dbname=db_epis;port=3307;charset=utf8mb4', 'root', '');
  }
  
  public function ExecutarComando($sql, $parametros = [])
  {
    try {
      $stmt = $this->conexao->prepare($sql);
      $stmt->execute($parametros);
      return $stmt->rowCount();
    } catch (PDOException $e) {
      throw new Exception("Erro ao executar comando: " . $e->getMessage());
    }
  }

  public function Consultar($sql, $parametros = [], $fetchall = false)
  {
    $stmt = $this->conexao->prepare($sql);
    $stmt->execute($parametros);
    if ($fetchall) {
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
      return $stmt->fetch(PDO::FETCH_ASSOC);
    }
  }

  public function iniciarTransacao()
  {
    $this->conexao->beginTransaction();
  }

  public function confirmarTransacao()
  {
    if ($this->conexao->inTransaction()) {
      $this->conexao->commit();
    }
  }

  public function reverterTransacao()
  {
    if ($this->conexao->inTransaction()) {
      $this->conexao->rollBack();
    }
  }

  public function fecharConexao()
  {
    $this->conexao = null;
  }
}
