<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>BWE - Workout Logs</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
  <link rel="stylesheet" href="style.css">
  <?php require_once 'php/db.php'; ?>
</head>
<body class="dark">
  <main class="container">
    <?php
    // Get the passed workout ID and user ID from the URI
    $workoutId = $_GET['workout_id'];
    $userId = $_GET['user_id'];

    // Retrieve the workout name from the database
    $workoutQuery = "SELECT name FROM workouts WHERE id = $workoutId";
    $workoutResult = query($workoutQuery);
    $workoutRow = mysqli_fetch_assoc($workoutResult);
    $workoutName = $workoutRow['name'];

    // Display the workout name
    echo "<h4>Workout: $workoutName</h4>";

    // Retrieve the workout logs from the database
    $logsQuery = "SELECT id, start_time, end_time FROM workout_logs WHERE workout_id = $workoutId AND user_id = $userId";
    $logsResult = query($logsQuery);

    // Display the table of workout logs
    echo "<table>";
    echo "<thead><tr><th>Date</th><th>Length</th><th></th></tr></thead>";
    echo "<tbody>";
    while ($logRow = mysqli_fetch_assoc($logsResult)) {
      $logId = $logRow['id'];
      $startTime = $logRow['start_time'];
      $endTime = $logRow['end_time'];

      // Get the day name of the start time
      $dayName = date("l", strtotime($startTime));
      $formattedStartTime = date("Y-m-d H:i:s", strtotime($startTime));

      // Calculate the duration of the workout
      $duration = strtotime($endTime) - strtotime($startTime);
      $length = gmdate("H:i:s", $duration);

      echo "<tr>";
      echo "<td><a href='workout_log.php?log_id=$logId'>$dayName, $formattedStartTime</a></td>";
      echo "<td>$length</td>";
      echo "<td><a href='#' class='delete-btn' data-log-id='$logId'><i class='material-icons'>delete</i></a></td>";
      echo "</tr>";
    }    
    echo "</tbody>";
    echo "</table>";
    ?>
    <a href="workout.php?workout_id=<?php echo $workoutId ?>&user_id=<?php echo $userId ?>" id="closeBtn" class="close-btn">
      <i class="material-icons">close</i></a>
</main>
</body>
</html>