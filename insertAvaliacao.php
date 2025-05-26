<?php
include 'conexaoDatabase.php';

header('Content-type: application/json');

$sql= "insert into avaliacao(descricao, nota, idUsuario, idEmpresa, idCategAvaliacao, idProduto) values
('".$_POST['descricao']."', '".$_POST['nota']."', '".$_POST['idUsuario']."','".$_POST['idEmpresa']."',
'".$_POST['idCategAvaliacao']."','".$_POST['idProduto']."')";

if ($conexao->query($sql)==TRUE){
    $msg= "Queixa registrada com sucesso!!";
} else{
    $msg= "Error: " .$sql. "<br>". $conexao->error;
}

$conexao-> close();

echo json_encode(['msg'=>$msg]);
?>