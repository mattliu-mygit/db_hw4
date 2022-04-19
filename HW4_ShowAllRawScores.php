<head>
  <title>Show All Raw Scores</title>
</head>
<body>
  <?php
    include "open.php";
	  $password = $_POST['password'];
    $sqlPassCheck = "Call HW4_CHECKPASSWORD('". $password."');";
	  echo "<h2>Raw Scores for ".$password."</h2><br>";
    try {
      $passCheck = $conn->query($sqlPassCheck);
      if ($passCheck->num_rows == 0) {
        echo "ERROR: Password ".$password." not found";
        return;
      }
      try {
        $conn->close();
        include "open.php"; // TODO: Handle this more elegantly
        $sqlAssignmentCheck = "Call HW4_GETASSIGNMENTS();";
        $assignments = $conn->query($sqlAssignmentCheck);
        $assignmentArray = mysqli_fetch_all($assignments);

        $sql = "Call HW4_SHOWALLRAWSCORES();";
        echo "<table border=\"1px solid black\">";

        // Within html table, tr is table row, th is table header,
        // and td is table data
        $header = "<tr><th> SID </th> <th> lname </th> <th> fname </th> <th> section </th>";

        foreach($assignments as $assignment) {
          $header=$header."<th>".$assignment["AName"]."</th>";
        }
        echo $header."</tr>";

        $conn->close();
        include "open.php"; // TODO: Handle this more elegantly

        $content = "";      //execute the query, then run through the result table row by row to
        //put each row's data into our array
        try {
          if ($result = $conn->query($sql)) {
            $check = true;
            $indexMod = 0;
            foreach($result as $index => $row){
              if ($check) {
                $content=$content."<tr><td>".$row["SID"]. "</td><td>".$row["LName"]."</td><td>".$row["FName"]."</td><td>".$row["Sec"];
                $check = false;
              }
              $indexTot = ($index+$indexMod) % $assignments->num_rows ;
              while ($row["AName"] != $assignmentArray[$indexTot][0]) {
                $content=$content."</td><td>";
                if (($indexTot+1)% $assignments->num_rows == 0) {
                  $content=$content."</td></tr>";
                  $check = true;
                }
                $indexMod = $indexMod + 1;
                $indexTot =  ($index+$indexMod) % $assignments->num_rows;
              }
              $score = $row["Score"];
              if ($score ==  null) {
                $content=$content."</td><td>";
              } else {
                $content=$content."</td><td>".$row["Score"];
              }
              if (($indexTot+1)% $assignments->num_rows == 0) {
                $content=$content."</td></tr>";
                $check = true;
              }

            }
            echo $content."</td></tr>";
          }
        } catch(Exception $e) {
          echo "ERROR: no students found";
        }
      
      } catch(Exception $ex) {
        echo "ERROR: Error fetching assignments! ".$ex->getMessage();
      }
    } catch(Exception $e) {
      echo "ERROR: password ".$password." invalid";
    }
    
      //close the connection opened by open.php since we no longer need access to dbase
      $conn->close();

  ?>
</body>