<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Upload</title>
</head>
<body>
    <form action="upload.php" method="post" enctype="multipart/form-data">
    <label for="photo">Unggah Foto:</label><br/>
    <input type="file" name="photo"><br/><br/>
    <input type="submit" value="Unggah" name="submit">
    </form>
</body>
</html>