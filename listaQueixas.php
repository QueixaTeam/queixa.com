<?php
include_once './conexaoDatabase.php';
include_once './dadosPerfilEmpresa.php';

// Consulta para puxar as queixas da empresa atual (idEmpresaConsulta)
$queryQueixas = "SELECT 
        u.nomeUsuario, 
        a.nota, 
        a.descricao, 
        a.dataAvaliacao, 
        c.nomeCategAvaliacao AS categoria, 
        p.nomeProduto
    FROM avaliacao a
    JOIN usuario u ON a.idUsuario = u.idUsuario
    JOIN categAvaliacao c ON a.idCategAvaliacao = c.idCategAvaliacao
    LEFT JOIN produto p ON a.idProduto = p.idProduto
    WHERE a.idEmpresa = $idEmpresaConsulta
    ORDER BY a.dataAvaliacao DESC
    LIMIT 10";

$resultQueixas = $conexao->query($queryQueixas);

if ($resultQueixas && $resultQueixas->num_rows > 0) {
    while ($row = $resultQueixas->fetch_assoc()) {
        echo '<div class="queixa mb-3">';
        echo '<strong>' . htmlspecialchars($row['nomeUsuario']) . '</strong> ';
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
    echo '<p>Não há queixas para esta empresa.</p>';
}
?>
