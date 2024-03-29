<?php
$pageTitle = "ExerHub";
include 'php/session.php';
if (!isset($_SESSION['user_id'])) {
  header("Location: index.html");
}
require_once 'php/header.php';
require_once 'php/db_connect.php';
require_once 'php/db_query.php';
$userId = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="apple-touch-icon" sizes="180x180" href="assets/img/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicon-16x16.png">
  <link rel="manifest" href="/assets/site.webmanifest">
  <link rel="stylesheet" href="css/style.css">
  <title>ExerHub</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</head>
<body class="dark">
<?php include 'html/nav.html'; ?>
<main>
  <?php include 'php/activity_chart.php';?>
  <div class="container">
    <h1>Welcome to ExerHub</h1>
    <p>This web application is designed to help you create and manage bodyweight workout routines. With BWE, you can track your progress, discover new exercises and progressions, and create customized workouts to meet your fitness goals.</p>
    <p>To get started, please log in or create an account.</p>
  </div>
</main>
  <script src="js/nav.js"></script>
  <?php include 'html/footer.html'; ?>
  </div>
</body>
</html>
