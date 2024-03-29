<?php 
$pageTitle = "ExerHub - Login";
include 'php/session.php';
require_once 'php/db_connect.php';
require_once 'php/db_query.php';
// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Retrieve the form data
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Validate the form data (you can add more validation if needed)
  if (empty($email) || empty($password)) {
    // Handle the case when required fields are missing
    echo "Please enter both email and password.";
    exit;
  }

  // Perform the database operation to authenticate the user
  // Assuming you have a "users" table with columns: id, name, email, password
  $query = "SELECT * FROM users WHERE email = '$email'";

  // Execute the query and check if the user exists
  $result = query($conn, $query);
  if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    // Verify the password
    if (password_verify($password, $user['password'])) {
      // Authentication successful, set session variables
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['user_name'] = $user['name'];
      $_SESSION['is_admin'] = $user['is_admin'];

      // Set a session variable for user login status
      $_SESSION['logged_in'] = true;

      // Redirect the user to the home page
      header("Location: /index.html");
      exit;
    }
  }
  // Handle the case when authentication fails
  echo "Invalid email or password.";
}
include 'php/header.php'; 
?>
<script>
    var sessionVars = {
      username: '<?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : ''; ?>',
      isAdmin: <?php echo isset($_SESSION['is_admin']) && $_SESSION['is_admin'] ? 'true' : 'false'; ?>
    };
</script>
<body class="dark">
<?php include 'html/nav.html'; ?>
<main>
  <div class="container">
    <h4>Welcome Back</h4>
    <p></p>
    <h5>Log In</h5>
    <form action="login.php" method="POST">
      <div>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
      </div>
      <div>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
      </div>
      <button type="submit">Log In</button>
    </form>
  </div>
</main>
<script src="js/nav.js"></script>
<?php include 'html/footer.html'; ?>
<script>document.getElementById("email").focus();</script>
</body>
</html>
