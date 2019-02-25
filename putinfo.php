<html>
<body>
  <?php
    //analyse data function
    if($dirFromClients = opendir('.\fromClients')){ // opendir to get file directory and use to read file name in directory to open with fopen()
        while (false !== ($entry = readdir($dirFromClients))) { // string readdir (resource $dir_handle)
        if($entry != "." && $entry != ".."){
          $file = fopen(__DIR__ . '/./fromClients/'.$entry, "r"); // __DIR__ is global constant that gives you the directory of the current file ('.' just gives you the directory of the root script executing).
          echo "File : ".$entry."<br><br>=========================================<br><br>";

          //in each file
          //separate file's name
          $clientName = "";
          $createdTime = "";

          $i=0;
          for (; $entry[$i]!="_" ; $i++){ // file's name pattern must be 'client's name_created time'
              $clientName=$clientName.$entry[$i];
          }
          //echo "FileName ::: ".$clientName."<br>";
          $i++;//avoid "_"
          for (;$entry[$i]!="."; $i++) {
            $createdTime=$createdTime.$entry[$i];
          }
          //echo "Created time ::: ".$createdTime."<br>";

          $submitHH = ""; //convert to int when calculate time
          $submitMM = "";
          $submitSS = "";
          $time = "";
          $signal="";
          $src="";
          $recTime = "";
          $dateTime="";
          $channel = "";
          //prepare var to collect data
          //in each data
          while(!feof($file)){
            //echo fgets($file)."<br>"; //test open file
            $Line = fgets($file);//read each line
            $StrArr = str_split($Line);// split them to 1:1 array
            //handmade fanction

            if (strpos($Line,"signal-at-rate=")) {


              $posSign = strripos($Line,"signal-at-rate=")+strlen("signal-at-rate=");


              for ($i=$posSign; $StrArr[$i]!="d";$i++ ) {
                $signal = $signal.$StrArr[$i];
              }


            }
            if (strpos($Line,"src=")) {
              $posSrc = strripos($Line,"src=")+strlen("scr=")-1;
              for ($i=$posSrc+1; $StrArr[$i]!=chr(10)&&$StrArr[$i]!=" ";$i++ ) { // 10 is next char from last char in this line : check by print them all in int with ord()
                //echo "bug : ".ord($StrArr[$i])."<br>";
                $src = $src.$StrArr[$i];
              }
            }
            if (strpos($Line,"RouterOS")!=false){ //that line must contain "RouterOS"
              $month = "";
              $date = "";
              $year = "";

              $month = $StrArr[2].$StrArr[3].$StrArr[4];
              $date = $StrArr[6].$StrArr[7];
              $year = $StrArr[9].$StrArr[10].$StrArr[11].$StrArr[12];

              if ($StrArr[14]==" "&&$StrArr[15]!=" ") {
                $submitHH = $submitHH.$StrArr[15];
              }else {
                $submitHH = $submitHH.$StrArr[14].$StrArr[15];
              }
              if ($StrArr[17]==" "&&$StrArr[18]!=" ") {
                $submitMM = $submitMM.$StrArr[18];
              }else {
                $submitMM = $submitMM.$StrArr[17].$StrArr[18];
              }
              if ($StrArr[20]==" "&&$StrArr[21]!=" ") {
                $submitSS = $submitSS.$StrArr[21];
              }else {
                $submitSS = $submitSS.$StrArr[20].$StrArr[21];
              }

              //echo "month  : ".$month."<br>"; //put any echo line to DB
              //echo "date   : ".$date."<br>";
              //echo "year   : ".$year."<br>";
              //concat string to make yyyy-mm-dd
              $dateTime = $dateTime.$year."-".$month."-".$date;


              //echo "subHH  : ".$submitHH."<br>";
              //echo "subMM  : ".$submitMM."<br>";
              //echo "subSS  : ".$submitSS."<br>----------------<br><br>";

            }
            if (strpos($Line,"time=")!=false) {

              //$signaltime = "";

              $posTime = strripos($Line,"time=")+strlen("time=")-1;
              $posChan = strripos($Line,"channel=")+strlen("channel=");

              for ($i=$posTime+1; $StrArr[$i]!=" ";$i++ ) {
                $time = $time.$StrArr[$i];
              }
              for ($i=$posChan+1; $StrArr[$i]!="\"";$i++ ) {
                $channel = $channel.$StrArr[$i];
              }
              //$time = $StrArr[$posTime+1].$StrArr[$posTime+2].$StrArr[$posTime+3].$StrArr[$posTime+4].$StrArr[$posTime+5];
              //$channel = $StrArr[$posChan+1].$StrArr[$posChan+2].$StrArr[$posChan+3].$StrArr[$posChan+4].$StrArr[$posChan+5].$StrArr[$posChan+6].$StrArr[$posChan+7].$StrArr[$posChan+8].$StrArr[$posChan+9].$StrArr[$posChan+10];

              //echo "time   : ".$time."<br>"; //put any echo line to DB
              //echo "channel: ".$channel."<br>";

              //calculate time when data was captured
              //$timeFloat = floatval($time);
              if ($createdTime!=""&&$time!="") {
                //$recTime = floatval($createdTime)+floatval($time);
                //now $createdTime format : HHMMSS we want to separate them!!
                $hh = intval($createdTime[0].$createdTime[1]);
                $mm = intval($createdTime[2].$createdTime[3]);
                $ss = intval($createdTime[4].$createdTime[5]);
                //code optimise with $temp to simplify repeated method
                if ($ss+floatval($time)<60) {
                  $ss = $ss+floatval($time);
                  $recTime = $recTime.$hh.".".$mm;
                }elseif ($mm+floor(($ss+floatval($time))/60)<60) {
                  $mm=$mm+floor(($ss+floatval($time))/60);
                  $ss=($ss+floatval($time))-(60*floor(($ss+floatval($time))/60));
                  $recTime = $recTime.$hh.".".$mm;
                }elseif ($hh+(($mm+(($ss+floatval($time))/60))/60)<24) {
                  $hh=$hh+floor(($mm+(floor($ss+floatval($time))/60))/60);
                  $mm=($mm+(floor($ss+floatval($time))/60))%60;
                  $ss=($ss+floatval($time))-(60*floor(($ss+floatval($time))/60));
                  $recTime = $recTime.$hh.".".$mm;
                  //.":".$ss
                }//else //incase change the day

              }

/*
              if($submitSS!=""&&$submitMM!=""&&$submitHH!=""&&$month!=""&&$date!=""&&$year!=""){// for error prevention when data line was swap
                $ss = floatval($submitSS);
                $mm = intval($submitMM);
                $hh = intval($submitHH);
/*
                if($ss<$timeFloat){ // if sec not enough
                    if((60*$mm)+$ss<$timeFloat){ //if min not enough
                      if((3600*$hh)+(60*$mm)+$ss<$timeFloat) { //if hr not enough
                          //day -1 -> 24*3600 added , 1st janruary -> 31st december decrease year
                          //day by $month
                          if($date=="1"){
                            switch ($month) {
                              case 'jan': $month = "dec"; $date = "31"; break;
                              case 'feb': $month = "jan"; $date = "31"; break;
                              case 'mar': $month = "feb"; $date = ""break;
                              case 'apr': $month = "mar"; break;
                              case 'may': $month = "apr"; break;
                              case 'jun': $month = "may"; break;
                              case 'jul': $month = "jun"; break;
                              case 'aug': $month = "jul"; break;
                              case 'sep': $month = "aug"; break;
                              case 'oct': $month = "sep"; break;
                              case 'nov': $month = "oct"; break;
                              case 'dec': $month = "nov"; break;
                            }

                          }else {
                              $date = strval(intval($date)-1);
                          }
                          $signaltime = (86400+((3600*$hh)+(60*$mm)+$ss))-$timeFloat; // 86400 is sec in a day
                      }
                    }
                }*/
              }
              //echo "timeF  : ".$timeFloat."<br>";
            //}
            if ($clientName!=""&&$dateTime!=""&&$recTime!=""&&$channel!=""&&$signal!=""&&$src!="") {
              if(intval($signal)>-85){ //add signal strength filter
                echo "Client Name        : ".$clientName."<br>";
                echo "Recieved Date      : ".$dateTime."<br>";
                echo "Recieved Timestamp : ".$recTime."<br>";
                echo "Channel            : ".$channel."<br>";
                echo "Signal Strength    : ".intval($signal)."<br>";
                echo "Source Mac Address : ".$src."<br><br>";

                ///////////////////// Put to database ////////////////////////
                $link = mysqli_connect("localhost", "root", "", "map");
                // Check connection
                if($link === false){
                    die("ERROR: Could not connect. " . mysqli_connect_error());
                }
                $sql = "INSERT INTO info (name, src, sig, recDate, recTime) VALUES ('$clientName','$src', '$signal', '$dateTime' , '$recTime')";
    
                if(mysqli_query($link, $sql)){
                  echo "Records added successfully.";
               } else{
                   echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
               }
               // close connection
               mysqli_close($link);
              }

              $recTime="";
              $channel="";
              $signal="";
              $src="";
              $time="";
            }

            /*
            if($clientName!=""){
              echo "FileName ::: ".$clientName."<br>";
            }
            if ($dateTime!="") {
              echo "YYYY-MM-DD : ".$dateTime."<br>";
            }/*
            if ($createdTime!="") {
              echo "Created time ::: ".$createdTime."<br>";
            }
            if($recTime!=""){
              echo "time   : ".$recTime."<br>"; //put any echo line to DB

            }
            if($channel!=""){
              echo "channel: ".$channel."<br>";
            }
            if($signal!=""){
              echo "signal : ".$signal."<br>"; //put any echo line to DB
            }
            if($src!=""){
              echo "src    : ".$src."<br><br>";
            }*/


          }
          fflush($file);
          fclose($file);
          echo "<br>"."----------------------------------"."<br>";

        }
      }

  }
   ?>
</body>
</html>
