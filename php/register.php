<?php 
  require_once 'php/db_connect.php';
  require_once 'php/db_query.php';

  // Check if the form was submitted
  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve the form data
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Validate the form data (you can add more validation if needed)
    if (empty($name) || empty($email) || empty($password)) {
      // Handle the case when required fields are missing
      echo "Please fill out all the required fields.";
      exit;
    }

    // Hash the password for security (you can use a more secure hashing method)
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Perform the database operation to create the user account
    $query = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
    post($conn, $query, [$name, $email, $hashedPassword]);


    // Execute the query
    if (query($conn, $query)) {
      // Account creation successful
      echo "Account created successfully!";
      header("Location: ../login.php");
      exit;
    } else {
      // Handle the case when the query fails
      echo "Account creation failed: " . mysqli_error($conn);
    }
  }
?>
