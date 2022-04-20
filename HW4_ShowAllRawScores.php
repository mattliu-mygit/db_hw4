<head>
  <title>Show All Raw Scores</title>
</head>
<body>
  <?php
    include "open.php";
	  $password = $_POST['password'];
    $sqlPassCheck = "Call HW4_CHECKPASSWORD('". $password."');";
	  echo "<h2>All Raw Scores for ".$password."</h2><br>";
    try {
      // Password check.
      $passCheck = $conn->query($sqlPassCheck);
      if ($passCheck->num_rows == 0) {
        echo "ERROR: Password ".$password." not found";
        return;
      }
      try {
        // Connection opened and closed because ran into issues with multiple query ommands not executing well.
        $conn->close();
        include "open.php"; 
        $sqlAssignmentCheck = "Call HW4_GETASSIGNMENTS();";
        $assignments = $conn->query($sqlAssignmentCheck);
        $assignmentArray = mysqli_fetch_all($assignments);

        $sql = "Call HW4_SHOWALLRAWSCORES();";
        echo "<table border=\"1px solid black\">";
        $header = "<tr><th> SID </th> <th> lname </th> <th> fname </th> <th> section </th>";

        // Update headers with all assignment names
        foreach($assignments as $assignment) {
          $header=$header."<th>".$assignment["AName"]."</th>";
        }
        echo $header."</tr>";

        $conn->close();
        include "open.php";

        $content = "";
        try {
          if ($result = $conn->query($sql)) {
            $check = true;
            $indexMod = 0; // keeps track of index positioning after encountering null values
            foreach($result as $index => $row){
              // At the beginning of each row, start a new table row with student information
              if ($check) {
                $content=$content."<tr><td>".$row["SID"]. "</td><td>".$row["LName"]."</td><td>".$row["FName"]."</td><td>".$row["Sec"];
                $check = false;
              }
              $indexTot = ($index+$indexMod) % $assignments->num_rows ;

              // Skip over untaken assessments while making sure we make sure to check for the end of a row
              while ($row["AName"] != $assignmentArray[$indexTot][0]) {
                $content=$content."</td><td>";
                // If we are at the end of a row, start a new row
                if (($indexTot+1)% $assignments->num_rows == 0) {
                  $content=$content."</td></tr>";
                  $check = true;
                }
                $indexMod = $indexMod + 1;
                $indexTot =  ($index+$indexMod) % $assignments->num_rows;
              }

              // records score for each assignment
              $score = $row["Score"];
              $content=$content."</td><td>".$row["Score"];
              // If we are at the end of a row, start a new row
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