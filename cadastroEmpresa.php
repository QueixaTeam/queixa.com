<?php
session_start();
include_once 'conexaoDatabase.php';

$query = "SELECT idSetor, nomeSetor FROM setor"; // Ajuste para sua tabela
$result = mysqli_query($conexao, $query);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css"
        rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7"
        crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
        crossorigin="anonymous"></script>
    <title>Queixa.com</title>
    <!-- No <head> ou antes do </body> -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="css/navBar.css">
    <link rel="stylesheet" href="css/cadastroEmpresa.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="MenuPrincipal.php">Queixa.com</a>
            <!-- Botão que aparece em telas pequenas para abrir/fechar o menu -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Itens que irão para o botão acima -->
            <div class="collapse navbar-collapse justify-content-between" id="navbarSupportedContent">
                <form class="d-flex mx-auto" role="search" method="get" action="perfilEmpresa.php">
                    <input class="form-control me-2" name="nomeEmpresa" type="search" placeholder="Buscar por empresas" aria-label="Search">
                    <button class="btn btn-outline-custom" type="submit">Buscar</button>
                </form>
                <!-- Botões para redirecionar -->
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted">Já tem cadastro?</span>
                    <a href="loginEmpCon.php" class="btn btn-outline-custom">Entrar</a>
                </div>
            </div>
        </div>
    </nav>


    <div class="container">
        <div class="card p-4 mx-auto" style="max-width: 600px;">
            <form action="insertCadEmpresa.php" method="post">
                <h1 class="text-center mb-1">Vamos cadastrar sua empresa!</h1>

                <img src="img/Queixinha.png" alt="Queixinha" style="height: 250px; width: 250px; display: block; margin: auto;">

                
                <div class="mb-3">
                    <label for="empresa" class="form-label">Digite o nome da empresa:</label>
                    <input type="text" class="form-control" id="empresa" name="empresa" required>
                </div>

                <div class="mb-3">
                    <label for="senha" class="form-label">Digite a senha:</label>
                    <input type="password" class="form-control" id="senha" name="senha" required>
                </div>

                <div class="mb-3">
                    <label for="cnpj" class="form-label">Digite seu CNPJ</label>
                    <input type="text" class="form-control" id="cnpj" name="cnpj" required>
                </div>

                <div class="mb-3">
                    <label for="endereco" class="form-label">Digite o endereço:</label>
                    <input type="text" class="form-control" id="endereco" name="endereco" required>
                </div>

                <div class="mb-3">
                    <label for="setor" class="form-label">Qual o setor da empresa?</label>
                    <select class="form-select" id="setor" name="setor" required>
                        <option value="">Selecione um setor...</option>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <option value="<?= $row['idSetor'] ?>">
                                <?= htmlspecialchars($row['nomeSetor']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">CADASTRAR</button>
            </form>
        </div>
    </div>

</body>

</html>