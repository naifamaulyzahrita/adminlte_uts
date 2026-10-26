<!DOCTYPE HTML>  
<html>
<head>
<style>
    .error {color: #FF0000;}
</style>
</head>
<body>  

<?php
    // inisialisasi -- definisikan variabel dan beri nilai kosong
    $errNIM = $errNama = $errNilai = "";
    $nim = $nama_mhsw = $nilai_akhir = "";
    $sts_nim = $sts_nama = $sts_nilai = "invalid";
    $nilai_huruf = $predikat ="";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // trigger saat tombol simpan di klik
    // perimaan data dari home
    
    //cek data nama berisi atau kosong
    if (!empty($_POST["nim"])) {
        // nama tidak kosong, lakukan sanitasi data
        // panggil fungsi sanitasi()
        $nim = sanitasi($_POST["nim"]);

        // cek format datanya benar atau salah 
        if (preg_match("/^[0-9'.]*$/", $nim)) {
            // format nama benar
            // set status nama = valid
            $sts_nim = "valid";
        } else {
            // jika format tidak sesuai -- set pesan error
            $errNIM = "NIM hanya boleh berisi Angka!";
            // set status nama = invalid
            $sts_nim = "invalid";
        }
    } else {
        // nama kosong
        $errNIM = "NIM wajib diisi!";
    }

    //cek data nama berisi atau kosong
    if (!empty($_POST["nama_mhsw"])) {
        // nama tidak kosong, lakukan sanitasi data
        // panggil fungsi sanitasi()
        $nama_mhsw = sanitasi($_POST["nama_mhsw"]);

        // cek format datanya benar atau salah 
        if (preg_match("/^[a-zA-Z-' .]*$/", $nama_mhsw)) {
            // format nama benar
            // set status nama = valid
            $sts_nama = "valid";
        } else {
            // jika format tidak sesuai -- set pesan error
            $errNama = "Nama hanya boleh berisi huruf dan spasi!";
            // set status nama = invalid
            $sts_nama = "invalid";
        }
    } else {
        // nama kosong
        $errNama = "Nama Mahasiswa wajib diisi!";
    }

    //cek data nilai berisi atau kosong
    if (!empty($_POST["nilai_akhir"])) {
        // nilai tidak kosong, lakukan sanitasi data
        // panggil fungsi sanitasi()
        $nilai_akhir = sanitasi($_POST["nilai_akhir"]);

        // cek format datanya benar atau salah 
        if (preg_match("/^[0-9'.]*$/", $nilai_akhir)) {
            // format nama benar
            // set status nilai = valid
            $sts_nilai = "valid";
            // konversi nilai angka ke nilai huruf
            // panggil fungsi konveksi_nilai()
            $nilai_huruf = konversi_nilai($nilai_akhir);
            $predikat = konversi_predikat($nilai_akhir);
        } else {
            // jika format tidak sesuai -- set pesan error
            $errNilai = "Nilai hanya boleh berisi angka dan titik!";
            // set status nilai = invalid
            $sts_nilai = "invalid";
        }
    } else {
        // nama kosong
        $errNilai= "Nilai Akhir wajib diisi!";
    }
}
//fungsi sanitasi
    function sanitasi(string $data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
    }

//fungsi konversi nilai
    function konversi_nilai($nilai) {
        if ((0.00 <= $nilai) AND ($nilai <= 34.50)){
            $nilai_huruf = 'E';
            // $predikat = "Tidak Lulus!";
        } else if((34.51 <= $nilai) AND ($nilai <= 50.50)){
            $nilai_huruf = 'D';
            // $predikat = "Kurang Memuaskan";
        } else if((50.51 <= $nilai) AND ($nilai<= 65.50)){
            $nilai_huruf = 'C';
            // $predikat = "Cukup Memuaskan";
        } else if((65.51 <= $nilai) AND ($nilai <= 80.50)){
            $nilai_huruf = 'B';
            // $predikat = "Memuaskan";
        } else if((80.51 <= $nilai) AND ($nilai <= 100.00)){
            $nilai_huruf = 'A';
            // $predikat = "Sangat Memuaskan";
        } else {
            echo "<h3>Ulangi input nilai akhir dengan benar!<h3><br>";
            $nilai_huruf = "Invalid";
            // $predikat = "Invalid";
        }
        return $nilai_huruf;
    }

//fungsi konversi predikat
    function konversi_predikat($nilai) {
        if ((0.00 <= $nilai) AND ($nilai <= 34.50)){
            return "Tidak Lulus!";
        } else if((34.51 <= $nilai) AND ($nilai <= 50.50)){
            return "Kurang Memuaskan";
        } else if((50.51 <= $nilai) AND ($nilai<= 65.50)){
            return "Cukup Memuaskan";
        } else if((65.51 <= $nilai) AND ($nilai <= 80.50)){
            return "Memuaskan";
        } else if((80.51 <= $nilai) AND ($nilai <= 100.00)){
            return "Sangat Memuaskan";
        } else {
            return "Invalid - <span class='error'>Ulangi input nilai akhir</span>";
        }
    }
?>

<h2>Form Nilai Mahasiswa</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    Nomor Induk Mahasiswa: <span class="error"></span><br>
    <input type="text" name="nim">
    <span class="error">* <?php echo $errNIM;?></span>
    <br>
    Nama Mahasiswa: <span class="error"></span><br>
    <input type="text" name="nama_mhsw">
    <span class="error">* <?php echo $errNama;?></span>
    <br>
    Nilai Akhir: <span class="error"></span><br>
    <input type="number" min=0 max=100 step="0.01" name="nilai_akhir">
    <span class="error">* <?php echo $errNilai;?></span>
    <p><span class="error">* required field</span></p>
    <input type="submit" name="submit" value="Simpan">  
</form>

<?php
    if (($sts_nim == "valid") and ($sts_nama == "valid") and ($sts_nilai == "valid")) {
        // query insert data ke tabel nilaimhsw
        // connect to db server
        require_once __DIR__ . "/connection.php";

        // Pilih database yang digunakan
        mysqli_select_db($conn, "db5e");

        // query statement
        $q = "INSERT INTO nilaimhsw (nim, namalengkap, nilaiakhir, nilaihuruf, predikat)
              VALUES ('$nim', '$nama_mhsw', '$nilai_akhir', '$nilai_huruf', '$predikat')";
        
        // cek query berhasil atau tidak
        if (mysqli_query($conn, $q)) {
            // berhasil -- pesan sukses
            echo "<script>alert('New record created successfully');</script>";
        } else {
            echo "Error: " . $q . "<br>" . mysqli_error($conn);
        }

        // tutup koneksi HANYA jika koneksi berhasil dibuat
        mysqli_close($conn);
    }
?>

</body>
</html>
