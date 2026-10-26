<!-- CATATAN:
Blok PHP di bagian atas saya taruh supaya dijalankan di server sebelum html itu akan dikirim ke browser,
jadi koneksi database dan query harus diletakkan di sini.
connection.php menyediakan variabel $conn (mysqli) yang dipakai di seluruh halaman.
Semua data (total baris, halaman aktif, hasil query) disiapkan di atas,
lalu HTML di bawah hanya menampilkan hasilnya.
mysqli_close($conn) dipanggil setelah data selesai dibaca jangan memakai $conn lagi setelah itu.

Pas awal saya lengkapi kode tidak tau ada penambahan kolom utk file_path di tabel nilaimhws
dan juga fotonya suda ke upload di folder asstets/img..
makanya file_path kosong karna saya baru menambahkan kolom tersebut setelah foto sudah ter-upload

-->
