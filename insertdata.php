<?php
//connect to db server
require_once __DIR__ . "/connection.php";

// Pilih database yang digunakan
mysqli_select_db($conn, "db5e");

//baca
$fname = $_POST["fname"];
$lname = $_POST["lname"];
$email = $_POST["email"];

$q = "INSERT INTO MyGuests (firstname, lastname, email)
VALUES ('$fname', '$lname', '$email')";

if (mysqli_query($conn, $q)) {
  echo "<script>alert('New record created successfully');</script>";
} else {
  echo "Error: " . $q . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);
?>