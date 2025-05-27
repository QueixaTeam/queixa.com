<?php
include_once './empresa.php';
include_once './dadosPerfilEmpresa.php';

?>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
        crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css"
        rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7"
        crossorigin="anonymous">
    <!-- CSS -->
    <link rel="stylesheet" href="css/perfilEmpresa.css">
    <link rel="stylesheet" href="css/navbar.css">
    <!-- Google Charts -->
    <script src="https://www.gstatic.com/charts/loader.js"></script>
    <!-- JS externo -->
    <script src="js/graficoSuperiorPerfilEmpresa.js"></script>
    <script src="js/graficoInferiorPerfilEmpresa.js"></script>
    <title>Queixa.com</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary mb-4">
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
                <div class="d-flex me-4">
                    <?php if (isset($_SESSION['user'])): ?>
                        <!-- Dropdown do perfil (usuário logado) -->
                        <div class="btn-group">
                            <!-- Botão com imagem -->
                            <button class="btn btn-secondary btn-lg d-flex align-items-center" type="button">
                                <img src="img/perfilUsuario.png" alt="Botão" style="width: 30px; height: 30px;" class="me-2">
                                <!-- Nome do usuário logado-->
                                <?php
                                if (isset($_SESSION['user'])) {
                                    if (property_exists($_SESSION['user'], 'nomeUsuario')) {
                                        echo htmlspecialchars($_SESSION['user']->nomeUsuario);
                                    } elseif (property_exists($_SESSION['user'], 'nomeEmpresa')) {
                                        echo htmlspecialchars($_SESSION['user']->nomeEmpresa);
                                    }
                                }
                                ?>
                            </button>

                            <!-- Ícone do dropdown -->
                            <button type="button" class="btn btn-lg btn-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="visually-hidden">Toggle Dropdown</span>
                            </button>

                            <!-- Menu dropdown -->
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="logout.php">Deslogar</a></li>
                                <?php if (isset($_SESSION['user']->nomeEmpresa)): ?>
                                    <li><a class="dropdown-item" href="perfilEmpresa.php">Perfil da empresa</a></li>
                                    <li><a class="dropdown-item" href="telaRelatorios.php">Relatórios</a></li>
                                <?php else: ?>
                                    <li><a class="dropdown-item" href="minhasQueixas.php">Minhas queixas</a></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    <?php else: ?>
                        <!-- Botões para redirecionar -->
                        <div class="d-flex">
                            <a href="loginEmpCon.php" class="btn btn-outline-custom">Entrar</a>
                            <a href="cadastroEmpCon.php" class="btn btn-outline-custom ms-2">Cadastrar</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid bg-light py-3 shadow">
        <?php
        // Define a classe do badge conforme a nota média da empresa
        if ($mediaEmpresa !== null && $mediaEmpresa < 6) {
            $classeBadgeEmpresa = "badge bg-danger fs-5"; // vermelho
        } else if ($mediaEmpresa !== null && $mediaEmpresa >= 6) {
            $classeBadgeEmpresa = "badge bg-success fs-5"; // verde
        } else {
            $classeBadgeEmpresa = "badge bg-secondary fs-5"; // cor padrão caso não tenha avaliação
        }

        // Define a classe do badge conforme a nota média dos produtos
        if ($mediaProdutos !== null && $mediaProdutos < 6) {
            $classeBadgeProdutos = "badge bg-danger fs-5"; // vermelho
        } else if ($mediaProdutos !== null && $mediaProdutos >= 6) {
            $classeBadgeProdutos = "badge bg-success fs-5"; // verde
        } else {
            $classeBadgeProdutos = "badge bg-secondary fs-5"; // cor padrão caso não tenha avaliação
        }
        ?>
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0"><?php echo $dadosEmpresa['nomeEmpresa']; ?></h2>

            <span class="<?php echo $classeBadgeEmpresa; ?>">Nota média da empresa: <?php echo $mediaEmpresa !== null ?
                number_format($mediaEmpresa, 1, ',', '.') : 'Sem avaliação'; ?>
            </span>

            <span class="<?php echo $classeBadgeProdutos; ?>">Nota média dos produtos da empresa: <?php echo $mediaProdutos !== null ?
                number_format($mediaProdutos, 1, ',', '.') : 'Sem avaliação'; ?>
            </span>

            <?php
            // Exibir botão se não estiver logado OU se for usuário comum (idUsuario)
            if (!isset($_SESSION['user']) || isset($_SESSION['user']->idUsuario)) :
            ?>
                <a href="cadastroQueixa.php" class="btn btn-custom">Fazer Queixa</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="container-fluid mt-4">
        <div class="row">
            <!-- Coluna esquerda: gráficos -->
            <div class="col-lg-4 mb-4">
                <div class="mb-3 p-3 border rounded shadow-sm">
                    <!-- Gráfico 1 -->

                    <div id="graficoSup"></div>
                </div>
                <div class="p-3 border rounded shadow-sm">
                    <!-- Gráfico 2 -->
                    
                    <div id="graficoInf"></div>
                </div>
            </div>

            <!-- Coluna direita: queixas -->
            <div class="col-lg-8 ">
                <div class="p-3 border rounded shadow-sm conteiner-lista-queixas">
                    <h4>Queixas dos Consumidores</h4>
                    <?php include 'listaQueixas.php'; ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>