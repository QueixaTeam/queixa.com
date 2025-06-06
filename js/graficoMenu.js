console.log('Carregando o gráfico do menu...');

// Carrega a lib do Google Charts
google.charts.load('current', { packages: ['corechart'] });
google.charts.setOnLoadCallback(drawChart);

// Função que desenha o gráfico
function drawChart() {
    fetch('dadosGraficoMenu.php')
        .then(response => response.json())
        .then(data => {
            const chartData = google.visualization.arrayToDataTable(data);

            const options = {
                title: 'Quantidade de empresas cadastradas por setor',
                legend: { position: 'right' },
                pieSliceText: 'none',
                width: 400,
                height: 170
            };

            const chart = new google.visualization.PieChart(document.getElementById('grafico'));
            chart.draw(chartData, options);
        })
        .catch(error => {
            console.error('Erro ao carregar dados do gráfico:', error);
        });
}