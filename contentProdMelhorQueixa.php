<?php 
include_once './empresa.php';
session_start(); 
?>
<page backtop="20mm" backbottom="20mm" footer="date;time;page" style="font-size: 12pt; font-family: Arial, sans-serif;">

    <h2 style="text-align: center; color: #0033cc; margin-bottom: 30px;">
        Relatório de Produtos Bem Avaliados 
    </h2>

    <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f0f0f0; border-bottom: 2px solid #ccc;">
                <th style="text-align: left; width: 50%;">Produto</th>
                <th style="text-align: center; width: 25%;">Nota Média</th>
                <th style="text-align: right; width: 25%;">Queixas Positivas</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include_once './conexaoDatabase.php';

            if (!isset($_SESSION['user']->idEmpresa)) {
                echo "<tr><td colspan='3' style='text-align: center;'>Empresa não está logada.</td></tr>";
            } else {
                $idEmpresa = $_SESSION['user']->idEmpresa;

                $stmt = $conexao->prepare("
                    SELECT 
                        p.nomeProduto,
                        ROUND(AVG(a.nota), 2) AS mediaNota,
                        SUM(CASE WHEN a.nota >= 6 THEN 1 ELSE 0 END) AS queixasPositivas
                    FROM avaliacao a
                    JOIN produto p ON a.idProduto = p.idProduto
                    WHERE p.idEmpresa = ? AND a.nota IS NOT NULL
                    GROUP BY p.nomeProduto
                    HAVING mediaNota >= 6
                    ORDER BY mediaNota DESC
                ");

                $stmt->bind_param("i", $idEmpresa);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows === 0) {
                    echo "<tr><td colspan='3' style='text-align: center;'>Nenhum produto com nota média ≥ 6.</td></tr>";
                } else {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr style='border-top: 1px solid #ccc;'>";
                        echo "<td style='text-align: left;'>" . htmlspecialchars($row['nomeProduto']) . "</td>";
                        echo "<td style='text-align: center;'>" . number_format($row['mediaNota'], 2, ',', '.') . "</td>";
                        echo "<td style='text-align: right;'>" . $row['queixasPositivas'] . "</td>";
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
