<html>
    <head>
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
            var url = "https://docs.google.com/spreadsheets/d/1cK0UPWvmf23iK3qu3QXrpgcsammpZseROuCN5oHFdO0/edit#gid=0&headers=1";
            var sendVal = url + '#gid=0&headers=1&tq=' + queryString;
            var query = new google.visualization.Query(sendVal);
            query.send(handleQueryResponse);
        }*/
        
        function drawChart() {
            var query = new google.visualization.Query('https://docs.google.com/spreadsheets/d/1OwpQd0fIu_dLA7PRdbwZBDJ7q5TzIvvE3LKU9V3qxMY/edit#gid=0&headers=1');
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
                    0: { type: 'bars', color: 'white', enableInteractivity: false },
                    1: { type: 'bars', color: '#aa7243' },
                    2: { type: 'line', color: 'black' },
                    3: { type: 'line', color: '#0000e6', lineWidth: 5 },
                    4: { type: 'line', color: '#e50000', lineWidth: 5 }
                },
                hAxis: { 
                    gridlines: { count: 12, color: 'white' }
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
 
    <!--Div that will hold the chart-->
    <div id="chart_div"></div>
</html>