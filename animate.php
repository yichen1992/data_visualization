<html>
  <head>
    <style>
      .button {
          background-color: #008CBA; /* Blue */
          border: none;
          color: white;
          padding: 15px 32px;
          margin-left: 20px;
          text-align: center;
          text-decoration: none;
          display: inline-block;
          font-size: 16px;
      }
      .button:hover {
          background-color: #66bad5;
      }
    </style>
    <!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">

    // Load the Visualization API and the corechart package.
    google.charts.load('current', {'packages':['corechart']});

    // Set a callback to run when the Google Visualization API is loaded.
    google.charts.setOnLoadCallback(drawSheetName);

    // Callback that pulls data from Google Sheet,
    // instantiates the chart, passes in the data and
    // draws it.

    function drawSheetName() {
      var query = new google.visualization.Query('https://docs.google.com/spreadsheets/d/1i0aZOeuBpe4ny1DwzSHiNBmGosSZpwWiYB1ZRn8OUUo/edit');
      query.setQuery('SELECT * LIMIT 366');
      query.send(handleSampleDataQueryResponse);
    }

    function handleSampleDataQueryResponse(response) {
      if (response.isError()) {
        alert('Error in query: ' + response.getMessage() + ' ' + response.getDetailedMessage());
        return;
      }

      var initial_options = {
        height: 600,
        //curveType: 'function',
        vAxis: { 
          minValue: 0,
          title: 'Temperature (F)',
          maxValue: 100
        },
        legend: 'none',
        series: {
          0: { color: '#dbdbdb', lineDashStyle: [4, 4] }, 
          1: { color: '#ff4c4c' }
        },
        hAxis: {
          title: 'Month'
        }
      };
      
      var data = response.getDataTable();
      var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
      var view = new google.visualization.DataView(data);

      // set columns initially
      view.setColumns([0,1,2]);

      // draw the chart initially
      chart.draw(view, initial_options);
      
      // draw each new series
      var i = 0, howManyTimes = data.getNumberOfColumns() - 2;
      var arr = [0,1];

      function updateChart() {
          if (i < howManyTimes ){
            arr.push(i+2);
          }
          view.setColumns(arr);

          // odd number columns are the averages - want dashed lines, light grey #e9e9e9
          // even number columns are the actuals - solid lines, light red #ffdbdb
          // current line - solid red, #ff4c4c

          var seriesObj = {};
          console.log("i = " + i);
          // loop to build series object
          for (var j = 0; j < i; j++) {
            
            console.log("j = " + j);
            console.log("mod j = " + (j % 2));
            var innerObj = {};
            if ((j % 2) === 0) {
              innerObj["color"] = '#e9e9e9';
              innerObj["lineDashStyle"] = [4,1];
            }
            else {
              innerObj["color"] = '#ffdbdb';
              innerObj["lineDashStyle"] = [0,0];
            }
            seriesObj[j] = innerObj;
          }

          // add last items
          var innerObjFinal1 = {};
          if (((i) % 2) === 0) {
              innerObjFinal1["color"] = '#999999';
            }
            else {
              innerObjFinal1["color"] = '#ff4c4c';
            }
          seriesObj[i] = innerObjFinal1;

          var innerObjFinal2 = {};
          if (((i+1) % 2) === 0) {
              innerObjFinal2["color"] = '#999999';
            }
            else {
              innerObjFinal2["color"] = '#ff4c4c';
            }
          seriesObj[i+1] = innerObjFinal2;

          console.log(seriesObj);


          var update_options = {
            vAxis: { 
              minValue: 0, 
              maxValue: 100,
              title: 'Temperature (F)'
            },
            legend: 'none',
            series: seriesObj,
            hAxis: {
              title: 'Month'
            }
          };

          drawChart(view,update_options);
          i++;
          if( i < howManyTimes ){
              setTimeout( updateChart, 50 );
              setTimeout( updateYear(i), 50 );
          }
      }
      function updateYear(i) {
        var year = ((i%2) === 0) ? (i/2) : (i/2 - 0.5);
        //console.log(i);
        document.getElementById('chart_year').innerHTML = 1995 + year;
      }
      updateChart();
      
      function drawChart(view,options) {
        chart.draw(view, options);
      }
      
      var button = document.getElementById('b1');

      button.onclick = function() {
        drawSheetName();
      }
    }
    </script>
    
  </head>

  <body>

    <h1 style="font-family: Arial; font-size: 28px; margin-left: 5%; margin-top: 30px;">Temperature trends Boston</h1>

    <p style="font-family: Arial; font-size: 16px; margin-left: 5%;">Created by Yichen Cheng and Zijian Yao

    <button type="button" id="b1" class="button">Refresh</button></p>
        
    <div style="color:#ff4c4c; font-size: 64px; text-align: center;" id="chart_year"></div>

    <!--Div that will hold the chart-->
    <div id="chart_div"></div>
</html>


