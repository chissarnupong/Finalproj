<?php

$conn = mysqli_connect("localhost", "root", "", "map");
// Check connection
if ($conn->connect_error) {
 die("Connection failed: " . $conn->connect_error);
}
$sql = " DELETE FROM info ";
$result = $conn->query($sql);
$conn->close();


?>