<br><br>
<div class="container">
 
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);
      
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Curso', 'No conocido', 'Hombres', 'Mujeres'],
          ['2015-2016', <?php echo 500; ?>, 400, 200],
          ['2016-2017', 1170, 460, 250],
          ['2017-2018', 660, 1120, 300]
        ]);

        var options = {
          chart: {
            title: 'Distribución sexos en talleres',
            subtitle: 'Número de personas de cada sexo en talleres',
          },
          vAxis: {
              format:'decimal'
          }
        };

        var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
    </script>
  </head>
  <body>
    <div id="columnchart_material" style="width: 800px; height: 500px;"></div>
  
    
    
    

</div>


<script>
$(document).ready(function(){
    
    
     
})
</script>    
