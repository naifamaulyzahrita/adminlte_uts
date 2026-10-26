<?php
//connect to db server
// koneksi TANPA nama database (db5e belum ada saat file ini dijalankan)
$conn = mysqli_connect("localhost", "root", "");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

//Create database
$sql = "CREATE DATABASE IF NOT EXISTS db5e";
if (mysqli_query($conn, $sql)) {
    echo "Database created successfully";
} else {
    echo "Error creating database: " . mysqli_error($conn);
}

//Close connection
mysqli_close($conn);
?>