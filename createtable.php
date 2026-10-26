<?php
//connect to db server
require_once __DIR__ . "/connection.php";

// Pilih database yang digunakan
mysqli_select_db($conn, "db5e");

// sql to create table
$sql = "CREATE TABLE photo (
id_photo INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
file_name VARCHAR(100) NOT NULL,
uploaded_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if (mysqli_query($conn, $sql)) {
    echo "Table created successfully";
} else {
    echo "Error creating table: " . mysqli_error($conn);
}

mysqli_close($conn);
?>