<style>

</style>
<div class="container">
    <br>
    
    <h3>Distribució usuaris/usuàries per sexe <?php echo $hoy; ?></h3>
    <!--Div that will hold the pie chart-->
    
    
    <br>
    <?php $total=$sexos['otros']+$sexos['hombres']+$sexos['mujeres'];
    $porcientoOtros=0;
    $porcientoHombres=0;
    $porcientoMujeres=0;
    if($total!=0){
    $porcientoOtros= number_format($sexos['otros']*100/$total,1);
    $porcientoHombres=number_format($sexos['hombres']*100/$total,1);
    $porcientoMujeres=number_format($sexos['mujeres']*100/$total,1);
    }
    
    ?>




    <table class="table table-bordered" style="width:50%">
      <tr>
        <th class="col-md-7" style="text-align: left;">Sexe Desconegut:</th>
        <th class="col-md-2" style="text-align: right;"><?php echo $sexos['otros']; ?></th>
        <th class="col-md-3" style="text-align: right;"> (<?php echo $porcientoOtros; ?> %)</th>       
    </tr>
      <tr>
        <th class="col-md-7" style="text-align: left;">Sexe Homes:</th>
        <th class="col-md-3" style="text-align: right;"><?php echo $sexos['hombres']; ?></th>
        <th class="col-md-3" style="text-align: right;"> (<?php echo $porcientoHombres; ?> %)</th>       
    </tr>
      <tr>
        <th class="col-md-7" style="text-align: left;">Sexe Dones:</th>
        <th class="col-md-2" style="text-align: right;"><?php echo $sexos['mujeres']; ?></th>
        <th class="col-md-3" style="text-align: right;"> (<?php echo $porcientoMujeres; ?> %)</th>       
    </tr>
    </tr>
      <tr>
        <th class="col-md-7" >Total:</th>
        <th class="col-md-2" style="text-align: right;"><?php echo $total; ?></th>
        <th class="col-md-3" > </th>       
    </tr>
      <tr>
      <th class="col-md-7" >Gràfic:</th>
        <th class="col-md-5" colspan="2"><span id="chart_div"></span></th>
        </tr>       
    
  </table>
    <!-- <h4>Sexe Desconegut: <?php echo $sexos['otros']; ?> (<?php echo $porcientoOtros; ?> %)</h4>
     <h4>Sexe Homes: <?php echo $sexos['hombres']; ?> (<?php echo $porcientoHombres; ?> %)</h4>
      <h4>Sexe Dones: <?php echo $sexos['mujeres']; ?> (<?php echo $porcientoMujeres; ?> %)</h4>
      <h3>Total: <?php echo $total; ?></h3>

      <div id="chart_div_"></div>
    -->
      <!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    
    <script type="text/javascript">

      // Load the Visualization API and the corechart package.
      google.charts.load('current', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.charts.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {
          var otros=<?php echo $sexos['otros']; ?>;
          var hombres=<?php echo $sexos['hombres']; ?>;
          var mujeres=<?php echo $sexos['mujeres']; ?>;
        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Topping');
        data.addColumn('number', 'Slices');
        data.addRows([
          ['Desconeguts', otros],
          ['Homes', hombres],
          ['Dones', mujeres]
        ]);

        // Set chart options
        var options = {     
                        'height':150,     
                       'width':300              
                   };

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
    
</div>


<script>
$(document).ready(function(){
    
    
     
})
</script>    
