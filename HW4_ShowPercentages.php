<head>
  <title>Show Percentages</title>
</head>
<body>
  <?php
    include "open.php";
	  $IDNum = $_POST['SID'];
    $sql = "Call HW4_SHOWPERCENTAGES('". $IDNum."');";
	  echo "<h2>Percentages for ".$IDNum."</h2><br>";
	  echo "<table border=\"1px solid black\">";

    // Within html table, tr is table row, th is table header,
    // and td is table data
    echo "<tr><th> SID </th> <th> lname </th> <th> fname </th> <th> section </th><th> aname </th> <th> percentage </th><th> course_avg </th></tr>";
    	//execute the query, then run through the result table row by row to
      //put each row's data into our array
      try {
        if ($result = $conn->query($sql)) {
          foreach($result as $row){
            echo "<tr><td>".$row["SID"];
            echo "</td><td>".$row["LName"];
            echo "</td><td>".$row["FName"];
            echo "</td><td>".$row["Sec"];
            echo "</td><td>".$row["AName"];
            echo "</td><td>".$row["pctg"]."%";
            echo "</td><td>".number_format($row["Course_Average"],2)."%";
            echo "</td></tr>";
          }
        }
      } catch(Exception $e) {
        echo "ERROR: SID ".$IDNum." not found";
      }
    
      //close the connection opened by open.php since we no longer need access to dbase
      $conn->close();

  ?>
</body>