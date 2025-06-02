<?php
include_once 'usuario.php';
include_once 'empresa.php';
include 'conexaoDatabase.php';
session_start();

// Verifica se o usuário está logado como empresa
if (!isset($_SESSION['user']) || !property_exists($_SESSION['user'], 'idEmpresa')) {
    header("Location: loginEmpCon.php");
    exit();
}

// Processa o formulário se for POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['idEmpresa'];
    $nome = $_POST['nomeEmpresa'];
    $cnpj = $_POST['cnpj'];
    $endereco = $_POST['endereco'];
    $senha = $_POST['senhaEmpresa'];
    $setor = $_POST['setor']; // Adicionado o campo setor

    try {
        // Atualiza o banco de dados (removida a verificação de senha vazia pois o campo é required)
        $sql = "UPDATE empresa SET nomeEmpresa = ?, cnpj = ?, endereco = ?, senhaEmpresa = ?, idSetor = ? WHERE idEmpresa = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("ssssii", $nome, $cnpj, $endereco, $senha, $setor, $id);

        if ($stmt->execute()) {
            // Atualiza os dados na sessão
            $_SESSION['user']->nomeEmpresa = $nome;
            $_SESSION['user']->cnpj = $cnpj;
            $_SESSION['user']->endereco = $endereco;
            $_SESSION['user']->idSetor = $setor; // Atualiza o setor na sessão

            // Redireciona com feedback de sucesso
            header("Location: editarCadEmpresa.php?success=1");
            exit();
        } else {
            header("Location: editarCadEmpresa.php?error=1");
            exit();
        }
    } catch (Exception $e) {
        header("Location: editarCadEmpresa.php?error=1");
        exit();
    }
} else {
    // Se não for POST, redireciona
    header("Location: editarCadEmpresa.php");
    exit();
}
?>