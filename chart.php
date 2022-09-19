<!DOCTYPE html>
<html>
  <head>
    <title>Macheira Weather Graph</title>
    
  </head>
  <body onload=update();>
    <div>
    <div id="chart-container"></div>

    <!-- javascript -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script type="text/javascript" src="js/app.js"></script>
    <link rel="stylesheet" href="css/stylesheet.css">

    <script type="text/javascript">

        $( function() {
          $( "#datepicker" ).datepicker({dateFormat: "yy-mm-dd", defaultDate: null, maxDate: "Now", showButtonPanel: true});
        } );

        function update()  {

          getDataDay();
          getSunData();

        }

    </script>

    <div id="calender">
    <p> Select Date: <input type="text" id="datepicker" onchange=update();></p>
    </div>

    <div id="sunrise">
      <p id="sr">Sunrise: </p>
      <p id="ss">Sunset:  </p>  
      <p id="sunspan">Daylight:  </p> 

      <script type="text/javascript">
        function getSunData()  {

          var dat = document.getElementById('datepicker').value;

          $.ajax({
            url: "http://localhost/~neuvians/weather/sun.php?dat=" + dat,
            method: "GET",
            success: function(data) {

              document.getElementById('sr').innerHTML = "<img src='png/sunrise_48px.png'>Sunrise: " + data.sunrise + " Hrs.";
              document.getElementById('ss').innerHTML = "<img src='png/sunset_48px.png'>Sunset:  " + data.sunset + " Hrs.";
              document.getElementById('sunspan').innerHTML = "Daylight: " + data.daylight + " Hrs.";

            },

            error: function(data) {
              console.log(data);
            }

          });
        }
      </script>

    </div>


    <div id="temp">
      <p id="maxTemp"><img src="png/temperature_48px.png">Max. Temperature:  </p> 
      <p id="minTemp"><img src="png/temperature2_48px.png">Min. Temperature:  </p>
      <p id="tempspan">Temperature Span:  </p>  
    </div>

    <div id="humid">
      <p id="maxHumid"><img src="png/humidity_48px.png">Max. Humidity:  </p> 
      <p id="minHumid"><img src="png/humidity_48px.png">Min. Humidity:  </p>
      <p id="humidspan">Humidity Span:  </p> 
    </div>

    <div id="press" class="box">
      <p id="maxPress"><img src='png/pressure_48px.png'>Max. Pressure:  </p> 
      <p id="minPress"><img src='png/pressure_48px.png'>Min. Pressure:  </p> 
      <p id="pressspan">Pressure Span:  </p>
    </div>
  </div>
  </body>
</html>