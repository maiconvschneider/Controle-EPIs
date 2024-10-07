<?php
// Destruindo a sessão
session_start();
session_destroy();

setcookie('usuario', '', time() - 3600, "/");
setcookie('nome_usuario', '', time() - 3600, "/");

// Redirecionar
header('LOCATION: ../index.php');
