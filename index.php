<!DOCTYPE html>
<html lang="">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Route Detection and Coordinate Mapping Device</title>

    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.3/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

    <style type="text/css">
        h1 {
            font-size: 24pt;


        }

        body {
            background-color: #d0f0f6;
        }

        #wrapper {
            position: relative;
        }

        #over_map {
            position: absolute;
            top: 480px;
            left: 10px;
            z-index: 99;
        }

        #over_map2 {
            position: absolute;
            top: 700px;
            left: 10px;
            z-index: 99;
        }
    </style>

    <style>

        .table-wrapper-scroll-y {
display: block;
max-height: 200px;
overflow-y: auto;
-ms-overflow-style: -ms-autohiding-scrollbar;
}

     th {
      background-color: #588c7e;
      color: white;
       }
     tr:nth-child(even) {background-color: #f2f2f2}
    </style>

    <title>Map</title>
    <meta name="viewport" content="initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script type="text/javascript">
        //    < script src = "https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false" >
    </script>
    <meta charset="utf-8">
    <style>
        #map {
            height: 100%;
            width: 100%;
        }

        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
    </style>


</head>

<body>
    <h1 class="text-center">Route Detection and Coordinate Mapping Device using Wi-Fi Signal</h1>

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Bootstrap JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>


    <body>


        <div id="map"></div>
        <script>
            function initMap() {
                var mapOptions = {
                    center: {
                        <?php
                            $conn = mysqli_connect("localhost", "root", "", "map");
                           // $sql = "SELECT lat,lng  FROM maping WHERE id = 1 ";
                           // $result = $conn->query($sql);
                             // output data of each row
                       // $row = $result->fetch_assoc();
                        // $lat = $row["lat"] ;
                        // $lng = $row["lng"];
    
                        $lat = 13.796472 ;
                        $lng = 100.324027;
                        ?>
                        lat: 13.796472,
                        lng: 100.324027
                    },
                    zoom: 18,
                }

                var maps = new google.maps.Map(document.getElementById("map"), mapOptions);


                //////////////////////////// marker /////////////////////////////////////////

                var marker, info;
                $.getJSON("jsondata.php", function (jsonobj) {
                    $.each(jsonobj, function (i, item) {
                        var marker = new google.maps.Marker({
                            position: new google.maps.LatLng(item.lat, item.lng),
                            map: maps,
                        });
                        info = new google.maps.InfoWindow();
                        google.maps.event.addListener(marker, 'click', (function (marker, i) {
                            return function () {
                                info.setContent(item.name);
                                info.open(maps, marker);
                            }
                        })(marker, i));
                    });
                });

 

                //////////////////// PolyLine /////////////////////////////////////////////

                var flightPlanCoordinates = [];
                $.getJSON("datajson2.json",function (json) {
                 // flightPath.setMap(null);
                    for (var i = 0; i < json.length; i++) {
                        var latLng = new google.maps.LatLng((json[i].lat), (json[i].lng));
                        flightPlanCoordinates.push(latLng);
                    }
                    var flightPath = new google.maps.Polyline({
                        path: flightPlanCoordinates,
                        geodesic: true,
                        strokeColor: '#0000FF',
                        strokeOpacity: 1.0,
                        strokeWeight: 2,
                        icons: [{
                            icon: {
                                path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW
                            },
                            offset: '100%',
                            repeat: '65px'
                        }]

                    });
                    flightPath.setMap(maps);
                    
                });

            }

        </script>
        <script async defer <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCvKYJq_cHLpj6GOfvZghyNgLoy1Da3quk&callback=initMap">
        </script>


        <?php
        //  header("Cache-Control: no-cache, must-revalidate");
        // header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        // header("Content-Type: application/xml; charset=utf-8");
        // error_reporting(E_ERROR | E_PARSE);
        error_reporting(0);
   
  $mysqli = NEW MySQLi("localhost", "root", "", "map");
   $Mac = $mysqli->query("SELECT DISTINCT src , COUNT(DISTINCT name ) FROM info GROUP BY src HAVING COUNT(DISTINCT name ) > 0 ");
 ?>

        <!-- /////////////////////////////////////////////////// select //////////////////////////////// -->

        <form action="index.php" method="post">
            <select name="MAC">
                <option disabled selected value> -- Select -- </option>
                <?php
   while($row = $Mac->fetch_assoc())
   {
    $mac = $row['src']; 
    echo "<option value='$mac'>$mac</option>";
   }
   ?>
            </select>
            <?php include'jsonpath.php';
        
        ?>
            <input name="submit" type="submit" value="Confirm">
        </form>

        <!--  ///////////////////////////////      LINK        /////////////////////////////////////       //////////////////////////////////          ///////////////// -->

        <a href="http://127.0.0.1/googleapi/putinfo.php">Put data to database || </a>
        <a href="http://127.0.0.1/googleapi/database.php">Create database </a>

        <!-- //////////////////////////////////////////////////////////////////////    -->

        <div id="over_map" class="table-wrapper-scroll-y">

            <table class="table table-bordered table-striped">
                <tr>
                    <th>MAC that detect all devices</th>
                </tr>
                <?php
                flush();
     $conn = mysqli_connect("localhost", "root", "", "map");
     // Check connection
     if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
     }

 

     $result_row = mysqli_query($conn,"SELECT DISTINCT name FROM maping");
     $num_rows = mysqli_num_rows($result_row);


     $sql = "SELECT src , COUNT(DISTINCT name ) FROM info GROUP BY src HAVING COUNT(DISTINCT name ) >= $num_rows ";
     $result = $conn->query($sql);
     if ($result->num_rows > 0) {
      // output data of each row
      while($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row["src"] . "</td><tr>";
       
   }
   //echo "</table>";
   } else { echo "0 results"; }
   $conn->close();


 
   ?>
            </table>

        </div>

        <form>
            <div class="form-group" action="" method="get">
                <label>Select Time</label>
                <input type="Start_Time" name="Start_Time" class="form-control" id="Start_Time" value="" placeholder="HH.MM">

            </div>
            <div class="form-group">
                <input type="End_Time" name="End_Time" class="form-control" id="End_Time" value="" placeholder="HH.MM">

            </div>

            <div class="form-group">
                <input type="Date" name="Date" class="form-control" id="Date" value="" placeholder="YYYY-MM-DD">

            </div>

            <button type="submit" class="btn btn-primary">Submit</button>

        </form>

        <?php $a =  $_GET['Start_Time']; ?>
        <?php $b =  $_GET['End_Time']; ?>
        <?php $c =  $_GET['Date']; ?>
        <!--////////////////////////////////////////////////   table    /////////////////////////////////////////////////////////// -->


        <div id="over_map2" class="table-wrapper-scroll-y">
            <table class="table table-bordered table-striped">
                <tr>
                    <th>In Between Time</th>
                    <?php echo "$a - $b" ;?>
                </tr>
                <?php
                flush();
$conn = mysqli_connect("localhost", "root", "", "map");
// Check connection
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}

// $sql = "SELECT src  FROM info WHERE recDate LIKE '$c'  AND recTime BETWEEN $a AND $b  GROUP BY src  ";
$sql = "SELECT src  FROM info WHERE    recTime BETWEEN $a AND $b AND recDate = '$c'  GROUP BY src  ";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
// output data of each row
while($row = $result->fetch_assoc()) {
echo "<tr><td>" . $row["src"] . "</td><tr>";

}
//echo "</table>";
} else { echo "0 results"; }
$conn->close();
?>
            </table>
        </div>

<?php
error_reporting(0);
?>
    </body>

</html>