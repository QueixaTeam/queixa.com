<?php
include_once './usuario.php';
include_once 'conexaoDatabase.php';
include_once 'empresa.php';
session_start();


if (isset($_GET['nomeEmpresa']) && trim($_GET['nomeEmpresa']) !== '') {
    $nomeEmpresaPesquisada = mysqli_real_escape_string($conexao, $_GET['nomeEmpresa']);

    $sql = "SELECT * FROM empresa WHERE nomeEmpresa LIKE '%$nomeEmpresaPesquisada%'";
    $resultado = mysqli_query($conexao, $sql);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $dadosEmpresa = mysqli_fetch_assoc($resultado);
    } else {
        echo "Empresa pesquisada não encontrada.";
        exit;
    }

    // Empresa pesquisada
} else if (!isset($_GET['idEmpresa']) && isset($_SESSION['user'])) {
    $idEmpresa = $_SESSION['user']->idEmpresa;

    $sql = "SELECT * FROM empresa WHERE idEmpresa = $idEmpresa";
    $resultado = mysqli_query($conexao, $sql);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $dadosEmpresa = mysqli_fetch_assoc($resultado);
    } else {
        echo "<script>
            alert('Empresa não encontrada.');
            window.location.replace('MenuPrincipal.php'); // volta para o menu
            </script>";
        exit;
    }

} else {
    echo "<script>
        alert('Nenhuma empresa especificada.');
        window.location.replace('MenuPrincipal.php'); // volta para o menu
        </script>";
    exit;
}

// Consulta da nota média usando a função medEmpresa
$idEmpresaConsulta = $dadosEmpresa['idEmpresa'];
// Média geral da empresa (ex.: avaliações gerais da empresa)
$sqlMediaEmpresa = "SELECT medEmpresa($idEmpresaConsulta) AS notaMediaEmpresa";
$resultMediaEmpresa = mysqli_query($conexao, $sqlMediaEmpresa);

if ($resultMediaEmpresa && mysqli_num_rows($resultMediaEmpresa) > 0) {
    $mediaEmpresa = mysqli_fetch_assoc($resultMediaEmpresa)['notaMediaEmpresa'];
} else {
    $mediaEmpresa = 0;
}

// Média das avaliações dos produtos da empresa
$sqlMediaProdutos = "SELECT medProdutosEmpresa($idEmpresaConsulta) AS notaMediaProdutos";
$resultMediaProdutos = mysqli_query($conexao, $sqlMediaProdutos);

if ($resultMediaProdutos && mysqli_num_rows($resultMediaProdutos) > 0) {
    $mediaProdutos = mysqli_fetch_assoc($resultMediaProdutos)['notaMediaProdutos'];
} else {
    $mediaProdutos = 0;
}

?>