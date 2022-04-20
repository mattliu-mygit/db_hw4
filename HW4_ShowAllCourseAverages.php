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
        $totalExam = 0;
        $totalQuiz  = 0;

        // Update the total possible exam and quiz scores
        foreach($assignments as $assignment) {
          if ($assignment["AType"] == "EXAM") {
            $totalExam = $totalExam + $assignment["PtsPoss"];
          } else {
            $totalQuiz = $totalQuiz + $assignment["PtsPoss"];
          }
        }

        $conn->close();
        include "open.php";

        $sql = "Call HW4_SHOWALLRAWSCORES();";
        echo "<table border=\"1px solid black\">";
        echo "<tr><th> SID </th> <th> lname </th> <th> fname </th> <th> section </th> <th> courseAvg </th></tr>";


        $content = "";
        try {
          if ($result = $conn->query($sql)) {
            // Initial state setup
            $check = true;
            $indexMod = 0; // keeps track of index positioning after encountering null values
            $examEarned = 0;
            $quizEarned = 0;
            foreach($result as $index => $row){
              // Every new row, start a new table row with student information
              if ($check) {
                $content=$content."<tr><td>".$row["SID"]. "</td><td>".$row["LName"]."</td><td>".$row["FName"]."</td><td>".$row["Sec"];
                $check = false;
              }
              // Handle Assessments
              $indexTot = ($index+$indexMod) % $assignments->num_rows ;
              while ($row["AName"] != $assignmentArray[$indexTot][0]) {
                $type = $assignmentArray[$indexTot][1];
                // Update course average and make a new row
                if (($indexTot+1)% $assignments->num_rows == 0) {
                  // Set weight-calculated course average
                  $total = 0.4 * ($quizEarned/$totalQuiz) + 0.6 * ($examEarned/$totalExam);
                  $content=$content."</td><td>".number_format($total*100,2)."%</td></tr>";
                  // Cleanup
                  $quizEarned = 0;
                  $examEarned = 0;
                  $check = true;
                }
                // Skip one for assessments not taken.
                $indexMod = $indexMod + 1;
                $indexTot =  ($index+$indexMod) % $assignments->num_rows;
              }

              // Update the total earned points for the current assignment
              $score = $row["Score"];
              if ($row["AType"] == "EXAM") {
                $examEarned = $examEarned + $score;
              } else {
                $quizEarned = $quizEarned + $score;
              }

              // Update course average and make a new row
              if (($indexTot+1)% $assignments->num_rows == 0) {
                // Set weight-calculated course average
                $total = 0.4 * ($quizEarned/$totalQuiz) + 0.6 * ($examEarned/$totalExam);
                $content=$content."</td><td>".number_format($total*100,2)."%</td></tr>";
                // Cleanup
                $quizEarned = 0;
                $examEarned = 0;
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