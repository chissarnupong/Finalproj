<?php

header('Content-Type: application/json');
  $objConnect = mysqli_connect("localhost", "root", "", "map");

  $sql = "SELECT src,name  FROM qu WHERE src LIKE '0C:8F:FF:17:9F:9C' ";
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
  echo json_encode($resultArray);

  //  $resultArray = json_decode(json_encode($resultArray), true);
  //  print_r($resultArray);


?>
