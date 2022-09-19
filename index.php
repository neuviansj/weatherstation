
<?php

    include_once('esp-database.php');


    if ($_GET["readingsCount"])  {

        $data = $_GET["readingsCount"];
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        $readings_count = $_GET["readingsCount"];

    }

    else  {
        $readings_count = 20;   //Default fetch value
    }

    $last_reading       = getLastReadings();
    $last_reading_temp  = $last_reading["temperature"];
    $last_reading_humi  = $last_reading["humidity"];
    $last_time          = $last_reading["time"];

    $last_time  = date("Y-m-d H:i:s", strtotime("$last_time - 1 hours"));   //Timezone adjust

    $min_temp   = minReading($readings_count, 'temperature');
    $max_temp   = maxReading($readings_count, 'temperature');
    $avg_temp   = avgReading($readings_count, 'temperature');

    $min_humi   = minReading($readings_count, 'humidity');
    $max_humi   = maxReading($readings_count, 'humidity');
    $avg_humi   = avgReading($readings_count, 'humidity');

?>

<!DOCTYPE html>
<html>

    <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link rel="stylesheet" type="text/css" href="esp-style.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    </head>

    <header class="header">
        <h1>📊 ESP Weather Station</h1>
        <form method="get">
            <input type="number" name="readingsCount" min="1" placeholder="Number of readings (<?php echo $readings_count; ?>)">
            <input type="submit" value="UPDATE">
        </form>
    </header>

    <body>
        <p>Last reading: <?php echo $last_time; ?></p>

        <section class="content">

            <div class="box gauge--1">
                <h3>TEMPERATURE</h3>
                <div class="mask">
                    <div class="semi-circle"></div>
                    <div class="semi-circle--mask"></div>
                </div>

                <p style="font-size: 30px;" id="temp">--</p>

                <table cellspacing="5" cellpadding="5">
                    <tr>
                        <th colspan="3">Temperature <?php echo $readings_count; ?> readings</th>
                    </tr>
                    <tr>
                        <td>Min</td>
                        <td>Max</td>
                        <td>Average</td>
                    </tr>
                    <tr>
                        <td><?php echo round($min_temp['min_amount'], 2); ?> &deg;C</td>
                        <td><?php echo round($max_temp['max_amount'], 2); ?> &deg;C</td>
                        <td><?php echo round($avg_temp['avg_amount'], 2); ?> &deg;C</td>
                    </tr>
                </table>

            </div>

            <div class="box gauge--2">
                <h3>HUMIDITY</h3>
                <div class="mask">
                    <div class="semi-circle"></div>
                    <div class="semi-circle--mask"></div>
                </div>

                <p style="font-size: 30px;" id="humi">--</p>

                    <table cellspacing="5" cellpadding="5">
                    <tr>
                        <th colspan="3">Humidity <?php echo $readings_count; ?> readings</th>
                    </tr>
                    <tr>
                        <td>Min</td>
                        <td>Max</td>
                        <td>Average</td>
                    </tr>
                    <tr>
                        <td><?php echo round($min_humi['min_amount'], 2); ?> %</td>
                        <td><?php echo round($max_humi['max_amount'], 2); ?> %</td>
                        <td><?php echo round($avg_humi['avg_amount'], 2); ?> %</td>
                    </tr>
                </table>

            </div>

        </section>


        <?php
            echo   '<h2> View Latest ' . $readings_count . ' Readings</h2>
                    <table cellspacing="5" cellpadding="5" id="tableReadings">
                    <tr>
                    <th>ID</th>
                    <th>Sensor</th>
                    <th>Temperature &deg;C</th>
                    <th>Humidity %</th>
                    <th>Pressure (mBar)</th>
                    <th>Timestamp</th>
                    </tr>';

            $result = getAllReadings($readings_count);

            if ($result)  {

                while ($row = $result->fetch_assoc())  {

                    $row_id             = $row["id"];
                    $row_fk_Sensor      = $row["fk_Sensor"];
                    $row_temperature    = $row["temperature"];
                    $row_humidity       = $row["humidity"];
                    $row_pressure       = $row["pressure"];
                    $row_time           = $row["time"];
 
                    $row_time = date("Y-m-d H:i:s", strtotime("$row_time - 1 hours"));

                    echo '<tr>
                    <td>' . $row_id . '</td>
                    <td>' . $row_fk_Sensor . '</td>
                    <td>' . $row_temperature . '</td>
                    <td>' . $row_humidity . '</td>
                    <td>' . $row_pressure . '</td>
                    <td>' . $row_time . '</td>
                    </tr>';

                }

                echo '</table>';
                $result->free();
            }
        ?>

        <script>

            var value1 = <?php echo $last_reading_temp; ?>;
            var value2 = <?php echo $last_reading_humi; ?>;

            setTemperature(value1);
            setHumidity(value2);

            function setTemperature(curVal)  {

                var minTemp = -20.0;
                var maxTemp = 60.0;

                var newVal  = scaleValue(curVal, [minTemp, maxTemp], [0, 180]);

                $('.gauge--1 .semi-circle--mask').attr({
                    style: '-webkit-transform: rotate(' + newVal + 'deg);' +
                    '-moz-transform: rotate(' + newVal + 'deg);' +
                    'transform: rotate(' + newVal + 'deg);'
                });

                $("#temp").text(curVal + ' ºC');

            }

            function setHumidity(curVal)  {

                var minHumi = 0;
                var maxHumi = 100;

                var newVal  = scaleValue(curVal, [minHumi, maxHumi], [0, 180]);
                $('.gauge--2 .semi-circle--mask').attr({
                    style: '-webkit-transform: rotate(' + newVal + 'deg);' +
                    '-moz-transform: rotate(' + newVal + 'deg);' +
                    'transform: rotate(' + newVal + 'deg);'
                });

                $("#humi").text(curVal + ' %');

            }

            function scaleValue(value, from, to)  {

                var scale   = (to[1] - to[0]) / (from[1] - from[0]);
                var capped  = Math.min(from[1], Math.max(from[0], value)) - from[0];

                return ~~(capped * scale + to[0]);

            }

        </script>

    </body>

</html>
