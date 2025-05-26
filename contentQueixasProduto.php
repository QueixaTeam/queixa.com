<?php 
include_once './empresa.php';
session_start(); 
?>
<page backtop="20mm" backbottom="20mm" footer="date;time;page" style="font-size: 12pt; font-family: Arial, sans-serif;">

    <h2 style="text-align: center; color: #0033cc; margin-bottom: 30px;">
        Relatório de Queixas por Produto
    </h2>

    <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f0f0f0; border-bottom: 2px solid #ccc;">
                <th style="text-align: left; width: 60%;">Produto</th>
                <th style="text-align: right; width: 40%;">Quantidade de Queixas</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include_once './conexaoDatabase.php';

            if (!isset($_SESSION['user']->idEmpresa)) {
                echo "<tr><td colspan='2' style='text-align: center;'>Empresa não está logada.</td></tr>";
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
                    echo "<tr><td colspan='2' style='text-align: center;'>Nenhuma queixa encontrada para seus produtos.</td></tr>";
                } else {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr style='border-top: 1px solid #ccc;'>";
                        echo "<td style='text-align: left;'>" . htmlspecialchars($row['nomeProduto']) . "</td>";
                        echo "<td style='text-align: right;'>" . $row['totalQueixas'] . "</td>";
                        echo "</tr>";
                    }
                }

                $stmt->close();
            }

            $conexao->close();
            ?>
        </tbody>
    </table>
</page>
