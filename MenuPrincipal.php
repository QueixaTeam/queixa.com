<?php
include_once './usuario.php';
include_once './empresa.php';
?>
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css"
        rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7"
        crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
        crossorigin="anonymous"></script>

    <!-- CSS -->
    <link rel="stylesheet" href="css/MenuPrincipal.css">
    <link rel="stylesheet" href="css/ListaMelhoresEmp.css">

    <!-- Google Charts -->
    <script src="https://www.gstatic.com/charts/loader.js"></script>
    <!-- JS externo -->
    <script src="js/graficoMenu.js"></script>

    <title>Queixa.com</title>
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
                                    <li><a class="dropdown-item" href="minhasQueixas.php">Minhas Queixas</a></li>
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
    <div class="container position-relative mt-5" >
        <div class="box-custom-item1 position-absolute top-0 start-0 m-4 p-4 text-center">
            <?php if (isset($_SESSION['user']->nomeEmpresa)): ?>
                <a href="perfilEmpresa.php" class="btn btn-custom-item1-empresa ">Veja as queixas da sua empresa</a>
            <?php else: ?>
                <a href="cadastroQueixa.php" class="btn btn-custom-item1  me-2">Faça uma queixa</a>
                <a href="minhasQueixas.php" class="btn btn-custom-item1 ">Minhas queixas</a>
            <?php endif; ?>
        </div>

        <div class="box-custom position-absolute top-0 end-0 m-4 p-4 text-center">
            <div class="dados" style="max-height: 170px; overflow-y: auto;">
                <h2 class="titulo-grafico">Empresas melhor avaliadas</h2>
                <?php include 'listarMelhoresEmpresas.php'; ?>
            </div>

        </div>

        <div class="box-custom-item3 position-absolute bottom-0 start-0 m-4 p-4 text-center">
            <?php if (isset($_SESSION['user']->nomeEmpresa)): ?>
                <a href="cadastroUsuario.php" class="btn btn-custom-item3  me-2 mb-2"> Cadastre-se como consumidor</a>
                <p>Registre-se como consumidor e ajude empresas a evoluírem com suas queixas!</p>
            <?php else: ?>
                <a href="cadastroEmpresa.php" class="btn btn-custom-item3  me-2 mb-2"> Cadastre aqui a sua empresa</a>
                <p>Cadastre sua empresa no QUEIXA.COM e destaque-se com as melhores avaliações para atrair mais clientes!</p>
            <?php endif; ?>
        </div>

        <div class="box-custom position-absolute bottom-0 end-0 m-4 p-4 text-center">
            <div id="grafico"></div>
        </div>
    </div>
</body>

</html>