<?php

if(isset($_POST['submit'])){
  if(!empty($_POST['MAC'])) {
 // echo "<span>You have selected :</span><br/>";
  $target = $_POST['MAC'];
  }
  else { echo "<span>Please Select Atleast One .</span><br/>";}
  }

if(!empty($target)){
// header('Content-Type: application/json');

  $objConnect = mysqli_connect("localhost", "root", "", "map");
  $sql = "SELECT src,name  FROM info WHERE src LIKE '$target' ORDER BY recTime ASC";
  $result = $objConnect->query($sql);
  $resultArray = array();

  while($row = $result->fetch_assoc()){

  $r = $row["name"];
  $strSQL = "SELECT lat,lng FROM maping WHERE name LIKE '$r'";
  $objQuery = mysqli_query($objConnect,$strSQL);
  
  
  while($obResult = mysqli_fetch_assoc($objQuery))
  {
  array_push($resultArray, $obResult);
  }

}
 // echo json_encode($resultArray);
 
echo "Target :: $target ";

$json =  json_encode($resultArray);
//echo"$json";

file_put_contents('datajson.json', $json);
}
?>



