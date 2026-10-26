<?php

// koneksi ke db
require_once __DIR__ . "/connection.php";

// Data dikirim lewat POST dari AJAX (FormData)
if (isset($_POST['nim'])) {
    $nim = mysqli_real_escape_string($conn, $_POST['nim']);

    $sql = "DELETE FROM nilaimhsw WHERE nim = '$nim'";

    if (mysqli_query($conn, $sql)) {
        echo "success";
    } else {
        http_response_code(500);
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else {
    http_response_code(400);
    echo "NIM tidak ditemukan";
}