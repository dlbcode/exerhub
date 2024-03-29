<?php
$pageTitle = "Workouts";
include 'php/session.php';
require_once 'php/header.php';
require_once 'php/db_connect.php';
require_once 'php/db_query.php';
?>  

<?php
  require_once 'php/get_workouts.php';
  if (isset($_SESSION['user_id'])) {
      $userId = $_SESSION['user_id'];
      $workouts = fetchWorkouts($userId);
  } else {
      $workouts = fetchWorkouts(null);
  }
  // Function to display the fetched workouts
  function displayWorkouts($workouts) {
    if (count($workouts) == 0) {
      echo "<p>No workouts found.</p>";
    } else {
      echo "<ul>";
      foreach ($workouts as $workout) {
        $avg_difficulty = isset($workout['avg_difficulty']) ? $workout['avg_difficulty'] : 'N/A';
        echo "<li>
        <a href='workout.php?workout_id=" . $workout['id'] . "&workout_name=" . urlencode($workout['name']) . "' style='display: block;'>
          <div class='workout-item' style='display: flex; justify-content: space-between;'>
            <div>" . $workout['name'] . "</div>
            <div>Difficulty: " . $avg_difficulty . "</div>
          </div>
        </a>
      </li>";
      }
      echo "</ul>";
    }
  }
?>
<body class="dark">
<?php include 'html/nav.html'; ?>
  <main class="container">
  <div class="row">
    <div class="col s12" style="margin: 5px; display: flex; justify-content: space-between;">
        <a href="select_workouts.php">
            <button class="btn" id="selectWorkoutBtn" style="margin: 5px;">Select Workouts</button>
        </a>
        <a href="create_workout.php" class="right">
            <button class="btn" id="createWorkoutBtn" style="margin: 5px; display: flex; align-items: center;">
                <span style="font-size: 24px; margin-right: 5px; padding-bottom: 3px;">+</span>
                <span>Create Workout</span>
            </button>
        </a>
    </div>
</div>


  <div class="row">
    <div class="col s12">
      <h6>My Workouts:</h6>
      <div class="col s12">
        <?php
            displayWorkouts($workouts);
        ?><br>
        <a href="create_workout.php"><button class="btn" id="workoutHistoryBtn" style="margin: 5px; line-height: 1;">Workout History</button></a>
      </div>
    </div>
  </div>
</main>
<script src="js/nav.js"></script>
<?php include 'html/footer.html'; ?>
</body>
</html>
