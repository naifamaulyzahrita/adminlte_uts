<?php

// koneksi ke db
require_once __DIR__ . "/connection.php";

// membuat tombol untuk Tambah Data
echo "<a href='formnilai.php'><button>Tambah Data</button></a>";

// menyiapkan statement query
$sql = "SELECT * FROM nilaimhsw";

// menjalankan SQL query
$result = mysqli_query($conn, $sql);

// Process the result set
if (mysqli_num_rows($result) > 0) {
    // Output data of each row
    echo "<table border=1 cellpadding=5>";
    echo "<caption><strong>Data Nilai Mahasiswa</strong></caption>";
    echo "<tr>";
    echo "<th>NIM</th>";
    echo "<th>Nama Lengkap</th>";
    echo "<th>Nilai Akhir</th>";
    echo "<th>Nilai Huruf</th>";
    echo "<th>Predikat</th>";
    echo "<th>Tgl Dibuat</th>";
    echo "<th>Tgl Diubah</th>";
    echo "<th>Aksi</th>";
    echo "</tr>";
    while($row = mysqli_fetch_assoc($result)) {
        // tampilan dalam format table
        echo "<tr>";
        echo "<td>". $row['nim']."</td>";
        echo "<td>". $row['namalengkap']."</td>";
        echo "<td>". $row['nilaiakhir']."</td>";
        echo "<td>". $row['nilaihuruf']."</td>";
        echo "<td>". $row['predikat']."</td>";
        echo "<td>". $row['created_date']."</td>";
        echo "<td>". $row['modified_date']."</td>";
        echo "<td><a href='formnilai.php?nim=" . $row['nim'] . "'>
                  <button>Edit Data</button>
                  </a>
                  <a href='hapusnilai.php?nim=" . $row['nim'] . "' 
                  onclick='return confirm(\"Apakah Anda yakin ingin menghapus nilai ini?\");'>
                  <button>Hapus</button>
                  </a>
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