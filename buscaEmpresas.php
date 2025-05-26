<?php
include_once 'conexaoDatabase.php';

$termo = $_GET['termo'] ?? '';

if (strlen($termo) < 2) {
  echo json_encode([]);
  exit;
}

$termo = mysqli_real_escape_string($conexao, $termo);
$sql = "SELECT idEmpresa, nomeEmpresa FROM empresa WHERE nomeEmpresa LIKE '%$termo%' LIMIT 10";
$result = mysqli_query($conexao, $sql);

$empresas = [];
while ($row = mysqli_fetch_assoc($result)) {
  $empresas[] = [
    'id'=>$row['idEmpresa'],
    'nome'=>$row['nomeEmpresa']
  ]; 
}

echo json_encode($empresas);
