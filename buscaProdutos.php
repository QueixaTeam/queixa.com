<?php
include_once 'conexaoDatabase.php';

header('Content-Type: application/json');

$termo = $_GET['termo'] ?? '';
$empresa = $_GET['empresaSelecionada'] ?? '';

if (strlen($termo) < 2 || empty($empresa)) {
  echo json_encode([]);
  exit;
}

$sql = "SELECT idProduto, nomeProduto FROM produto 
        WHERE idEmpresa = $empresa 
          AND nomeProduto LIKE '%$termo%' 
        LIMIT 10";


//$stmt = mysqli_prepare($conexao, $sql);
//mysqli_stmt_bind_param($stmt, "i", $empresa);
//mysqli_stmt_execute($stmt);

$result = mysqli_query($conexao,$sql);
$produtos = [];

while ($row = mysqli_fetch_assoc($result)) {
  $produtos[] = [
    'idProduto' => $row['idProduto'],
    'nomeProduto' => $row['nomeProduto']
  ];
}

echo json_encode($produtos);
