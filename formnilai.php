<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Nilai</title>
</head>
<body>
    <h3>Form Nilai</h3>
    <form action="insertnilai.php" method="post">
        <label for="nim">NIM:</label><br>
        <input type="text" name="nim" required><br>
        <label for="name">Full Name:</label><br>
        <input type="text" name="namalengkap" required><br>
        <label for="nilaiakhir">Nilai Akhir:</label><br>
        <input type="number" min=0 max=100 step=0.01 name="nilaiakhir" required><br><br>
        <input type="submit" value="SAVE">
    </form>
</body>
</html>
        
