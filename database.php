<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "map";

//Create connection
$conn = new mysqli($servername, $username, $password);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

// Create database
$sql = "CREATE DATABASE map";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully";
} else {
    echo "Error creating database: " . $conn->error;
}

// sql to create table


$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 


$info = "CREATE TABLE info (
name VARCHAR(30)  , 
src VARCHAR(30) ,
sig VARCHAR(11) ,
recDate VARCHAR(11) ,
recTime VARCHAR(11) ,
primary key (name, src, recTime)
)";

$maping = "CREATE TABLE maping (
    name VARCHAR(30) PRIMARY KEY , 
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