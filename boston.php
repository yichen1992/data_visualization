<html>
    <head>
        <meta charset="UTF-8">
        <title>Temperature Change</title>
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,700' rel='stylesheet' type='text/css'>
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
                var year = 1945;
                var arr = [0];
                var howManyColumns = raw_data.getNumberOfColumns();     // get column number of the chart
                var seriesObj = {};
                
                var colorAvgTemp = ['#666666', '#8c8c8c', '#b3b3b3', '#d9d9d9', '#e6e6e6'];
                var colorYearTemp = ['#cc0000', '#ff1a1a', '#ff6666', '#ffb3b3', '#ffe6e6'];
                
                function updateChart() {
                    
                    var counter = 0;
                    // this is a portion to grey out history, important for the visualization
                    var index;
                    
                    for(index in seriesObj) {
                        var updateObj = {};
                        if(index % 2 === 0) {       // average temp
                            if (index < 10) {
                                updateObj["color"] = colorAvgTemp[index / 2];
                            }
                            else {
                                updateObj["color"] = colorAvgTemp[4];
                            }
                            updateObj["type"] = 'line';
                            // updateObj["lineDashStyle"] = [4,1];
                            seriesObj[index] = updateObj;
                        }
                        else {                      // year temp
                            if (index < 10) {
                                updateObj["color"] = colorYearTemp[(index - 1) / 2];
                            }
                            else {
                                updateObj["color"] = colorYearTemp[4];
                            }
                            updateObj["type"] = 'line';
                            //updateObj["lineDashStyle"] = [4,1];
                            seriesObj[index] = updateObj;
                        }
                    }
                    
                    var index2;                     // remove history, both array and slice
                    if (currentCol > 11) {          // check error on newly constructed input arr
                       arr.splice(1,2);
                       for (index2 in seriesObj) {
                           if (index > 3) {
                               seriesObj[index-2] = seriesObj[index];
                               seriesObj[index-3] = seriesObj[index-1];
                           }
                       }
                       delete seriesObj[10];            // already copied to 8?
                       delete seriesObj[11];            // already copied to 9?
                       console.log("delete occurred at"+currentCol);
                    }
                    
                    // this portion ends here
                    
                    //currentCol % 2 == 0, it is a year temperature, set line
                    //currentCol % 2 == 1, it is a year average temperature, set line
                    while (counter < 2) {
                        var innerObj = {};
                        if(currentCol % 2 === 0) {          // year temperature
                            innerObj["type"] = 'line';
                            innerObj["color"] = '#800000';
                        }
                        else {                              // average temperature
                            innerObj["type"] = 'line';
                            innerObj["color"] = '#404040';
                        }
                        
                        // variable settings
                        if (currentCol > 11) {              // trick-figure this out? 
                            if (currentCol % 2 === 1)
                                seriesObj[10] = innerObj;
                            else
                                seriesObj[11] = innerObj;
                        }
                        else {
                            seriesObj[currentCol - 1] = innerObj;   // append value to new object
                        }
                        arr.push(currentCol);
                        view.setColumns(arr);
                        currentCol++;
                        counter++;
                    }
                    console.log(arr);
                    console.log(currentCol);
                    console.log(seriesObj);
                    console.log("a year over");
                    // update print options
                    var options_update = {
                        height: 600,
                        width: 1300,
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
                            maxValue: 85
                        },
                        legend: {position: 'none'}
                    };
                    
                    
                    
                    chart.draw(view, options_update);
                    
                    year++;
               
                    if (currentCol < howManyColumns) {
                        setTimeout(updateChart, 100);
                        setTimeout(updateYear, 100);
                    }
                    else {          // MARK
                        updateValue();
                    }
                }
                
                function updateYear() {
                        document.getElementById("year").innerHTML = "year: "+(year-1);
                }
                
                function updateValue() {
                    document.getElementById("rangeMax").innerHTML = "Max: 53.80F(2012)";
                    document.getElementById("rangeMin").innerHTML = "Min: 49.41F(1965)";
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
            .titleClass1{
                margin-top: 30px;
                text-align: center;
                font-weight: 300;
                font-size:   2.5em;
            }
            .titleClass2{
                margin-top: 20px;
                text-align: center;
                font-weight: 300;
                font-size: 1.9em;
            }
            .rangeLabel {
                width: 100%;
                text-align: right;
                margin-right: 20px;
            }
            .rangeLabel .rangeText {
                font-weight: 200;
                font-size: 1em;
                text-align: right;
            }
        </style>
    </head>
     <body>
        <!--Div that will hold the chart-->
        <h1 class = "titleClass1">Boston Monthly Average Temperature Change Since 1945</h1>
        <h2 class = "titleClass2" id="year"></h2>
        <div class = "rangeLabel">
            <p  class = "rangeText" id = "rangeMax"></p>
            <p class = "rangeText" id = "rangeMin"></p> 
        </div>
        <div id="chart_div"></div>
        <!--div chart holder ends-->
    </body>
</html>

