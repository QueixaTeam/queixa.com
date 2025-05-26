<?php
include_once './conexaoDatabase.php';
include_once './usuario.php';
include_once './empresa.php';
session_start(); 

// Lógica de obtenção do ID da empresa
if (isset($_GET['idEmpresa'])) {
    $idEmpresa = intval($_GET['idEmpresa']);
} elseif (isset($_GET['nomeEmpresa'])) {
    // Escapa para evitar SQL Injection
    $nomeEmpresa = $conexao->real_escape_string($_GET['nomeEmpresa']);

    // Busca o ID da empresa com base no nome
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

// Consulta: Quantidade de empresa por setor
$sql = "SELECT nomeCategAvaliacao, COUNT(avaliacao.idAvaliacao) AS qtd_avaliacao_categ 
        FROM avaliacao
        INNER JOIN categAvaliacao ON avaliacao.idCategAvaliacao = categAvaliacao.idCategAvaliacao
        WHERE avaliacao.idEmpresa = $idEmpresa
        GROUP BY nomeCategAvaliacao";

$result = $conexao->query($sql);

// Verifica se houve erro na consulta
if (!$result) {
    echo json_encode(['error' => 'Erro na consulta: ' . $conexao->error]);
    exit;
}

// Formata os dados para o Google Charts
$data = [];
$data[] = ['nomeCategAvaliacao', 'qtd_avaliacao_categ'];

while ($row = $result->fetch_assoc()) {
    $data[] = [$row['nomeCategAvaliacao'], (int)$row['qtd_avaliacao_categ']];
}

echo json_encode($data);
?>
