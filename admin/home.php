<?php include "db.php";?>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<h1>Welcome to <?php echo $_settings->info('name') ?></h1>
<head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Month','Total Number of People', 'Total Number of People Being Monitored', 'Total Establishments Entered'],
          <?php
            $query="select * from barchart";
            $res=mysqli_query($conn,$query);
            while($data=mysqli_fetch_array($res)){
              $month=$data['month'];
              $total=$data['total'];
              $being_monitored=$data['being_monitored'];
              $tot_est=$data['tot_est'];
           ?>
           ['<?php echo $month;?>', <?php echo $total;?>, <?php echo $being_monitored;?>, <?php echo $tot_est;?>],   
           <?php   
            }
           ?> 
        ]);

        var options = {
          chart: {
            title: 'Analytics for Individuals',
            subtitle: 'Contact Tracing',
          },
          colors: ['silver','gold','brown'],
          bars: 'vertical' // Required for Material Bar Charts.
        };

        var chart = new google.charts.Bar(document.getElementById('barchart_material'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
  
        var data1 = google.visualization.arrayToDataTable([
          ['Establishment','Total Number of People Who Entered', 'Frequency of People Entering the Establishment'],
          <?php
            $query="select * from barchart2";
            $res=mysqli_query($conn,$query);
            while($data1=mysqli_fetch_array($res)){
              $establishment=$data1['establishment'];
              $total=$data1['total'];
              $freq=$data1['freq'];
           ?>
           ['<?php echo $establishment;?>', <?php echo $total;?>, <?php echo $freq;?>],   
           <?php   
            }
           ?> 
        ]);

        var barchart2_options = {
          chart: {
            title: 'Analytics for Establishments',
            subtitle: 'Contact Tracing',
          },
          colors: ['violet','orange', 'pink'],
          bars: 'vertical' // Required for Material Bar Charts.
        };

        var barchart2_chart = new google.charts.Bar(document.getElementById('barchart_material1'));

        barchart2_chart.draw(data1, google.charts.Bar.convertOptions(barchart2_options));

        var data2 = google.visualization.arrayToDataTable([
          ['Barangay', 'Total Number Who Registered in the Barangay', 'Total Number Who Are Currently Being Contact Traced'],
          <?php
            $query="select * from barchart3";
            $res=mysqli_query($conn,$query);
            while($data2=mysqli_fetch_array($res)){
              $brgy=$data2['brgy'];
              $total_added=$data2['total_added'];
              $total_registered=$data2['total_registered'];
           ?>
           ['<?php echo $brgy;?>', <?php echo $total_added;?>, <?php echo $total_registered;?>],   
           <?php   
            }
           ?> 
        ]);

        var barchart3_options = {
          chart: {
            title: 'Analytics for Barangays',
            subtitle: 'Contact Tracing',
          },
          colors: ['indigo','blue'],
          bars: 'vertical', // Required for Material Bar Charts.
        };

        var barchart3_chart = new google.charts.Bar(document.getElementById('barchart_material2'));

        barchart3_chart.draw(data2, google.charts.Bar.convertOptions(barchart3_options));
 

      }
    </script>
  </head>
  <body>
    <div id="barchart_material" style="width: 1300px; height: 500px;"></div>
    <div id="barchart_material1" style="width: 1300px; height: 500px;"></div>
    <div id="barchart_material2" style="width: 1300px; height: 500px;"></div>
  </body>
</html>

