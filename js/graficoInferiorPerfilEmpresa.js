console.log('Carregando o gráfico do menu...');

// Pega o parâmetro nomeEmpresa da URL
function getNomeEmpresaFromUrl() {
    const params = new URLSearchParams(window.location.search);
    return params.get('nomeEmpresa');
}

// Carrega o Google Charts
google.charts.load('current', { packages: ['corechart'] });
google.charts.setOnLoadCallback(drawChart);

function drawChart() {
    const nomeEmpresa = getNomeEmpresaFromUrl();
    let url = 'dadosGraficoInfPerfilEmpresa.php';
    if (nomeEmpresa) {
        url += '?nomeEmpresa=' + encodeURIComponent(nomeEmpresa);
    }

    fetch(url)
        .then(response => response.json())
        .then(data => {
            const dataTable = google.visualization.arrayToDataTable(data);

            // Cores para cada barra (até 5)
            const cores = ['#3366cc', '#dc3912', '#ff9900', '#109618', '#990099'];

            // Usa DataView para adicionar uma coluna de estilo
            const view = new google.visualization.DataView(dataTable);
            view.setColumns([
                0, // Produto (label)
                1, // Total de Queixas (value)
                {
                    type: 'string',
                    role: 'style',
                    calc: function (dataTable, row) {
                        return cores[row] || '#888'; // Usa cor ou cinza padrão
                    }
                }
            ]);

            const chartContainer = document.getElementById('graficoInf');
            const width = chartContainer.offsetWidth;

            const options = {
                title: 'Top 5 produtos com mais queixas',
                legend: { position: 'none' },
                width: width,
                height: 400,
                hAxis: {
                    title: 'Produto',
                    textStyle: {
                        fontSize: 12
                    }
                },
                vAxis: {
                    title: 'Quantidade de Queixas',
                    minValue: 0
                },
                bar: { groupWidth: '60%' }
            };

            const chart = new google.visualization.ColumnChart(chartContainer);
            chart.draw(view, options);
        })
        .catch(error => {
            console.error('Erro ao carregar dados do gráfico:', error);
        });
}
