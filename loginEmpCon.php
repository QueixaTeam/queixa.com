<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css"
    rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7"
    crossorigin="anonymous">
  <link rel="stylesheet" href="css/loginEmpCon.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
    crossorigin="anonymous"></script>
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
        <form class="d-flex mx-auto" role="search">
          <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
          <button class="btn btn-outline-custom" type="submit">Search</button>
        </form>
        <!-- Botões para redirecionar -->
        <div class="d-flex">
          <a href="loginEmpCon.php" class="btn btn-outline-custom">Entrar</a>
          <a href="cadastroEmpCon.php" class="btn btn-outline-custom ms-2">Cadastrar</a>
        </div>
      </div>
    </div>
  </nav>
  <!-- Cards -->
  <div class="container d-flex justify-content-around mt-5">
    <!-- Login como empresa -->
    <div class="card card-custom text-center">
      <img src="img/imageEmp.png" class="card-img-top img-custom mx-auto" alt="Empresa">
      <div class="card-body">
        <p class="card-text">Acesse as queixas recentes e a avaliação de sua empresa</p>
        <a href="#" class="btn btn-custom-card">Fazer login como empresa</a>
      </div>
    </div>
    <!-- Login como consumidor -->
    <div class="card card-custom text-center">
      <img src="img/imageCon.png" class="card-img-top img-custom mx-auto" alt="Consumidor">
      <div class="card-body">
        <p class="card-text">Acompanhe suas queixas e as respostas das empresa</p>
        <a href="loginUsuario.php" class="btn btn-custom-card">Fazer login como consumidor</a>
      </div>
    </div>
  </div>
</body>

</html>