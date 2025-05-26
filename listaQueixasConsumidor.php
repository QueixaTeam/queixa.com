<?php

include_once './conexaoDatabase.php';
include_once './usuario.php';

if (!isset($_SESSION['user']) || !isset($_SESSION['user']->idUsuario)) {
    echo '<p>Você precisa estar logado para ver suas queixas.</p>';
    exit;
}

$idUsuarioLogado = $_SESSION['user']->idUsuario;

// Consulta adaptada ao modelo do banco fornecido
$queryQueixas = "SELECT 
        e.nomeEmpresa, 
        a.nota, 
        a.descricao, 
        a.dataAvaliacao, 
        c.nomeCategAvaliacao AS categoria, 
        p.nomeProduto
    FROM avaliacao a
    JOIN empresa e ON a.idEmpresa = e.idEmpresa
    JOIN categAvaliacao c ON a.idCategAvaliacao = c.idCategAvaliacao
    LEFT JOIN produto p ON a.idProduto = p.idProduto
    WHERE a.idUsuario = $idUsuarioLogado
    ORDER BY a.dataAvaliacao DESC
    LIMIT 10";

$resultQueixas = $conexao->query($queryQueixas);

if ($resultQueixas && $resultQueixas->num_rows > 0) {
    while ($row = $resultQueixas->fetch_assoc()) {
        echo '<div class="queixa mb-3">';
        echo '<strong>' . htmlspecialchars($row['nomeEmpresa']) . '</strong> ';
        echo '(' . number_format($row['nota'], 1) . '/10): ';
        echo htmlspecialchars($row['descricao']) . '<br>';

        // Categoria e Produto (se existir)
        echo '<small class="text-muted">';
        echo 'Categoria: ' . htmlspecialchars($row['categoria']);
        if (!empty($row['nomeProduto'])) {
            echo ' - Produto: ' . htmlspecialchars($row['nomeProduto']);
        }
        echo '</small><br>';

        // Data
        echo '<small class="text-muted">' . date('d/m/Y', strtotime($row['dataAvaliacao'])) . '</small>';
        echo '</div>';
    }
} else {
    echo '<p>Você ainda não fez nenhuma queixa.</p>';
}
?>
