<html>
    <head>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
            // Load the Visualization API and the corechart package.
            google.charts.load('current', {'packages':['corechart']});
 
            // Set a callback to run when the Google Visualization API is loaded.
            google.charts.setOnLoadCallback(drawChart);
            
            // function to load data sheet
            function drawChart() {
                var query = new google.visualization.Query('https://docs.google.com/spreadsheets/d/1i0aZOeuBpe4ny1DwzSHiNBmGosSZpwWiYB1ZRn8OUUo/edit#gid=0&headers=1');
                // query.setQuery('SELECT A, B, C, D, E, F');
                query.setQuery('SELECT * LIMIT 366');
                query.send(handleQueryResponse);
            }
            // function to handle query response
            function handleQueryResponse(response) {
                if (response.isError()) {
                    alert('Error in query: ' + response.getMessage() + ' ' + response.getDetailedMessage());
                    return;
                }
                
                var raw_data = response.getDataTable();
                var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
                var view = new google.visualization.DataView(raw_data);
                
                var currentCol = 1;         // start from 1995 average
                var arr = [0];
                var howManyColumns = raw_data.getNumberOfColumns();     // get column number of the chart
                var seriesObj = {};
                
                function updateChart() {
                    
                    var counter = 0;
                    // this is a portion to grey out history, important for the visualization
                    var index;
                    
                    for(index in seriesObj) {
                        var updateObj = {};
                        if(index % 2 === 0) {       // average temp
                            updateObj["type"] = 'line';
                            updateObj["color"] = '#d6d4d4';
                            seriesObj[index] = updateObj;
                        }
                        else {
                            updateObj["type"] = 'line';
                            updateObj["color"] = '#fcbdbd';
                            seriesObj[index] = updateObj;
                        }
                    }
                    
                    // this portion ends here
                    
                    //currentCol % 2 == 0, it is a year temperature, set line
                    //currentCol % 2 == 1, it is a year average temperature, set line
                    while (counter < 2) {
                        var innerObj = {};
                        if(currentCol % 2 === 0) {          // year temperature
                            innerObj["type"] = 'line';
                            innerObj["color"] = 'grey';
                        }
                        else {                              // average temperature
                            innerObj["type"] = 'line';
                            innerObj["color"] = 'red';
                        }
                        
                        // variable settings 
                        seriesObj[currentCol - 1] = innerObj;   // append value to new object
                        arr.push(currentCol);
                        view.setColumns(arr);
                        currentCol++;
                        counter++;
                    }
                    
                    // update print options
                    var options_update = {
                        height: 600,
                        width: 1000,
                        seriesType: 'bars',
                        isStacked: false,
                        series: seriesObj,
                        hAxis: {
                            gridlines: {color: 'white' },
                            title: 'Month'
                        },
                        vAxis: {
                            gridlines: {color: 'white' },
                            minValue: 0,
                            title: 'Temperature (F)',
                            maxValue: 100
                        },
                        legend: {position: 'none'}
                    };
                    chart.draw(view, options_update);
               
                    if (currentCol < howManyColumns) {
                        setTimeout(updateChart, 100);
                    }
                }
                updateChart();
            }
            
            
        </script>
    </head>
     <body>
        <!--Div that will hold the chart-->
        <div id="chart_div"></div>
    </body>
</html>

