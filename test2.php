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
    $sql = "SELECT src,name  FROM qu WHERE src LIKE '0C:54:15:CF:DC:D0' ";
    $result = $conn->query($sql);
     // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row["name"] . "</td><tr>";

        $r = $row["name"];
        $sql2 = "SELECT lat,lng  FROM maping WHERE name LIKE '$r'  ";
        $result2 = $conn->query($sql2);
        $row2 = $result2->fetch_assoc();
        $lat = $row2["lat"];
        $lng = $row2["lng"];
        echo"__$lat";
        echo"__$lng __";

   }


//    $sql2 = "SELECT lat,lng  FROM maping WHERE name LIKE 'MikroTik 1'  ";
//    $result2 = $conn->query($sql2);
//    $row2 = $result2->fetch_assoc();
//    $lat = $row2["lat"];
//    $lng = $row2["lng"];
//    echo"$lat";

    ?>
</body>
</html>