<?php
include_once './usuario.php';
session_start();
include_once './conexaoDatabase.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['user']) || !isset($_SESSION['user']->idUsuario)) {
    echo json_encode(['erro' => 'Usuário não logado']);
    exit;
}

$idUsuarioLogado = $_SESSION['user']->idUsuario;

// Consulta: Quantidade de queixas por empresa feitas pelo usuário logado
$sql = "SELECT e.nomeEmpresa, COUNT(*) AS totalQueixas
        FROM avaliacao a
        INNER JOIN empresa e ON a.idEmpresa = e.idEmpresa
        WHERE a.idUsuario = $idUsuarioLogado
        GROUP BY a.idEmpresa";

$result = $conexao->query($sql);

// Formata os dados para o Google Charts
$data = [];
$data[] = ['Empresa', 'Total de Queixas'];

// Adiciona os dados ao array
while ($row = $result->fetch_assoc()) {
    $data[] = [$row['nomeEmpresa'], (int)$row['totalQueixas']];
}

// Retorna em JSON
echo json_encode($data);
?>
