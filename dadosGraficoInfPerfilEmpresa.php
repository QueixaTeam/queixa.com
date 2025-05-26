<?php
include_once './conexaoDatabase.php';
include_once './usuario.php';
include_once './empresa.php';
session_start(); 

// Lógica de obtenção do ID da empresa
if (isset($_GET['idEmpresa'])) {
    $idEmpresa = intval($_GET['idEmpresa']);
} elseif (isset($_GET['nomeEmpresa'])) {
    $nomeEmpresa = $conexao->real_escape_string($_GET['nomeEmpresa']);

    $sqlBusca = "SELECT idEmpresa FROM empresa WHERE nomeEmpresa LIKE '%$nomeEmpresa%' LIMIT 1";
    $res = $conexao->query($sqlBusca);

    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $idEmpresa = $row['idEmpresa'];
    } else {
        echo json_encode([["Empresa não encontrada", 0]]);
        exit;
    }
} elseif (isset($_SESSION['user'])) {
    $idEmpresa = $_SESSION['user']->idEmpresa;
} else {
    echo json_encode([["Erro", 0]]);
    exit;
}

// Consulta: Top 5 produtos com mais queixas
$sql = "SELECT 
            p.nomeProduto, 
            COUNT(a.idAvaliacao) AS totalQueixas
        FROM produto p
        LEFT JOIN avaliacao a ON a.idProduto = p.idProduto
        WHERE p.idEmpresa = $idEmpresa
        GROUP BY p.nomeProduto
        ORDER BY totalQueixas DESC
        LIMIT 5";


$result = $conexao->query($sql);

if (!$result) {
    echo json_encode(['error' => 'Erro na consulta: ' . $conexao->error]);
    exit;
}

// Formata os dados para o Google Charts
$data = [];
$data[] = ['Produto', 'Total de Queixas'];

while ($row = $result->fetch_assoc()) {
    $data[] = [$row['nomeProduto'], (int)$row['totalQueixas']];
}

echo json_encode($data);
?>
