<?php
//connect to db server
require_once __DIR__ . "/connection.php";

// Pilih database yang digunakan
mysqli_select_db($conn, "db5e");

// sql to create table
$sql = "CREATE TABLE nilaimhsw (
nim CHAR(10) PRIMARY KEY,
namalengkap VARCHAR(50) NOT NULL,
nilaiakhir DECIMAL(5,2) NOT NULL,
nilaihuruf CHAR(1),
predikat VARCHAR(20),
created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
modified_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
file_path VARCHAR(255) NOT NULL,
)";

if (mysqli_query($conn, $sql)) {
    echo "<script>alert('Table created successfully');</script>";
} else {
    echo "Error creating table: " . mysqli_error($conn);
}

mysqli_close($conn);
?>