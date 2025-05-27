<?php
include_once 'usuario.php';
session_start();

if(!isset($_SESSION['user'])){
  header('Location: loginEmpCon.php');
  exit();
}

include_once 'conexaoDatabase.php';
include_once 'empresa.php';
include_once 'produto.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css"
    rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7"
    crossorigin="anonymous">
  <link rel="stylesheet" href="css/cadastroQueixa.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
    crossorigin="anonymous"></script>
  <title>Queixa.com</title>
</head>

<body>
  <nav class="navbar navbar-expand-lg bg-body-tertiary">
          <div class="container-fluid">
              <a class="navbar-brand" href="MenuPrincipal.php">Queixa.com</a>
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
                                  <li><a class="dropdown-item" href="#">Minhas Reclamações</a></li>
                              </ul>
                          </div>
                  </div>
              </div>
          </div>
  </nav>

  <p class="text-center fs-4">Vamos nos Queixar!</p>

  <form method="POST" action="insertAvaliacao.php">

  <!--pega o idUsuario-->
  <input type="hidden" name="idUsuario"
    value="<?= htmlspecialchars($_SESSION['user']->idUsuario ?? '') ?>">

    <!--card fundo-->
    <div class="container mt-5">
      <div class="card shadow p-4 w-100 h-90">
      <div class="container mt-4">
        <div class="row">
          <!-- Coluna da esquerda -->
          <div class="col-md-6">
            <!-- Campo de empresa -->
          <div style="position: relative;">
            <label for="empresaInput" class="form-label">Qual a empresa referente?</label>
            <input type="text" class="form-control" id="empresaInput" autocomplete="off" placeholder="Digite o nome da empresa" require>
            <input type="hidden" name="idEmpresa" id="idEmpresa"> <!--campo escondido para armazenar idEmpresa-->
            <div id="buscaEmpresa" class="list-group position-absolute z-3 w-50" 
                style="max-height: 200px; overflow-y: auto;"></div>
          </div>

            <!-- Nota -->
            <p>Digite a nota para a empresa ou produto</p>
            <div class="input-group mb-3" style="max-width: 150px;">
              <input type="number" name="nota" class="form-control" min="0" max="10" step="1" placeholder="0" require/>
              <span class="input-group-text">/10</span>
            </div>

            <!-- Campo de categoria -->
            <div class="input-group mb-3">
              <p class="fs-5 text-start">Em qual categoria você enquadraria sua queixa?</p>
              <label class="input-group-text">Selecione aqui:</label>
              <select class="form-select" name="idCategAvaliacao" require>
                <option selected>Escolher...</option>
                <option value="1">Qualidade do produto</option>
                <option value="2">Atendimento ao cliente</option>
                <option value="3">Entrega</option>
                <option value="4">Preço</option>
                <option value="5">Suporte técnico</option>
              </select>
            </div>
          </div>

          <!-- Coluna da direita -->
          <div class="col-md-6">
            <!-- Produto ou não -->
            <p>Sua queixa é referente a um produto especifico?</p>
            <div class="form-check mb-2">
              <input class="form-check-input" type="radio" name="radioDefault" id="radioDefault1">
              <label class="form-check-label" for="radioDefault1">
                Sim, é referente a um produto
              </label>
            </div>
            <div class="form-check mb-4">
              <input class="form-check-input" type="radio" name="radioDefault" id="radioDefault2" checked>
              <label class="form-check-label" for="radioDefault2">
                Não, queixa direcionada à empresa como um todo
              </label>
            </div>
            <!--caixa de texto "escondida"-->
            <div class="mb-3" id="campoProduto" style="display: none;">
              <label for="nomeProduto" class="form-label">Digite qual produto é referente à queixa: </label>
              <input type="text" class="form-control" id="nomeProduto" placeholder="Digite o nome do produto" autocomplete="off">
              <input type="hidden" name="idProduto" id="idProduto"> <!--campo escondido para armazenar idProduto-->
              <ul id="sugestoes" class="list-group" style="position: absolute; z-index: 1000;"></ul>
            </div>

            <!-- Descrição da queixa -->
            <div class="mb-3">
              <label for="exampleFormControlTextarea1" class="form-label">Descreva sua queixa aqui: </label>
              <textarea class="form-control" id="exampleFormControlTextarea1" name="descricao" placeholder="Descreva aqui" rows="6" style="resize: none;"></textarea>
            </div>
          </div>
          <!--botão envio-->
          <div class="d-flex justify-content-center mt-4">
            <button type="submit" href="MenuPrincipal.php" class="btn btn-primary" >Enviar Queixa</button>
          </div>
  </form>                          
      </div>
    </div>
    </div>
  </div>

<script>
  //script funcionamento checkbox
 
  document.addEventListener("DOMContentLoaded", function () {
    const simProduto = document.getElementById("radioDefault1");
    const naoProduto = document.getElementById("radioDefault2");
    const campoProduto = document.getElementById("campoProduto");

    function atualizarCampoProduto() {
      if (simProduto.checked) {
        campoProduto.style.display = "block";
      } else {
        campoProduto.style.display = "none";

        document.getElementById('idProduto').value = '';
        document.getElementById('nomeProduto').value = '';
      }
    }

    //Chamar ao carregar
    atualizarCampoProduto();

    //Atualiza quando usuário clicar
    simProduto.addEventListener("change", atualizarCampoProduto);
    naoProduto.addEventListener("change", atualizarCampoProduto);
  });
</script>


<script>
let empresaSelecionada = ''; // empresa clicada

document.addEventListener('DOMContentLoaded', () => {
  const empresaInput  = document.getElementById('empresaInput');
  const listaEmpresas = document.getElementById('buscaEmpresa');
  const prodInput     = document.getElementById('nomeProduto');
  const listaProd     = document.getElementById('sugestoes');

  /* ---------- AUTOCOMPLETE DE EMPRESAS ---------- */
  empresaInput.addEventListener('input', () => {
    const termo = empresaInput.value.trim();
    if (termo.length < 2) { listaEmpresas.innerHTML = ''; return; }

    fetch(`buscaEmpresas.php?termo=${encodeURIComponent(termo)}`)
      .then(r => r.json())
      .then(empresas => {
        listaEmpresas.innerHTML = '';
        if (!empresas.length) {
          listaEmpresas.innerHTML = '<div class="list-group-item">Nenhuma empresa encontrada</div>';
          return;
        }

       empresas.forEach(empresa => {
        const item = document.createElement('div');
        item.className = 'list-group-item list-group-item-action';
        item.textContent = empresa.nome;
        item.dataset.id = empresa.id;

        item.addEventListener('click', () => {
          empresaInput.value = empresa.nome;
          empresaSelecionada = empresa.id;
          document.getElementById('idEmpresa').value = empresa.id; //armazena o idEmpresa
          listaEmpresas.innerHTML = '';
        });

        listaEmpresas.appendChild(item);
      });

      })
      .catch(() => {
        listaEmpresas.innerHTML = '<div class="list-group-item text-danger">Erro ao buscar empresas</div>';
      });
  });

  // Fecha sugestões de empresa se clicar fora
  document.addEventListener('click', e => {
    if (!e.target.closest('#empresaInput') && !e.target.closest('#buscaEmpresa'))
      listaEmpresas.innerHTML = '';
  });

  /* ---------- AUTOCOMPLETE DE PRODUTOS ---------- */
  prodInput.addEventListener('input', () => {
    const termo = prodInput.value.trim();
    if (termo.length < 2 || !empresaSelecionada) {
      listaProd.innerHTML = '';
      return;
    }
    
    fetch(`buscaProdutos.php?termo=${encodeURIComponent(termo)}&empresaSelecionada=${encodeURIComponent(empresaSelecionada)}`)
      .then(r => r.json())
      .then(produtos => {
        listaProd.innerHTML = '';
        if (!produtos.length) {
          listaProd.innerHTML = '<li class="list-group-item">Nenhum produto encontrado</li>';
          return;
        }

        produtos.forEach(produto => {
        const item = document.createElement('li');
        item.className = 'list-group-item list-group-item-action';
        item.textContent = produto.nomeProduto;
        item.dataset.id = produto.idProduto;

        item.addEventListener('click', () => {
          prodInput.value = produto.nomeProduto;
          document.getElementById('idProduto').value = produto.idProduto; // armazena o IdProduto
          listaProd.innerHTML = '';
        });

        listaProd.appendChild(item);
      });

      })
      .catch(() => {
        listaProd.innerHTML = '<li class="list-group-item text-danger">Erro ao buscar produtos</li>';
      });
  });
}); 
</script>

</body>

</html>