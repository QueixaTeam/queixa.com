<?php
include_once 'usuario.php';
include_once 'empresa.php';
include 'conexaoDatabase.php';
session_start();

// Verificação de sessão
if (!isset($_SESSION['user']) || !is_object($_SESSION['user'])) {
    header("Location: loginEmpCon.php");
    exit();
}

// Verifica se é empresa (não usuário)
if (!property_exists($_SESSION['user'], 'idEmpresa')) {
    die("Acesso restrito a empresas");
}

$id = $_SESSION['user']->idEmpresa;

// Prepara a consulta com prevenção a SQL injection
$sql = "SELECT * FROM empresa WHERE idEmpresa = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Empresa não encontrada no banco de dados.");
}

$row = $result->fetch_assoc();

// Buscar setores para o dropdown
$query = "SELECT idSetor, nomeSetor FROM setor";
$resultSetores = mysqli_query($conexao, $query);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css"
        rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7"
        crossorigin="anonymous">
    <link rel="stylesheet" href="css/cadastro.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
        crossorigin="anonymous"></script>
    <title>Queixa.com - Editar Empresa</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="css/cadastroEmpresa.css">
    <link rel="stylesheet" href="css/navBar.css">
</head>

<body>
    <!-- Navbar idêntico ao do MenuPrincipal.php -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="MenuPrincipal.php">Queixa.com</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between" id="navbarSupportedContent">
                <form class="d-flex mx-auto" role="search" method="get" action="perfilEmpresa.php">
                    <input class="form-control me-2" name="nomeEmpresa" type="search" placeholder="Buscar por empresas" aria-label="Search">
                    <button class="btn btn-outline-custom" type="submit">Buscar</button>
                </form>
                <div class="d-flex me-4">
                    <div class="btn-group">
                        <button class="btn btn-secondary btn-lg d-flex align-items-center" type="button">
                            <img src="img/perfilUsuario.png" alt="Botão" style="width: 30px; height: 30px;" class="me-2">
                            <?php
                            if (property_exists($_SESSION['user'], 'nomeUsuario')) {
                                echo htmlspecialchars($_SESSION['user']->nomeUsuario);
                            } elseif (property_exists($_SESSION['user'], 'nomeEmpresa')) {
                                echo htmlspecialchars($_SESSION['user']->nomeEmpresa);
                            }
                            ?>
                        </button>
                        <button type="button" class="btn btn-lg btn-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="visually-hidden">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="logout.php">Deslogar</a></li>
                            <?php if (isset($_SESSION['user']->nomeEmpresa)): ?>
                                <li><a class="dropdown-item" href="perfilEmpresa.php">Perfil da empresa</a></li>
                                <li><a class="dropdown-item" href="editarCadEmpresa.php">Editar dados</a></li>
                                <li><a class="dropdown-item" href="#">Relatórios</a></li>
                            <?php else: ?>
                                <li><a class="dropdown-item" href="minhasQueixas.php">Minhas Queixas</a></li>
                                <li><a class="dropdown-item" href="editarCadUsuario.php">Editar dados</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="card p-4 mx-auto" style="max-width: 600px;">
            <form action="atualizarEmpresa.php" method="post">
                <h1 class="text-center mb-4">Editar dados da empresa</h1>
                <input type="hidden" name="idEmpresa" value="<?php echo $id; ?>">

                <div class="mb-3">
                    <label for="nomeEmpresa" class="form-label">Nome da Empresa:</label>
                    <input type="text" class="form-control" id="nomeEmpresa" name="nomeEmpresa"
                        value="<?php echo htmlspecialchars($row['nomeEmpresa']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="cnpj" class="form-label">CNPJ:</label>
                    <input type="text" class="form-control" id="cnpj" name="cnpj"
                        value="<?php echo htmlspecialchars($row['cnpj']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="endereco" class="form-label">Endereço:</label>
                    <input type="text" class="form-control" id="endereco" name="endereco"
                        value="<?php echo htmlspecialchars($row['endereco']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="setor" class="form-label">Setor da Empresa:</label>
                    <select class="form-select" id="setor" name="setor" required>
                        <?php
                        // Reset o ponteiro do resultado para poder usar novamente
                        mysqli_data_seek($resultSetores, 0);
                        while ($setor = mysqli_fetch_assoc($resultSetores)): ?>
                            <option value="<?= $setor['idSetor'] ?>"
                                <?= ($setor['idSetor'] == $row['idSetor']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($setor['nomeSetor']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="senhaEmpresa" class="form-label">Senha:</label>
                    <input type="text" class="form-control" id="senhaEmpresa" name="senhaEmpresa"
                        value="<?php echo htmlspecialchars($row['senhaEmpresa']); ?>" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">ATUALIZAR</button>
            </form>
            <div id="feedback" class="mt-3"></div>
        </div>
    </div>

    <!-- Script para feedback após atualização -->
    <script>
        $(document).ready(function() {
            // Verifica se há uma mensagem na URL (ex: ?success=1)
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('success')) {
                $('#feedback').html('<div class="alert alert-success">Dados atualizados com sucesso!</div>');
            } else if (urlParams.has('error')) {
                $('#feedback').html('<div class="alert alert-danger">Erro ao atualizar dados.</div>');
            }
        });
    </script>
</body>

</html>