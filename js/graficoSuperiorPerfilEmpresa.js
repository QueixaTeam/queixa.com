console.log('Carregando o gráfico do menu...');

// Função para pegar o parâmetro nomeEmpresa da URL
function getNomeEmpresaFromUrl() {
    const params = new URLSearchParams(window.location.search);
    return params.get('nomeEmpresa'); // retorna null se não existir
}

// Carrega a lib do Google Charts
google.charts.load('current', { packages: ['corechart'] });
google.charts.setOnLoadCallback(drawChart);

function drawChart() {
    const nomeEmpresa = getNomeEmpresaFromUrl();

    // Monta a URL do fetch, passando o nomeEmpresa se existir
    let url = 'dadosGraficoSupPerfilEmpresa.php';
    if (nomeEmpresa) {
        url += '?nomeEmpresa=' + encodeURIComponent(nomeEmpresa);
    }

    fetch(url)
        .then(response => response.json())
        .then(data => {
            const chartData = google.visualization.arrayToDataTable(data);

            const options = {
                title: 'Quantidade de queixas por categoria',
                legend: { position: 'right' },
                pieSliceText: 'none',
                pieHole: 0.4, // Aqui vira gráfico de rosca
                width: 400,
                height: 170
            };

            const chart = new google.visualization.PieChart(document.getElementById('graficoSup'));
            chart.draw(chartData, options);
        })
        .catch(error => {
            console.error('Erro ao carregar dados do gráfico:', error);
        });
}
