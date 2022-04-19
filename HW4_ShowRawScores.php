<head>
  <title>Show Raw Scores</title>
</head>
<body>
  <?php
    include "open.php";
	  $IDNum = $_POST['SID'];
    $sql = "Call HW4_SHOWRAWSCORES('". $IDNum."');";
	  echo "<h2>Raw Scores for ".$IDNum."</h2><br>";
	  echo "<table border=\"1px solid black\">";

    // Within html table, tr is table row, th is table header,
    // and td is table data
    $header = "<tr><th> SID </th> <th> lname </th> <th> fname </th> <th> section </th>";
    $content = "";
    	//execute the query, then run through the result table row by row to
      //put each row's data into our array
      try {
        if ($result = $conn->query($sql)) {
          $check = true;
          foreach($result as $row){
            if ($check) {
              $content=$content."<tr><td>".$row["SID"]. "</td><td>".$row["LName"]."</td><td>".$row["FName"]."</td><td>".$row["Sec"];
              $check = false;
            }
            $score = $row["Score"];
            $assessment=$row["AName"];
            $header=$header."<th>".$assessment."</th>";
            if ($score ==  null || $score == 0) {
              $content=$content."</td><td>";
            } else {
              $content=$content."</td><td>".$row["Score"];
            }
          }
          echo $header."</tr>";
          echo $content."</td></tr>";
        }
      } catch(Exception $e) {
        echo "ERROR: SID ".$IDNum." not found";
      }
    
      //close the connection opened by open.php since we no longer need access to dbase
      $conn->close();

  ?>
</body>