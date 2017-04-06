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
            var query = new google.visualization.Query('https://docs.google.com/spreadsheets/d/1NfunRiZRRILwgnohdwEFMclL7dCe56Fa_mY5Qd6_H-0/edit#gid=0&headers=1');
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
            
 
            var raw_data = response.getDataTable();
            var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
            var view = new google.visualization.DataView(raw_data);
            
            var currentCol = 3;
            var arr = [0,1,2];
            var howManyColumns = raw_data.getNumberOfColumns();     // exclude first three columns
            // console.log("columns in sheet are "+howManyColumns);
            var year = 1994;
            var seriesObj = {           // set it as a global object
                0: { type: 'bars', color: 'white', enableInteractivity: false },    // min value 
                1: { type: 'bars', color: '#aa7243' }                               // historic range
            };
            
            function updateChart() {
                // this is a counter that 
                var counter = 0;
                // currentCol % 3 === 0 --> it is a temperature input, corresponding to seriesObj[currentCol - 1]
                // currentCol % 3 === 1 --> it is a record low input, corresponding to seriesObj[currentCol - 1]
                // currentCol % 3 === 2 --> it is a record high input, corresponding to seriesObj[currentCol - 1]
                
                // preprocessing previous colors - dash it out
                // use index to count how many indexes should be processed
                var index;
                
                for(index in seriesObj) {
                    var updateObj = {};
                   
                    if (index > 1 && index % 3 === 2) {   // temperature preprocessing
                        updateObj["color"] = '#e8e8e8';
                        updateObj["type"] = 'line';
                        updateObj["lineWidth"] = 0.9;
                        seriesObj[index] = updateObj;
                    }
                    else if (index > 1 && index % 3 === 0) {  // low temp preprocessing
                        updateObj["color"] = '#c4d3ed';
                        updateObj["type"] = 'line';
                        updateObj["lineWidth"] = 3;
                        seriesObj[index] = updateObj;
                    }
                    else if (index > 1 && index % 3 === 1) {  // high temp preprocessing
                        updateObj["color"] = '#edc4d0';
                        updateObj["type"] = 'line';
                        updateObj["lineWidth"] = 3;
                        seriesObj[index] = updateObj;
                    }
                }
                // this is a step that adds new data into the table
                while (counter < 3){
                    // construct inner obj at this step, append it to seriesObj at the end
                    // append it one at a time
                    var innerObj = {};
                    if (currentCol % 3 === 0) {             // daily average temperature column
                        innerObj["type"] = 'line';
                        innerObj["color"] = 'black';
                        data.addColumn("number", "daily");
                    }
                    else if (currentCol % 3 === 1) {        // minimum temperature 
                        innerObj["type"] = 'line';
                        innerObj["color"] = '#0000e6';
                        innerObj["lineWidth"] = 5;
                        data.addColumn("number", "min");
                    }   
                    else {                                  // maximum temperature
                        innerObj["type"] = 'line';
                        innerObj["color"] = '#e50000';
                        innerObj["lineWidth"] = 5;
                        data.addColumn("number", "max");
                    }
                    
                    // variable settings 
                    seriesObj[currentCol - 1] = innerObj;   // append value to new object
                    arr.push(currentCol);
                    view.setColumns(arr);
                    currentCol++;
                    counter++;
                }
                
                //console.log("counter value is "+counter);
                //console.log("array right now is "+arr);
                
                var options_update = {
                    height: 600,
                    width: 1300,
                    seriesType: 'bars',
                    isStacked: true,
                    series: seriesObj,
                    hAxis: { 
                        gridlines: { count: 12, color: 'white' }
                    },
                    vAxis: { 
                        gridlines: { count: 6, color: 'white' } 
                    },
                    legend: 'none'
                };
                
               
                
                chart.draw(view, options_update);
                
                year++;
                
                if (currentCol < howManyColumns) {
                    setTimeout(updateChart, 1000);
                    setTimeout(updateYear, 1000);
                }
            }
            
            function updateYear() {
                document.getElementById("year").innerHTML = "year: "+year;
                
            }
            
            updateChart();
            
            
        }
        </script>
        <style>
            * {
                box-sizing: border-box;
                margin: 0;
                padding: 0;
                font-family: 'Open Sans', sans-serif;
            }
            body{
                min-width: 1000px;
            }
            .titleClass1 {
                margin-top: 30px;
                text-align: center;
                font-weight: 300;
                font-size:   2.5em;
            }
            .titleClass2 {
                margin-top: 20px;
                text-align: center;
                font-weight: 300;
                font-size: 1.9em;
            }
        </style>
    </head>
 
  <body>
    <h1 class = "titleClass1">Miami Daily Average Temperature from 1996 to 2016</h1>
    <h2 class = "titleClass2" id="year">year: 1995</h2>
    <!--Div that will hold the chart-->
    <div id="chart_div"></div>
</html>