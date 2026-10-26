<?php
// $namafile = $_FILES['photo']['name'];
// $tipefile = $_FILES['photo']['type'];
// $ukuranfile = $_FILES['photo']['size'];
// $temporaryfile = $_FILES['photo']['tmp_name'];
// $errfile = $_FILES['photo']['error'];

// echo "Nama File: ".$namafile."<br>";
// echo "Tipe File: ".$tipefile."<br>";
// echo "Ukuran File: ".$ukuranfile."<br>";
// echo "Alamat Temporary: ".$temporaryfile."<br>";
// echo "Status Error: ".$errfile."<br>";

// Menentukan direktori tujuan/target
$target_dir = "img/";

// Menyimpan alamat lengkap (path name) dari file yg diunggah
$target_file = $target_dir . basename($_FILES["photo"]["name"]);

// Inisialisasi status
$uploadOk = 1;

// Mengkonversi extension fiel menjadi huruf kecil
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // Checkpoint 1 (Autentifikasi)
    if (isset($_POST["submit"])) {
        // Memeriksa apakah file benar 'actual image' atau 'fake image'
        $check = getimagesize($_FILES["photo"]["tmp_name"]);
        if($check !== false) {
            echo "File is an image - " . $check["mime"] . ".<br>";
            $uploadOk = 1;
        } else {
            echo "File is not an image.<br>";
            $uploadOk = 0;
        }
    }

    // Checkpoint 2 
    // Memeriksa apakah nama file yang diunggah sudah ada di direktori tujuan/target
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Checkpoint 3
    // Memeriksa ukuruan file yang di unggah tidak melebihi batas yg ditentukan
    if ($_FILES["photo"]["size"] > 1000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Checkpoint 4
    // Mengizinkan format file tertentu (jpg, jpeg, png, gif)
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Memeriksa apakah $uploadOk sudah true (1), jika 0 berarti ada error
    echo "Status Upload: ".$uploadOk."<br>";

    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        //jika status sudah oke, lanjutkan unggah file
    } elseif (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
        // pindahkan fisik file dari temporary direktori ke target direktori
        echo "The file " . htmlspecialchars(basename($_FILES["photo"]["name"])) . "has been uploaded.";

        // aksi paralel, catat ke tabel di database
        // buat koneksi ke db server
        require_once __DIR__ . "/connection.php";

        // cek query berhasil atau tidak
        $q = "INSERT INTO photo (file_name) VALUES ('$target_file')";
                
            // cek query berhasil atau tidak
            if (mysqli_query($conn, $q)) {
                // berhasil -- pesan sukses
                echo "<script>alert('New record created successfully');</script>";
            } else {
                echo "Error: " . $q . "<br>" . mysqli_error($conn);
            }

        // tutup koneksi
        // mysqli_close($conn);
        header('Location: readphoto.php');
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
?>