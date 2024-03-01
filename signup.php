<?php
// Connect to the database using mysqli_connect
$servername = "localhost";
$username = "root";
$password = "";
$database = "shopunique";
$conn = mysqli_connect($servername, $username, $password, $database);
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// Get the user input from the signup form
$first_name = $_POST["first_name"];
$last_name = $_POST["last_name"];
$email = $_POST["email"];
$pass = $_POST["pass"];
$cpass = $_POST["cpass"];

// Check if the passwords match and the email is valid
if ($pass == $cpass && filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $hash = password_hash($pass, PASSWORD_DEFAULT);
  // Insert the user data into the database table
  $sql = "INSERT INTO users (first_name, last_name, email, password) VALUES ('$first_name', '$last_name', '$email', '$hash')";
  if (mysqli_query($conn, $sql)) {
    echo "New record created successfully";
  } else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
  }
} else {
  echo "Passwords do not match or email is invalid";
}

// Close the database connection
mysqli_close($conn);
?>
