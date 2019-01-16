<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "map";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

// Create database
$sql = "CREATE DATABASE map1";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully";
} else {
    echo "Error creating database: " . $conn->error;
}


// sql to create table
$info = "CREATE TABLE info (
name VARCHAR(30)  , 
src VARCHAR(30) ,
tim INT(6) ,
sig INT(6) NOT NULL,
tst INT(6) NOT NULL,
reg_date TIMESTAMP,
primary key (name, src, tim)
)";

$maping = "CREATE TABLE maping (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY ,
    name VARCHAR(30) , 
    lat VARCHAR(30) NOT NULL,
    lng VARCHAR(30) NOT NULL,
    reg_date TIMESTAMP
    )";

// $conn->query($info) ;
$conn->query($maping) ;

if ($conn->query($info) === TRUE) {
    echo "Table  created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();


function Redirect($url, $permanent = false)
{
    if (headers_sent() === false)
    {
        header('Location: ' . $url, true, ($permanent === true) ? 301 : 302);
    }
    exit();
}
Redirect('http://127.0.0.1/googleapi/', false);

?>