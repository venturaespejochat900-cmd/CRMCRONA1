
 var cambio = 'bar';
 var fecha = new Date()
 var actual = fecha.getFullYear();
 var menosUno = fecha.getFullYear() - 1;
 var menosDos = fecha.getFullYear() - 2;

 var arraySinComillas;

 function quitarComillas(array) {
     arraySinComillas = array.map(elemento => parseFloat(Math.round(elemento * 100) / 100))
     return arraySinComillas;
 }
 
 var mayorDegrafica = Math.max(datosGrafica);
 var distancia = mayorDegrafica / 10;
 //console.log('bolsa');
 //var tipo = tipoGrafica;
 var grafica = quitarComillas(datosGrafica);
 //console.log(grafica);
 var grafica2 = quitarComillas(datosGrafica2);
 var grafica3 = quitarComillas(datosGrafica3);

 var months = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dec']

 var cssColors = (color) => {
     return getComputedStyle(document.documentElement).getPropertyValue(color)
 }

 var getColor = () => {
     return window.localStorage.getItem('color') ?? 'cyan'
 }

 var colors = {
     primary: cssColors(`--color-${getColor()}`),
     primaryLight: cssColors(`--color-${getColor()}-light`),
     primaryLighter: cssColors(`--color-${getColor()}-lighter`),
     primaryDark: cssColors(`--color-${getColor()}-dark`),
     primaryDarker: cssColors(`--color-${getColor()}-darker`),
 }


    var barChart = new Chart(document.getElementById('barChart'+cod+''), {
        type: cambio,        
        data: {
            labels: months,
            datasets: [
                {
                    label:menosUno,
                    data: grafica2,
                    backgroundColor: 'rgb(34, 220, 19 )',  
                },
                {
                    label: menosDos,
                    data: grafica3,
                    backgroundColor: 'rgb(178, 13, 13)',
                },                
                {
                label: actual,
                data: grafica,
                backgroundColor: colors.primary,
                hoverBackgroundColor: colors.primaryDark,
            }, ],
        },
        options: {
            responsive : true,
            scales: {
                yAxes: [{
                    gridLines: true,
                    ticks: {
                        beginAtZero: true,
                        stepSize: 200000,
                        fontSize: 12,
                        fontColor: '#97a4af',
                        fontFamily: 'Open Sans, sans-serif',
                        padding: 10,
                    },
                }, ],
                xAxes: [{
                    gridLines: false,
                    ticks: {
                        fontSize: 12,
                        fontColor: '#97a4af',
                        fontFamily: 'Open Sans, sans-serif',
                        padding: 5,
                    },
                    categoryPercentage: 0.5,
                    maxBarThickness: '10',
                }, ],
            },
            cornerRadius: 2,
            maintainAspectRatio: false,
            legend: {
                display: true,
                title: {
                    text: '2021'
                },
            },
        },
    })
  
  