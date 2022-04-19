<head>
  <title>Show Raw Scores</title>
</head>
<body>
  <?php
    include "open.php";
    // $sql = file_get_contents('HW4_SHOWRAWSCORES.sql');
    $sql = "Select *
  FROM HW4_Student
    JOIN HW4_RawScore ON HW4_Student.SID = HW4_RawScore.SID;";

    	//execute the query, then run through the result table row by row to
      //put each row's data into our array
      if ($result = mysqli_query($conn,$sql)){
        foreach($result as $row){
          echo $dataPoints, array( "SID"=> $row["SID"], "Score"=> $row["Score"]);
        }
      }
    
      //close the connection opened by open.php since we no longer need access to dbase
      $conn->close();

  ?>
</body>