<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>BWE - Workout</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
  <link rel="stylesheet" href="style.css">
  <?php require_once 'php/db.php'; ?>
</head>
<body class="dark">
  <nav>
    <div class="nav-wrapper">
      <span class="brand-logo" style="margin-left: 60px"><a href="index.html">BWE/</a><span class="sub-page-name">Logs</span></span>
      <a href="index.html" data-target="side-nav" class="show-on-large sidenav-trigger"><i class="material-icons">menu</i></a>
      <ul class="right" id="top-nav"></ul>
    </div>
  </nav>
  <ul class="sidenav" id="side-nav"></ul>
  <main class="container">
  <?php
    session_start();
    $userId = $_SESSION['user_id'];
    // print the user id to the console
    echo "<script>console.log('userId: $userId')</script>";

    // Retrieve the workout logs from the database for the user along with the workout name, sort newest to oldest
    $logsQuery = "SELECT workout_logs.id, workout_logs.start_time, workout_logs.end_time, workouts.name FROM workout_logs INNER JOIN workouts ON workout_logs.workout_id = workouts.id WHERE workout_logs.user_id = $userId ORDER BY workout_logs.start_time DESC";
    $logsResult = query($logsQuery);

    // Display the table of workout logs
    echo "<table>";
    echo "<thead><tr><th>Workout Name</th><th>Date</th><th>Length</th><th></th></tr></thead>";
    echo "<tbody>";
    while ($logRow = mysqli_fetch_assoc($logsResult)) {
      $logId = $logRow['id'];
      $startTime = $logRow['start_time'];
      $endTime = $logRow['end_time'];
      $workoutName = $logRow['name'];

      // Get the day name of the start time
      $dayName = date("l", strtotime($startTime));
      $formattedStartTime = date("Y-m-d H:i:s", strtotime($startTime));

      // Calculate the duration of the workout
      $duration = strtotime($endTime) - strtotime($startTime);
      $length = gmdate("H:i:s", $duration);

      echo "<tr>";
      echo "<td><a href='workout_log.php?log_id=$logId'>$workoutName</a></td>";
      echo "<td><a href='workout_log.php?log_id=$logId'>$dayName $formattedStartTime</a></td>";
      echo "<td><a href='workout_log.php?log_id=$logId'>$length</a></td>";
      echo "<td><a href='#' class='delete-btn' data-log-id='$logId'><i class='material-icons'>delete</i></a></td>";
      echo "</tr>";
    }    
    echo "</tbody>";
    echo "</table>";
    ?>
  </main>
  <script src="js/nav.js"></script>
</body>
</html>