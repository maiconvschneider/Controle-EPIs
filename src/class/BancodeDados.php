<?php
class BancodeDados
{
  public $conexao;

  function __construct()
  {
    $this->conexao = new PDO('mysql:host=localhost;dbname=db_epis;charset=utf8mb4', 'root', '');
  }
  public function ExecutarComando($sql, $parametros = [])
  {
    $stmt = $this->conexao->prepare($sql);
    $stmt->execute($parametros);
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
}
