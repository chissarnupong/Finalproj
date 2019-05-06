<?php

$conn = mysqli_connect("localhost", "root", "", "map");
// Check connection
if ($conn->connect_error) {
 die("Connection failed: " . $conn->connect_error);
}
$sql = " DELETE FROM info ";
$result = $conn->query($sql);
$conn->close();


function Redirect($url, $permanent = false)
{
    header('Location: ' . $url, true, $permanent ? 301 : 302);

    exit();
}

Redirect('/googleapi/index.php', false);


?>