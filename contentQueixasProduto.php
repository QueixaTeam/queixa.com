<?php session_start(); ?>
<page backtop="20mm" backbottom="20mm" footer="date;time;page" style="font-size: 12pt">
    <h1 style="text-align: center;">Relatório de Queixas por Produto da Sua Empresa</h1>
    <br>
    <table border="1" cellspacing="0" style="width: 100%; text-align: center;">
        <thead>
            <tr style="background-color: #f0f0f0;">
                <th>Produto</th>
                <th>Quantidade de Queixas</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include './conexao.php';

            // Verifica se a empresa está logada
            if (!isset($_SESSION['user']->idEmpresa)) {
                echo "<tr><td colspan='2'>Empresa não está logada.</td></tr>";
            } else {
                $idEmpresa = $_SESSION['user']->idEmpresa;

                $stmt = $conexao->prepare("
                    SELECT p.nomeProduto, COUNT(a.idAvaliacao) AS totalQueixas
                    FROM avaliacao a
                    JOIN produto p ON a.idProduto = p.idProduto
                    WHERE p.idEmpresa = ?
                    GROUP BY p.nomeProduto
                    ORDER BY totalQueixas DESC
                ");

                $stmt->bind_param("i", $idEmpresa);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows === 0) {
                    echo "<tr><td colspan='2'>Nenhuma queixa encontrada para seus produtos.</td></tr>";
                } else {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['nomeProduto']) . "</td>";
                        echo "<td>" . $row['totalQueixas'] . "</td>";
                        echo "</tr>";
                    }
                }

                $stmt->close();
            }

            $conn->close();
            ?>
        </tbody>
    </table>
</page>
