<?php
include_once 'usuario.php';
include_once 'empresa.php';
include 'conexaoDatabase.php';
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user']) || !property_exists($_SESSION['user'], 'idUsuario')) {
    header("Location: loginEmpCon.php");
    exit();
}

// Processa o formulário se for POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['idUsuario'];
    $nome = $_POST['nomeUsuario'];
    $email = $_POST['email'];
    $senha = $_POST['senhaUsuario'];

    try {
        // Atualiza o banco de dados
        if ($senha) {
            $sql = "UPDATE usuario SET nomeUsuario = ?, email = ?, senhaUsuario = ? WHERE idUsuario = ?";
            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("sssi", $nome, $email, $senha, $id);
        } else {
            $sql = "UPDATE usuario SET nomeUsuario = ?, email = ? WHERE idUsuario = ?";
            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("ssi", $nome, $email, $id);
        }

        if ($stmt->execute()) {
            // Atualiza os dados na sessão (opcional)
            $_SESSION['user']->nomeUsuario = $nome;
            $_SESSION['user']->email = $email;

            // Redireciona com feedback de sucesso
            header("Location: editarCadUsuario.php?success=1");
            exit();
        } else {
            header("Location: editarCadUsuario.php?error=1");
            exit();
        }
    } catch (Exception $e) {
        header("Location: editarCadUsuario.php?error=1");
        exit();
    }
} else {
    // Se não for POST, redireciona
    header("Location: editarCadUsuario.php");
    exit();
}
?>