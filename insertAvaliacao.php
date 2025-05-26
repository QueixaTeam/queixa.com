<?php

include 'conexaoDatabase.php';

header('Content-type: application/json');

$idProduto = $_POST['idProduto'] ?? null;
if ($idProduto === '') {
    $idProduto = "NULL";
} else {
    $idProduto = intval($idProduto);
}

$sql= "INSERT INTO avaliacao(descricao, nota, idUsuario, idEmpresa, idCategAvaliacao, idProduto) VALUES (
    '".$_POST['descricao']."',
    '".$_POST['nota']."',
    '".$_POST['idUsuario']."',
    '".$_POST['idEmpresa']."',
    '".$_POST['idCategAvaliacao']."',
    ". ($idProduto === "NULL" ? "NULL" : $idProduto) ."
)";

if ($conexao->query($sql) === TRUE) {
    header('Location: MenuPrincipal.php');
    $msg= "Queixa registrada com sucesso!!";
} else {
    $msg= "Error: " .$sql. "<br>". $conexao->error;
}

$conexao->close();

echo json_encode(['msg'=>$msg]);

exit;
?>
