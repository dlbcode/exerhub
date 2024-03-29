<?php
require_once 'db_connect.php';
require_once 'db_query.php';

$workoutId = $_POST['workout_id'];

// Query to get workout sequences for the selected workout
$query = "SELECT ws.*, e.name FROM workout_sequences ws LEFT JOIN exercises e ON ws.exercise_id = e.id WHERE ws.workout_id = ?";
$result = query($conn, $query, [$workoutId]);

$workoutSequences = [];
while ($row = mysqli_fetch_assoc($result)) {
  $workoutSequences[] = $row;
}

echo json_encode($workoutSequences);
?>