<html>
  <head>
    <style>
      .overlay-low {
        width: 160px;
        height: 50px;
        position: absolute;
        top: 540px;
        left: 385px;
        font-family: 'Arial';
        font-size: 14px;
        color: #0000e6;
      }
      .overlay-high {
        width: 160px;
        height: 50px;
        position: absolute;
        top: 255px;
        left: 960px;
        font-family: 'Arial';
        font-size: 14px;
        color: #e50000;
      }
    </style>
    <!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">

      // Load the Visualization API and the corechart package.
      google.charts.load('current', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.charts.setOnLoadCallback(drawChart);

      // Callback that pulls data from Google Sheet,
      // instantiates the chart, passes in the data and
      // draws it.
      /*
      function drawChart() {
        var queryString = encodeURIComponent('SELECT A, B, C, D, E, F');

        var url = "<Put the sharing link of your Google Sheet here, with editing enabled>";

        var query = new google.visualization.Query(url + '#gid=0&headers=1&tq=' + queryString);
        query.send(handleQueryResponse);
      }*/
      function drawChart() {
            var query = new google.visualization.Query('https://docs.google.com/spreadsheets/d/1cK0UPWvmf23iK3qu3QXrpgcsammpZseROuCN5oHFdO0/edit#gid=0&headers=1');
            // query.setQuery('SELECT A, B, C, D, E, F');
            query.setQuery('SELECT * LIMIT 366');
            query.send(handleQueryResponse);
      }

      function handleQueryResponse(response) {
        if (response.isError()) {
          alert('Error in query: ' + response.getMessage() + ' ' + response.getDetailedMessage());
          return;
        }

        var data = new google.visualization.DataTable();
        data.addColumn('date', 'Date');
        data.addColumn('number', 'Min');
        data.addColumn('number', 'Delta');
        data.addColumn('number', '2015 temp');
        data.addColumn('number', '2015 Min');
        data.addColumn('number', '2015 Max');

        var raw_data = response.getDataTable();
        var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
        
        var options = {
          height: 600,
          width: 1500,
          seriesType: 'bars',
          isStacked: true,
          series: {
            0: { type: 'bars', color: 'white', enableInteractivity: false }, // also, this worked to remove tooltip:  tooltip: false
            1: { type: 'bars', color: '#aa7243' },
            2: { type: 'line', color: 'black' },
            3: { type: 'line', color: '#0000e6', lineWidth: 5 },
            4: { type: 'line', color: '#e50000', lineWidth: 5 }
          },
          hAxis: { 
            gridlines: { count: 12, color: 'white' }
            // format: 'date'; 
          },
          vAxis: { gridlines: { count: 6, color: 'white' } },
          legend: 'top'
        };
        
        // draw chart
        chart.draw(raw_data, options);

      }
    </script>
    
  </head>

  <body>

    <h1 style="font-family: Arial; font-size: 28px; margin-left: 5%; margin-top: 30px;">Boston Weather in 2015 (a recreation of Tufte's famous chart using Google's Chart API)</h1>

    

    <!--Div that will hold the chart-->
    <div id="chart_div"></div>

    <!-- Divs for the text overlays -->
    
</html>


