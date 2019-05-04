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
            top: 280px;
            left: 10px;
            z-index: 99;
        }

        #over_map2 {
            position: absolute;
            top: 300px;
            left: 10px;
            z-index: 99;
        }

        #over_map3 {
            position: absolute;
            top: 480px;
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

        tr:nth-child(even) {
            background-color: #f2f2f2
        }
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
    //13.795935, 100.322993
                        // $lat = 13.796472 ;
                        // $lng = 100.324027;
                        ?>
                        lat: 13.795935,
                        lng: 100.322993
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
  
                        info.setContent(item.name);
                        info.open(maps, marker);
                    });
                });



                //////////////////// PolyLine /////////////////////////////////////////////

                var flightPlanCoordinates = [];
                $.getJSON("datajson.json", function (json) {
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
        <script async defer <script
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCvKYJq_cHLpj6GOfvZghyNgLoy1Da3quk&callback=initMap">
        </script>


        <?php
        //  header("Cache-Control: no-cache, must-revalidate");
        // header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        // header("Content-Type: application/xml; charset=utf-8");
        // error_reporting(E_ERROR | E_PARSE);
        error_reporting(0);
   
  
 ?>

        <!-- /////////////////////////////////////////////////// select //////////////////////////////// -->

        <form action="index.php" method="post">
            <select name="MAC">
                <option disabled selected value> -- Select -- </option>
                <?php

$mysqli = NEW MySQLi("localhost", "root", "", "map");
$Mac = $mysqli->query("SELECT DISTINCT src , COUNT(DISTINCT name ) FROM info GROUP BY src HAVING COUNT(DISTINCT name ) > 1 ");

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

        

        </div>


        <!--////////////////////////////////////////////////   table    /////////////////////////////////////////////////////////// -->


        <div id="over_map2" class="table-wrapper-scroll-y">


            <form action="#" method="post">
                <?php


$conn = mysqli_connect("localhost", "root", "", "map");
$name = "SELECT name  FROM maping ";
$result = $conn->query($name);
$a = 0;
    while($row = $result->fetch_assoc())
    {
      
    $name = $row['name']; 
    echo "<div class='checkbox'>";
    echo "<label><input type='checkbox' name='check_list[$a]' value='$name'>$name</label>";
    echo"</div>";
    $a = $a+1;
    }
    
  ?>
<input type="submit" name="submit1" value="Submit" />
            </form>



            <?php
                      $i = 0;
if(isset($_POST['submit1']))

{//to run PHP script on submit
if(!empty($_POST['check_list'])){
// Loop to store and display values of individual checked checkbox.
foreach($_POST['check_list'] as $selected[$i]){
//echo $selected[$i]."</br>";
$i=$i+1;
}
}

$connn = mysqli_connect("localhost", "root", "", "map");
$result_row = mysqli_query($connn,"SELECT DISTINCT name FROM info");
$num_rows = mysqli_num_rows($result_row);

//////////////////////////////////////////////////////////////

for($i=0;$i<=sizeof($selected);$i++){

$strSQL = "SELECT lat,lng FROM maping WHERE name LIKE '$selected[$i]'";

$objQuery = mysqli_query($connn,$strSQL);


}

while($obResult = mysqli_fetch_assoc($objQuery))
{
array_push($resultArray, $obResult);
}


$json =  json_encode($resultArray);
file_put_contents('datajson.json', $json);


////////////////////////////////////////////////////////////



if(sizeof($selected)==1){
$sql = "SELECT *  FROM info WHERE name LIKE '$selected[0]'";
echo "</br>"."</br>".$selected[0];
}

if(sizeof($selected)==2){
$sql = "SELECT *  FROM info WHERE name LIKE '$selected[0]' and src IN (SELECT src  FROM info WHERE name LIKE '$selected[1]')  ";
echo "</br>"."</br>".$selected[0]."<-->".$selected[1];
}

if(sizeof($selected)==3){
$sql = "SELECT *  FROM info WHERE name LIKE '$selected[0]' and src IN (SELECT src  FROM info WHERE name LIKE '$selected[1]') and src IN (SELECT src  FROM info WHERE name LIKE '$selected[2]') ";
echo "</br>"."</br>".$selected[0]."<-->".$selected[1]."<-->".$selected[2];
}

if(sizeof($selected)==4){
$sql = "SELECT *  FROM info WHERE name LIKE '$selected[0]' and src IN (SELECT src  FROM info WHERE name LIKE '$selected[1]') and src IN (SELECT src  FROM info WHERE name LIKE '$selected[2]') and src IN (SELECT src  FROM info WHERE name LIKE '$selected[3]') ";
echo "</br>"."</br>".$selected[0]."<-->".$selected[1]."<-->".$selected[2]."<-->".$selected[3];
}

if(sizeof($selected)==5){
$sql = "SELECT *  FROM info WHERE name LIKE '$selected[0]' and src IN (SELECT src  FROM info WHERE name LIKE '$selected[1]') and src IN (SELECT src  FROM info WHERE name LIKE '$selected[2]') and src IN (SELECT src  FROM info WHERE name LIKE '$selected[3]')and src IN (SELECT src  FROM info WHERE name LIKE '$selected[4]') ";
echo "</br>"."</br>".$selected[0]."<-->".$selected[1]."<-->".$selected[2]."<-->".$selected[3]."<-->".$selected[4];
}

if(sizeof($selected)==6){
$sql = "SELECT *  FROM info WHERE name LIKE '$selected[0]' and src IN (SELECT src  FROM info WHERE name LIKE '$selected[1]') and src IN (SELECT src  FROM info WHERE name LIKE '$selected[2]') and src IN (SELECT src  FROM info WHERE name LIKE '$selected[3]')and src IN (SELECT src  FROM info WHERE name LIKE '$selected[4]') and src IN (SELECT src  FROM info WHERE name LIKE '$selected[5]') ";
echo "</br>"."</br>".$selected[0]."<-->".$selected[1]."<-->".$selected[2]."<-->".$selected[3]."<-->".$selected[4]."<-->".$selected[5];
}

if(sizeof($selected)==7){
$sql = "SELECT *  FROM info WHERE name LIKE '$selected[0]' and src IN (SELECT src  FROM info WHERE name LIKE '$selected[1]') and src IN (SELECT src  FROM info WHERE name LIKE '$selected[2]') and src IN (SELECT src  FROM info WHERE name LIKE '$selected[3]')and src IN (SELECT src  FROM info WHERE name LIKE '$selected[4]') and src IN (SELECT src  FROM info WHERE name LIKE '$selected[5]') and src IN (SELECT src  FROM info WHERE name LIKE '$selected[6]') ";
echo "</br>"."</br>".$selected[0]."<-->".$selected[1]."<-->".$selected[2]."<-->".$selected[3]."<-->".$selected[4]."<-->".$selected[5]."<-->".$selected[6];
}

if(sizeof($selected)==8){
$sql = "SELECT *  FROM info WHERE name LIKE '$selected[0]' and src IN (SELECT src  FROM info WHERE name LIKE '$selected[1]') and src IN (SELECT src  FROM info WHERE name LIKE '$selected[2]') and src IN (SELECT src  FROM info WHERE name LIKE '$selected[3]')and src IN (SELECT src  FROM info WHERE name LIKE '$selected[4]') and src IN (SELECT src  FROM info WHERE name LIKE '$selected[5]') and src IN (SELECT src  FROM info WHERE name LIKE '$selected[6]') and src IN (SELECT src  FROM info WHERE name LIKE '$selected[7]') ";
echo "</br>"."</br>".$selected[0]."<-->".$selected[1]."<-->".$selected[2]."<-->".$selected[3]."<-->".$selected[4]."<-->".$selected[5]."<-->".$selected[6]."<-->".$selected[7];
}

if(sizeof($selected)==9){
$sql = "SELECT *  FROM info WHERE name LIKE '$selected[0]' and src IN (SELECT src  FROM info WHERE name LIKE '$selected[1]') and src IN (SELECT src  FROM info WHERE name LIKE '$selected[2]') and src IN (SELECT src  FROM info WHERE name LIKE '$selected[3]')and src IN (SELECT src  FROM info WHERE name LIKE '$selected[4]') and src IN (SELECT src  FROM info WHERE name LIKE '$selected[5]') and src IN (SELECT src  FROM info WHERE name LIKE '$selected[6]') and src IN (SELECT src  FROM info WHERE name LIKE '$selected[7]') and src IN (SELECT src  FROM info WHERE name LIKE '$selected[8]') ";
echo "</br>"."</br>".$selected[0]."<-->".$selected[1]."<-->".$selected[2]."<-->".$selected[3]."<-->".$selected[4]."<-->".$selected[5]."<-->".$selected[6]."<-->".$selected[7]."<-->".$selected[8];
}

if(sizeof($selected)==10){
$sql = "SELECT *  FROM info WHERE name LIKE '$selected[0]' and src IN (SELECT src  FROM info WHERE name LIKE '$selected[1]') and src IN (SELECT src  FROM info WHERE name LIKE '$selected[2]') and src IN (SELECT src  FROM info WHERE name LIKE '$selected[3]')and src IN (SELECT src  FROM info WHERE name LIKE '$selected[4]') and src IN (SELECT src  FROM info WHERE name LIKE '$selected[5]') and src IN (SELECT src  FROM info WHERE name LIKE '$selected[6]') and src IN (SELECT src  FROM info WHERE name LIKE '$selected[7]') and src IN (SELECT src  FROM info WHERE name LIKE '$selected[8]') and src IN (SELECT src  FROM info WHERE name LIKE '$selected[9]') ";
echo "</br>"."</br>".$selected[0]."<-->".$selected[1]."<-->".$selected[2]."<-->".$selected[3]."<-->".$selected[4]."<-->".$selected[5]."<-->".$selected[6]."<-->".$selected[7]."<-->".$selected[8]."<-->".$selected[9];
}

}

?>

        </div>

        <div id="over_map3" class="table-wrapper-scroll-y">
        <table class="table table-bordered table-striped">
        <?php
        $result = $connn->query($sql);
        if ($result->num_rows > 0) {
         // output data of each rows
         echo "</br>" ;
         while($row = $result->fetch_assoc()) {
             
           echo  "<tr><td>" . $row["src"] . "</td><tr>" ;
          
        }
        
        } else { echo "0 results";}
        $connn->close();
error_reporting(0);

?>
</table>
</div>

    </body>

</html>