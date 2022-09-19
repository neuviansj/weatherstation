
function getDataDay()  {


  var dat = document.getElementById('datepicker').value;

  //console.log(dat);

  $.ajax({
    url: "http://localhost/~neuvians/weather/data2.php?dat=" + dat,
    method: "GET",
    success: function(data) {
      console.log(data);
      var time = [];
      var temperature = [];
      var humidity    = [];
      var pressure    = [];
      var temp        = [];
      var humid       = [];
      var press       = [];
      var timeString  = [];
      var hr,min,sec  = 0;
      var date;

      for(var i in data) {
        date = new Date (Date.parse(data[i].time));
        date.setHours(date.getHours()-1);
        time.push(date);
        temp.push(parseFloat(data[i].temperature));
        humid.push(parseFloat(data[i].humidity));
        press.push(parseFloat(data[i].pressure));

        temperature.push({
          x: time[i],
          y: temp[i]
        });

        humidity.push({
          x: time[i],
          y: humid[i]
        });

        pressure.push({
          x: time[i],
          y: press[i]
        });

      }

      maxTemp = (Math.max.apply(null, temp));
      maxTempTime = new Date(Date.parse(time[temp.indexOf(maxTemp)]));
      hrs     = maxTempTime.getHours().toString();
      if (hrs.length<2) hrs = "0"+hrs;
      mins    = maxTempTime.getMinutes().toString();
      if (mins.length<2) mins = "0"+mins;
      document.getElementById('maxTemp').innerHTML = "<img src='png/temperature_48px.png'>Max. Temperature: " + maxTemp + "&deg;C at " + hrs + ":" + mins + " Hrs.";

      minTemp = (Math.min.apply(null, temp));
      minTempTime = new Date(Date.parse(time[temp.indexOf(minTemp)]));
      hrs     = minTempTime.getHours().toString();
      if (hrs.length<2) hrs = "0"+hrs;
      mins    = minTempTime.getMinutes().toString();
      if (mins.length<2) mins = "0"+mins;
      document.getElementById('minTemp').innerHTML = "<img src='png/temperature2_48px.png'>Min. Temperature: " + minTemp + "&deg;C at " + hrs + ":" + mins + " Hrs.";

      document.getElementById('tempspan').innerHTML = "Temperature Span: " + (parseFloat(maxTemp)-parseFloat(minTemp)).toFixed(2) + "&deg;C";


      maxHumid = (Math.max.apply(null, humid));
      maxHumidTime = new Date(Date.parse(time[humid.indexOf(maxHumid)]));
      hrs     = maxHumidTime.getHours().toString();
      if (hrs.length<2) hrs = "0"+hrs;
      mins    = maxHumidTime.getMinutes().toString();
      if (mins.length<2) mins = "0"+mins;
      document.getElementById('maxHumid').innerHTML = "<img src='png/humidity_48px.png'>Max. Humidity: " + maxHumid + "% at " + hrs + ":" + mins + " Hrs.";

      minHumid = (Math.min.apply(null, humid));
      minHumidTime = new Date(Date.parse(time[humid.indexOf(minHumid)]));
      hrs     = minHumidTime.getHours().toString();
      if (hrs.length<2) hrs = "0"+hrs;
      mins    = minHumidTime.getMinutes().toString();
      if (mins.length<2) mins = "0"+mins;
      document.getElementById('minHumid').innerHTML = "<img src='png/humidity_48px.png'>Min. Humidity: " + minHumid + "% at " + hrs + ":" + mins + " Hrs.";

      document.getElementById('humidspan').innerHTML = "Humidity Span: " + (parseFloat(maxHumid)-parseFloat(minHumid)).toFixed(2) + "%";


      maxPress = (Math.max.apply(null, press));
      maxPressTime = new Date(Date.parse(time[press.indexOf(maxPress)]));
      hrs     = maxPressTime.getHours().toString();
      if (hrs.length<2) hrs = "0"+hrs;
      mins    = maxPressTime.getMinutes().toString();
      if (mins.length<2) mins = "0"+mins;
      document.getElementById('maxPress').innerHTML = "<img src='png/pressure_48px.png'>Max. Pressure: " + maxPress + " hPa at " + hrs + ":" + mins + " Hrs.";

      minPress = (Math.min.apply(null, press));
      minPressTime = new Date(Date.parse(time[press.indexOf(minPress)]));
      hrs     = minPressTime.getHours().toString();
      if (hrs.length<2) hrs = "0"+hrs;
      mins    = minPressTime.getMinutes().toString();
      if (mins.length<2) mins = "0"+mins;
      document.getElementById('minPress').innerHTML = "<img src='png/pressure_48px.png'>Min. Pressure: " + minPress + " hPa at " + hrs + ":" + mins + " Hrs.";
      document.getElementById('pressspan').innerHTML = "Pressure Span: " + (parseFloat(maxPress)-parseFloat(minPress)).toFixed(2) + " hPa";


      var chart = new CanvasJS.Chart("chart-container",  {

          zoomEnabled: true,
          theme: "light2",
          title: {
            text: "Weather of Macheira " + dat,
            fontSize: 42
          }, 

          axisX: {      
              valueFormatString: "HH:mm",
              title: "Time of day (UTC+01:00)",
              titleFontSize: 28,
              labelFontSize: 18,
              interval: 2,
              intervalType: "hour"
          },

          axisY2: [{
              title: "Relative humidity %",
              titleFontSize: 28,
              percentFormatString: "#0.##",
              maximum: 100,
              minimum: 0,
              labelFontSize: 20,
              titleFontColor: "rgba(0,0,200,0.5)",
              labelFontColor: "rgba(0,0,200,0.5)"
          },


          {
              title: "Air Pressure hPa",
              titleFontSize: 28,
              percentFormatString: "#0.##",
              maximum: 1010,
              minimum: 975,
              labelFontSize: 20,
              titleFontColor: "green",
              labelFontColor: "green"
          }],

          axisY: {
              title: "Temperature C",
              titleFontSize: 28,
              valueFormatString: "#0.##",          
              maximum: 50,
              minimum: -5,
              labelFontSize: 20,
              interlacedColor: "#F0F0F0",
              titleFontColor: "red",
              labelFontColor: "red"

          },

     
           
          data: [

            {        
              type: "spline",
              axisYIndex: 0,
              axisYType: "secondary",
              color: "rgba(0,0,200,0.5)", 
              dataPoints: humidity,
              showInLegend: true,
              name: "Humidity",
              legendText: "Humidity",
              legendFontSize: 28,
              toolTipContent: "{x} {y}%"
            },

            {        
              type: "spline",
              color: "red",
              dataPoints: temperature,
              showInLegend: true,
              name: "Temperature",
              legendText: "Temperature",
              legendFontSize: 28,
              toolTipContent: "{x}<br> {y}&deg;C"
              
            },
            
            {        
              type: "spline",
              axisYIndex: 1,
              axisYType: "secondary",
              color: "green",
              dataPoints: pressure,
              showInLegend: true,
              name: "Pressure",
              legendText: "Air Pressure",
              legendFontSize: 28,
              toolTipContent: "{x}<br> {y} hPa"
            }
            
          ]
    });
    chart.render();
      
    },
    error: function(data) {
      console.log(data);
    }
  });
}


function getSunData()  {


  var dat = document.getElementById('datepicker').value;

  console.log(dat);

  $.ajax({
    url: "http://localhost/~neuvians/weather/sun.php?dat=" + dat,
    method: "GET",
    success: function(data) {
      console.log(data);

      console.log("Sunrise: "+data.sunrise);
      console.log("Sunset:  "+data.sunset);


    },

    error: function(data) {
      console.log(data);
    }

  });
}