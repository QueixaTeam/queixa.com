<?php 
include_once 'empresa.php';
session_start();
include_once 'conexaoDatabase.php';

header('Content-type: application/json');

if (!isset($_SESSION['user']) || !property_exists($_SESSION['user'], 'idEmpresa')) {
    echo json_encode(['msg' => 'ID da empresa não encontrado na sessão.']);
    exit;
}

$nomeProduto = $_POST['nomeProduto'] ?? '';
$idEmpresa = $_SESSION['user']->idEmpresa;

$sql = "INSERT INTO produto (nomeProduto, idEmpresa) VALUES ('$nomeProduto', '$idEmpresa')";

if ($conexao->query($sql) === TRUE) {
    header('Location: cadastroProduto.php');
    $msg = "Produto registrado com sucesso!";
} else {
    $msg = "Erro: " . $sql . "<br>" . $conexao->error;
}

$conexao->close();

echo json_encode(['msg' => $msg]);
exit;
?>
