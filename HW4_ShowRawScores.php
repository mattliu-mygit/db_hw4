<head>
  <title>Show Raw Scores</title>
</head>
<body>
  <?php
    include "open.php";
    $sql = file_get_contents('HW4_SHOWRAWSCORES.sql');

    	//execute the query, then run through the result table row by row to
      //put each row's data into our array
      if ($result = mysqli_query($conn,$sql)){
        foreach($result as $row){
          array_push($dataPoints, array( "SID"=> $row["SID"], "Score"=> $row["Score"]));
        }
      }
    
      //close the connection opened by open.php since we no longer need access to dbase
      $conn->close();

  ?>
</body>