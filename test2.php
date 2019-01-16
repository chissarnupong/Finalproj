<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <?php
    
 
    $conn = mysqli_connect("localhost", "root", "", "map");
    $sql = "SELECT lat,lng  FROM maping WHERE id = 1 ";
    $result = $conn->query($sql);
     // output data of each row
$row = $result->fetch_assoc();
$lat = $row["lat"] ;
$lng = $row["lng"];
echo "$lat";
echo "$lng";


    ?>
</body>
</html>