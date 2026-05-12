 // All javascript code in this project for now is just for demo DON'T RELY ON IT
 var cambio = 'bar';
 var fecha = new Date()
 var actual = fecha.getFullYear();
 var menosUno = fecha.getFullYear() - 1;
 var menosDos = fecha.getFullYear() - 2;

 let arraySinComillas;


 function lineOrBar(valor){
  
  if(valor == 'false'){
    $('#cambioGrafica').val(true);
    cambio = 'line';
    console.log($('#cambioGrafica').val());
    
  }
  if(valor == 'true'){
    $('#cambioGrafica').val(false);
    cambio = 'bar';
    console.log($('#cambioGrafica').val());
    
  }
  barChart.type.writeln(cambio);
  barChart.update();
}

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

 const months = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dec']

 const cssColors = (color) => {
     return getComputedStyle(document.documentElement).getPropertyValue(color)
 }

 const getColor = () => {
     return window.localStorage.getItem('color') ?? 'cyan'
 }

 const colors = {
     primary: cssColors(`--color-${getColor()}`),
     primaryLight: cssColors(`--color-${getColor()}-light`),
     primaryLighter: cssColors(`--color-${getColor()}-lighter`),
     primaryDark: cssColors(`--color-${getColor()}-dark`),
     primaryDarker: cssColors(`--color-${getColor()}-darker`),
 }


    const barChart = new Chart(document.getElementById('barChart'), {
        type: cambio,        
        data: {
            labels: months,
            datasets: [{
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
  
  