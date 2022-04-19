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

<html>
<head>
<script>
window.onload = function () { 
	var chart = new CanvasJS.Chart("chartContainer", {
		animationEnabled: true,
		exportEnabled: true,
		theme: "light1", // "light1", "light2", "dark1", "dark2"
		title:{
			text: "PHP Line Chart from Database - MySQLi"
		},
		data: [{
			type: "line", //change type to column, bar, line, area, pie, etc  
			dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
		}]
	});
	chart.render(); 
}
</script>
</head>
<body>
	<div id="chartContainer" style="height: 400px; width: 100%;"></div>
	<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>
</html>