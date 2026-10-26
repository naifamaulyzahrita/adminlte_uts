<?php

//connect to db server
require_once __DIR__ . "/connection.php";

// Pilih database yang digunakan
mysqli_select_db($conn, "db5e");

//baca
$nim = $_POST["nim"];
$nama_mhsw = $_POST["namalengkap"];
$nilai_akhir = $_POST["nilaiakhir"];

// Validasi Foto 
if (!preg_match('/^[0-9]{10}$/', $nim)) {
    echo "<script>alert('NIM harus 10 digit angka.');history.back();</script>";
    exit;
}

$fotoOk = false;
$ext = '';
if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    if (
        getimagesize($_FILES['photo']['tmp_name']) !== false &&
        in_array($ext, $allowed) &&
        $_FILES['photo']['size'] <= 1000000
    ) {
        $fotoOk = true;
    }
}
if (!$fotoOk) {
    echo "<script>alert('Foto tidak valid. Gunakan JPG/PNG/GIF, maksimal 1 MB.');history.back();</script>";
    exit;
}

// konveksi nilai angka ke huruf dan predikat
  if ((0.00 <= $nilai_akhir) AND ($nilai_akhir <= 34.50)) {
      $nilai_huruf = 'E';
      $predikat = "Tidak Lulus!";
  } else if((34.51 <= $nilai_akhir) AND ($nilai_akhir <= 50.50)) {
      $nilai_huruf = 'D';
      $predikat = "Kurang Memuaskan";
  } else if((50.51 <= $nilai_akhir) AND ($nilai_akhir<= 65.50)) {
      $nilai_huruf = 'C';
      $predikat = "Cukup Memuaskan";
  } else if((65.51 <= $nilai_akhir) AND ($nilai_akhir <= 80.50)) {
      $nilai_huruf = 'B';
      $predikat = "Memuaskan";
  } else if((80.51 <= $nilai_akhir) AND ($nilai_akhir <= 100.00)) {
      $nilai_huruf = 'A';
      $predikat = "Sangat Memuaskan";
  } else {
      $nilai_huruf = "Invalid";
      $predikat = "Invalid";
  }

$q = "INSERT INTO nilaimhsw (nim, namalengkap, nilaiakhir, nilaihuruf, predikat)
      VALUES ('$nim', '$nama_mhsw', '$nilai_akhir', '$nilai_huruf', '$predikat')";
        
      // cek query berhasil atau tidak
      if (mysqli_query($conn, $q)) {

        // Simpan foto ke assets/img dengan nama dan nim
        $dir = __DIR__ . "/assets/img/";
        foreach (glob($dir . $nim . ".*") as $lama) {
            unlink($lama); // hapus foto lama dengan NIM sama (jika ada)
        }
        move_uploaded_file($_FILES['photo']['tmp_name'], $dir . $nim . "." . $ext);

        // berhasil -- pesan sukses
        echo "<script>alert('New record created successfully');window.location.href='viewdata.php';</script>";
      } else {
        echo "Error: " . $q . "<br>" . mysqli_error($conn);
        echo "<br><a href='viewdata.php'>Kembali</a>";
      }

// tutup koneksi
mysqli_close($conn);

// kembali ke form input
// header('Location: viewdata.php');
?>