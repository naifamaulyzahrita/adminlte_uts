<?php

// koneksi ke db
require_once __DIR__ . "/connection.php";

// membuat tombol untuk Tambah Data
echo "<a href='form_upload.php'><button>Tambah Foto</button></a>";

// menyiapkan statement query
$sql = "SELECT * FROM photo";

// menjalankan SQL query
$result = mysqli_query($conn, $sql);

// Process the result set
if (mysqli_num_rows($result) > 0) {
    // Output data of each row
    echo "<table border=1 cellpadding=5>";
    echo "<caption><strong>Data Foto</strong></caption>";
    echo "<tr>";
    echo "<th>No</th>";
    echo "<th>Nama File</th>";
    echo "<th>Tgl Unggah</th>";
    echo "<th>Pratinjau</th>";
    echo "</tr>";
    while($row = mysqli_fetch_assoc($result)) {
        // tampilan dalam format table
        echo "<tr>";
        echo "<td>". $row['id_photo']."</td>";
        echo "<td>". $row['file_name']."</td>";
        echo "<td>". $row['uploaded_date']."</td>";
        echo "<td>
                <img src=" . $row['file_name'] . "
                     width='150px' height='auto'>
              </td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
  echo "0 results - Data not found!";
}

// tutup konrksi ke db
mysqli_close($conn);

?>