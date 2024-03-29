<?php
session_start(); // Start the session if not already started
// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include the database connection file
require_once 'db_connect.php';
require_once 'db_query.php';

// Get the raw request body
$requestBody = file_get_contents('php://input');

// Parse the JSON data
$requestData = json_decode($requestBody, true);

// Get the workout name and user ID from the request data
$workoutName = $requestData['workoutName'];
$workoutData = $requestData['workoutData'];
$userId = $_SESSION['user_id'];

// Create the workout record in the 'workouts' table
$query = "INSERT INTO workouts (name, user_id, is_public) VALUES (?, ?, '0')";
$params = array($workoutName, $userId);
$result = query($conn, $query, $params);

// Check if the query was successful
if ($result['success']) {
    $workoutId = $result['insert_id']; // Get the auto-generated workout ID

    // Process the workout data
    foreach ($workoutData as $type) {
        $typeValue = $type['type'];
        $exerciseValue = $type['exercise'];
        $secondsValue = $type['seconds'];
        $warmupValue = $type['warmup'];

        // Get the exercise ID based on the exercise name
        $exerciseQuery = "SELECT id FROM exercises WHERE name = ?";
        $exerciseResult = query($conn, $exerciseQuery, array($exerciseValue));

        if ($typeValue != 'Rest') {
            $stmt = $conn->prepare("SELECT id FROM exercises WHERE name = ?");
            $stmt->bind_param("s", $exerciseValue);
            $stmt->execute();
            $result = $stmt->get_result();
            $exerciseRow = $result->fetch_assoc();

            if ($exerciseRow) {
                $exerciseId = $exerciseRow['id'];
                $stmt = $conn->prepare("INSERT INTO workout_sequences(workout_id, type, exercise_id, seconds, warmup) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("isiii", $workoutId, $typeValue, $exerciseId, $secondsValue, $warmupValue);
                $stmt->execute();
            } else {
                die("Error: Exercise not found for name '$exerciseValue'");
            }
        } else {
            $query = "INSERT INTO workout_sequences(workout_id, type, seconds) VALUES (?, ?, ?)";
            $params = array($workoutId, $typeValue, $secondsValue);
            $result = query($conn, $query, $params);

            if (!$result['success']) {
                die("Error creating workout type: " . $result['error']);
            }
        }
    }
    echo json_encode(['success' => true]);
} else {
    die("Error creating workout: " . $result['error']);
}
?>
