<?php

require_once __DIR__ . "/connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nim         = mysqli_real_escape_string($conn, $_POST['nim']);
    $namalengkap = mysqli_real_escape_string($conn, $_POST['namalengkap']);
    $nilaiakhir  = (float) $_POST['nilaiakhir'];

    //UPDATE NILAI
    if ($nilaiakhir >= 85)      { $nilaihuruf = 'A';  $predikat = 'Sangat Memuaskan'; }
    elseif ($nilaiakhir >= 70)  { $nilaihuruf = 'B';  $predikat = 'Memuaskan'; }
    elseif ($nilaiakhir >= 60)  { $nilaihuruf = 'C';  $predikat = 'Cukup Memuaskan'; }
    elseif ($nilaiakhir >= 50)  { $nilaihuruf = 'D';  $predikat = 'Kurang Memuaskan'; }
    else                        { $nilaihuruf = 'E';  $predikat = 'Tidak Lulus'; }

    $sql = "UPDATE nilaimhsw SET
                namalengkap   = '$namalengkap',
                nilaiakhir    = $nilaiakhir,
                nilaihuruf    = '$nilaihuruf',
                predikat      = '$predikat',
                modified_date = NOW()
            WHERE nim = '$nim'";

    if (!mysqli_query($conn, $sql)) {
        die("Error updating record: " . mysqli_error($conn));
    }
}

mysqli_close($conn);
header('Location: viewdata.php');
exit;