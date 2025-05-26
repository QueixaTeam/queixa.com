<?php
require_once 'conexaoDatabase.php';

// Sanitiza e valida os dados de entrada
$cnpj = mysqli_real_escape_string($conexao, $_POST['cnpj']);
$senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
$nomeEmpresa = mysqli_real_escape_string($conexao, $_POST['empresa']);
$idSetor = (int)$_POST['setor'];
$endereco = mysqli_real_escape_string($conexao, $_POST['endereco']);

// Query de inserção
$sql = "INSERT INTO empresa (cnpj, senhaEmpresa, nomeEmpresa, idSetor, endereco) 
        VALUES ('$cnpj', '$senha', '$nomeEmpresa', $idSetor, '$endereco')";

if (mysqli_query($conexao, $sql)) {
    header('Location: loginEmpresa.php');
    exit();
} else {
    // Página de erro personalizada
    header('Location: erro_cadastro.php?erro=' . urlencode(mysqli_error($conexao)));
    exit();
}

// Não é necessário fechar a conexão explicitamente
// pois o PHP fecha automaticamente quando o script termina
?>