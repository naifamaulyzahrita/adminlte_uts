<?php
// Koneksi ke database
require_once __DIR__ . "/connection.php";

// Paging: jumlah baris per halaman hanya boleh 5, 10, atau 15
$allowedLimits = [5, 10, 15];
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
if (!in_array($limit, $allowedLimits, true)) {
  $limit = 5;
}

// Daftar predikat yang diizinkan untuk filter dropdown
$allowedPredikat = ['Sangat Memuaskan', 'Memuaskan', 'Cukup Memuaskan', 'Kurang Memuaskan', 'Tidak Lulus!'];

// Mengambil kata kunci pencarian, filter predikat, dan arah sortir dari URL
$q        = isset($_GET['q']) ? trim($_GET['q']) : '';
$predikat = isset($_GET['predikat']) ? $_GET['predikat'] : '';
if (!in_array($predikat, $allowedPredikat, true)) {
  $predikat = '';
}
$sort = (isset($_GET['sort']) && $_GET['sort'] === 'asc') ? 'asc' : 'desc';

// Menyusun kondisi WHERE secara dinamis sesuai pencarian dan filter
$where = [];
if ($q !== '') {
  // Escape karakter khusus supaya aman dari SQL injection dan wildcard LIKE
  $qLike = mysqli_real_escape_string($conn, addcslashes($q, '%_'));
  // Menyocokkan awal nama (nama depan) atau awal kata setelah spasi (nama belakang)
  $where[] = "(namalengkap LIKE '$qLike%' OR namalengkap LIKE '% $qLike%')";
}
if ($predikat !== '') {
  $where[] = "predikat = '" . mysqli_real_escape_string($conn, $predikat) . "'";
}
$whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

// Menghitung total data (setelah filter) untuk menentukan jumlah halaman
$countResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM nilaimhsw $whereSql");
$totalRows   = (int) mysqli_fetch_assoc($countResult)['total'];
$totalPages  = max(1, (int) ceil($totalRows / $limit));

// Menentukan halaman aktif, dijaga agar tidak kurang dari 1 atau lebih dari total halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
if ($page > $totalPages) $page = $totalPages;
$offset = ($page - 1) * $limit;

// Mengambil data halaman ini; nim ditambahkan agar urutan stabil saat nilai sama
// file_path ikut diambil eksplisit (aman walau kolom belum ada? lihat catatan di bawah)
$sql    = "SELECT *, file_path FROM nilaimhsw $whereSql ORDER BY nilaiakhir $sort, nim ASC LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $sql);

// Helper pembuat link: semua parameter (limit, q, predikat, sort) ikut terbawa
$url = fn($p, $extra = []) => htmlspecialchars('?' . http_build_query(array_merge(
  ['page' => $p, 'limit' => $limit, 'q' => $q, 'predikat' => $predikat, 'sort' => $sort],
  $extra
)), ENT_QUOTES);
?>

<!doctype html>
<html lang="en">
<!--begin::Head-->

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>AdminLTE 4 | Rekap Nilai Mahasiswa</title>

  <!--begin::Theme Init (prevents flash of incorrect theme on load, #6043)-->
  <script>
    (() => {
      'use strict';
      const root = document.documentElement;

      // Applications with their own theming opt out of AdminLTE's color mode
      // entirely, here as well as in the bundle.
      if (root.getAttribute('data-lte-color-mode') === 'off') {
        return;
      }

      const STORAGE_KEY = 'lte-theme';
      let stored = null;
      try {
        stored = localStorage.getItem(STORAGE_KEY);
      } catch {
        // localStorage may be unavailable (private mode, sandboxed iframe).
      }
      // Mirror the precedence in color-mode.ts: the visitor's stored choice
      // wins, then a theme this page declared itself, then the OS preference.
      const authored = root.getAttribute('data-bs-theme');
      let resolved = 'light';
      if (stored === 'dark' || stored === 'light') {
        resolved = stored;
      } else if (authored === 'dark' || authored === 'light') {
        resolved = authored;
      } else if (globalThis.matchMedia('(prefers-color-scheme: dark)').matches) {
        resolved = 'dark';
      }
      root.setAttribute('data-bs-theme', resolved);
      root.style.colorScheme = resolved;
      // Flag values computed here, so the bundle does not mistake them for a
      // theme the page declared and stop following the OS preference.
      if (resolved !== authored) {
        root.setAttribute('data-lte-theme-resolved', '');
      }
    })();
  </script>
  <!--end::Theme Init-->

  <!--begin::Accessibility Meta Tags-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
  <meta name="color-scheme" content="light dark" />
  <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
  <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
  <!--end::Accessibility Meta Tags-->

  <!--begin::Primary Meta Tags-->
  <meta name="title" content="AdminLTE 4 | Users" />
  <meta name="author" content="ColorlibHQ" />
  <meta name="description"
    content="AdminLTE is a free Bootstrap 5 admin dashboard template with almost 50 example pages, built with vanilla JS and designed with accessibility in mind." />
  <meta name="keywords"
    content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard, accessible admin panel" />
  <!--end::Primary Meta Tags-->

  <!--begin::Accessibility Features-->
  <!-- Skip links will be dynamically added by accessibility.js -->
  <meta name="supported-color-schemes" content="light dark" />
  <link rel="preload" href="./css/adminlte.css" as="style" />
  <!--end::Accessibility Features-->

  <!--begin::Fonts-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
    integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" media="print"
    onload="this.media = 'all'" />
  <!--end::Fonts-->

  <!--begin::Third Party Plugin(OverlayScrollbars)-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
    crossorigin="anonymous" />
  <!--end::Third Party Plugin(OverlayScrollbars)-->

  <!--begin::Third Party Plugin(Bootstrap Icons)-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
    crossorigin="anonymous" />
  <!--end::Third Party Plugin(Bootstrap Icons)-->

  <!--begin::Required Plugin(AdminLTE)-->
  <link rel="stylesheet" href="./css/adminlte.css" />
  <!--end::Required Plugin(AdminLTE)-->
</head>
<!--end::Head-->
<!--begin::Body-->

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
  <!--begin::App Wrapper-->
  <div class="app-wrapper">
    <!--begin::Header-->
    <!--end::Header-->
    <!--begin::Sidebar-->

    <!--end::Sidebar-->
    <!--begin::App Main-->
    <main class="app-main">
      <!--begin::App Content Header-->
      <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
          <!--begin::Row-->
          <div class="row">
            <div class="col-sm-6">
              <h1 class="mb-0 fs-3">Rekap Nilai Mahasiswa</h1>
            </div>
            <div class="col-sm-6">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Beranda</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Nilai</li>
                </ol>
              </nav>
            </div>
          </div>
          <!--end::Row-->
        </div>
        <!--end::Container-->
      </div>
      <!--end::App Content Header-->
      <!--begin::App Content-->
      <div class="app-content">
        <!--begin::Container-->
        <div class="container-fluid">
          <!--begin::Row-->
          <div class="row">
            <div class="col-12">
              <!--begin::Card-->
              <div class="card mb-4">
                <!--begin::Card Header-->
                <div class="card-header">
                  <div class="row g-2 align-items-center">
                    <div class="col-12 col-md-4">
                      <h3 class="card-title">Directory Nilai</h3>
                    </div>
                      <div class="col-12 col-md-8">

                        <!-- Form pencarian nama dan filter predikat -->
                        <form method="get" class="d-flex flex-wrap justify-content-md-end gap-2 m-0">
                          
                          <!-- Simpan limit dan sort agar tidak hilang saat mencari -->
                          <input type="hidden" name="limit" value="<?php echo $limit; ?>">
                          <input type="hidden" name="sort" value="<?php echo $sort; ?>">

                          <div class="input-group input-group-sm w-auto">
                            <button type="submit" class="input-group-text" aria-label="Cari">
                              <i class="bi bi-search" aria-hidden="true"></i>
                            </button>
                            <input type="search" name="q" id="user-search" class="form-control"
                              placeholder="Cari Nama Mahasiswa" aria-label="Search by name"
                              value="<?php echo htmlspecialchars($q, ENT_QUOTES); ?>" style="width: 220px" />
                          </div>

                          <select name="predikat" id="user-role-filter" class="form-select form-select-sm w-auto"
                            aria-label="Filter by predikat" onchange="this.form.submit()">
                            <option value="">Semua Predikat</option>
                            <?php foreach ($allowedPredikat as $p) { ?>
                              <option value="<?php echo $p; ?>" <?php echo $p === $predikat ? 'selected' : ''; ?>>
                                <?php echo rtrim($p, '!'); ?>
                              </option>
                            <?php } ?>
                          </select>

                          <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                            data-bs-target="#modal-add-user">
                            <i class="bi bi-person-plus-fill me-1" aria-hidden="true"> </i>
                            Tambah Data Nilai
                          </button>
                        </form>
                    </div>
                  </div>
                </div>
                <!--end::Card Header-->

                  <!--begin::Card Body-->
                  <div class="card-body p-0">
                <?php if (mysqli_num_rows($result) > 0) { ?>
                    <div class="table-responsive">
                      <table class="table table-hover align-middle m-0" id="tabel-nilai">
                        <thead>
                          <tr>
                            <th>NIM</th>
                            <th>Nama Lengkap</th>
                            <th>
                              <!-- Header Nilai Akhir: klik untuk toggle urutan ascending/descending -->
                              <a href="<?php echo $url(1, ['sort' => $sort === 'asc' ? 'desc' : 'asc']); ?>"
                                class="text-reset text-decoration-none d-flex justify-content-between align-items-center gap-2">
                                Nilai Akhir
                                <i class="bi <?php echo $sort === 'asc' ? 'bi-caret-up-fill' : 'bi-caret-down-fill'; ?>" aria-hidden="true"></i>
                              </a>
                            </th>
                            <th>Nilai Huruf</th>
                            <th>Predikat</th>
                            <th>Tanggal Dibuat</th>
                            <th>Tanggal Diubah</th>
                            <th class="text-end">Aksi</th>
                          </tr>
                        </thead>
                        <tbody>
                        <?php
                        while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                            <tr data-nim="<?php echo $row['nim']; ?>">
                              <td>
                                <span class="fw-medium"><?php echo $row['nim']; ?></span>
                              </td>
                              <td>
                                <!-- Foto + Nama Lengkap dalam satu kolom -->
                                <div class="d-flex align-items-center gap-2">
                                  <?php
                                    // Prioritas: file_path dari DB; fallback ke glob() lama agar data lama tetap tampil
                                    $fotoSrc = '';
                                    if (!empty($row['file_path']) && is_file(__DIR__ . '/' . ltrim($row['file_path'], './'))) {
                                      $fotoSrc = './' . ltrim($row['file_path'], './');
                                    } else {
                                      $foto = glob(__DIR__ . "/assets/img/" . $row['nim'] . ".*");
                                      if ($foto) $fotoSrc = './assets/img/' . basename($foto[0]);
                                    }
                                  ?>
                                  <?php if ($fotoSrc) { ?>
                                    <img src="<?php echo htmlspecialchars($fotoSrc, ENT_QUOTES); ?>"
                                      alt="" class="rounded-circle" style="width:32px;height:32px;object-fit:cover;" />
                                  <?php } else { ?>
                                    <span class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center"
                                      style="width:32px;height:32px;">
                                      <i class="bi bi-person-fill text-white" aria-hidden="true"></i>
                                    </span>
                                  <?php } ?>
                                  <span class="fw-medium nama-lengkap"><?php echo htmlspecialchars($row['namalengkap']); ?></span>
                                </div>
                              </td>
                              <td>
                                <span class="fw-medium"><?php echo $row['nilaiakhir']; ?></span>
                              </td>
                              <td>
                                <span class="fw-medium"><?php echo $row['nilaihuruf']; ?></span>
                              </td>
                              <td>
                                <span class="fw-medium predikat-text"><?php echo $row['predikat']; ?></span>
                              </td>
                              <td>
                                <span class="fw-medium"><?php echo $row['created_date']; ?></span>
                              </td>
                              <td>
                                <span class="fw-medium"><?php echo $row['modified_date']; ?></span>
                              </td>
                              <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                  <!-- Tombol edit: data baris dikirim lewat atribut data ke modal -->
                                  <button type="button" class="btn btn-outline-secondary btn-edit" data-bs-toggle="modal"
                                    data-bs-target="#modal-edit-user"
                                    data-nim="<?php echo $row['nim']; ?>"
                                    data-nama="<?php echo htmlspecialchars($row['namalengkap'], ENT_QUOTES); ?>"
                                    data-nilai="<?php echo $row['nilaiakhir']; ?>"
                                    data-file_path="<?php echo htmlspecialchars($row['file_path'] ?? '', ENT_QUOTES); ?>"
                                    aria-label="Edit">
                                    <i class="bi bi-pencil" aria-hidden="true"> </i>
                                  </button>
                                  <!-- Tombol hapus: NIM dikirim lewat atribut data-nim ke modal -->
                                  <button type="button" class="btn btn-outline-danger btn-delete" data-bs-toggle="modal"
                                    data-bs-target="#modal-delete-user" data-nim="<?php echo $row['nim']; ?>" aria-label="Delete">
                                    <i class="bi bi-trash" aria-hidden="true"> </i>
                                  </button>
                                </div>
                              </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                      </table>
                    </div>
                    <!-- /.table-responsive -->
                  <?php
                } else {
                  echo '<p class="text-center text-body-secondary py-4 m-0">0 Data Mahasiswa - Rekap Nilai tidak ditemukan!</p>';
                }
                mysqli_close($conn);
                  ?>

                  </div>
                  <!--end::Card Body-->

                  <!--begin::Card Footer-->
                  <?php
                  // Info jumlah data yang sedang ditampilkan
                  $from = $totalRows > 0 ? $offset + 1 : 0;
                  $to   = min($offset + $limit, $totalRows);

                  // Menampilkan maksimal 5 tombol angka di sekitar halaman aktif
                  $endPage   = min($totalPages, max(1, $page - 2) + 4);
                  $startPage = max(1, $endPage - 4);

                  ?>
                  <div class="card-footer">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                      <div class="fs-7 text-body-secondary">
                        Nomor urut <?php echo $from; ?> batas data ke <?php echo $to; ?> dari <?php echo $totalRows; ?> mahasiswa
                      </div>

                      <div class="d-flex flex-wrap align-items-center gap-2">
                        <!-- Pilihan jumlah baris per halaman (5, 10, 15) -->
                        <form method="get" class="d-flex align-items-center gap-2 m-0">
                          <input type="hidden" name="q" value="<?php echo htmlspecialchars($q, ENT_QUOTES); ?>">
                          <input type="hidden" name="predikat" value="<?php echo htmlspecialchars($predikat, ENT_QUOTES); ?>">
                          <input type="hidden" name="sort" value="<?php echo $sort; ?>">
                          <label for="page-size" class="fw-semibold fs-7 mb-0">Batas Data</label>
                          <select id="page-size" name="limit" class="form-select form-select-sm w-auto"
                            onchange="this.form.submit()">
                            <?php foreach ($allowedLimits as $opt) { ?>
                              <option value="<?php echo $opt; ?>" <?php echo $opt === $limit ? 'selected' : ''; ?>>
                                <?php echo $opt; ?>
                              </option>
                            <?php } ?>
                          </select>
                        </form>

                        <!-- Navigasi halaman: pertama, sebelumnya, nomor, selanjutnya, terakhir -->
                        <ul class="pagination pagination-sm m-0">
                          <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                            <a class="page-link" href="<?php echo $url(1); ?>">Pertama</a>
                          </li>
                          <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                            <a class="page-link" href="<?php echo $url($page - 1); ?>">Sebelumnya</a>
                          </li>

                          <?php for ($i = $startPage; $i <= $endPage; $i++) { ?>
                            <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                              <a class="page-link" href="<?php echo $url($i); ?>"><?php echo $i; ?></a>
                            </li>
                          <?php } ?>

                          <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                            <a class="page-link" href="<?php echo $url($page + 1); ?>">Selanjutnya</a>
                          </li>
                          <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                            <a class="page-link" href="<?php echo $url($totalPages); ?>">Terakhir</a>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                  <!--end::Card Footer-->
                  
              </div>
              <!--end::Card-->
            </div>
            <!-- /.col -->
          </div>
          <!--end::Row-->

          <!--begin::Add User Modal-->
          <div class="modal fade" id="modal-add-user" tabindex="-1" aria-labelledby="modal-add-user-label"
            aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <form action="insertnilai.php" method="post" enctype="multipart/form-data" id="form-tambah-nilai">
                  <div class="modal-header">
                    <h5 class="modal-title" id="modal-add-user-label">Tambah Data Nilai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <div class="mb-3">
                      <label for="add-nim" class="form-label"> NIM </label>
                      <input type="text" class="form-control" id="add-nim" minlength="10" maxlength="10"
                        pattern="[0-9]{10}" placeholder="cth. 320xx16xxx" name="nim" required />
                    </div>
                    <div class="mb-3">
                      <label for="add-nama" class="form-label"> Nama Lengkap </label>
                      <input type="text" class="form-control" id="add-nama"
                        placeholder="cth. Naifa Maulyzahrita" name="namalengkap" required />
                    </div>
                    <div class="mb-3">
                      <label for="add-nilai" class="form-label"> Nilai Akhir </label>
                      <input type="number" class="form-control" id="add-nilai" placeholder="xxx.xx"
                        min="0" max="100" step="0.01" name="nilaiakhir" required />
                    </div>
                    <div class="mb-3">
                      <label for="add-foto" class="form-label"> Foto </label>
                      <input type="file" class="form-control" id="add-foto" name="photo"
                        accept=".jpg,.jpeg,.png,.gif" required />
                      <div class="form-text">Format JPG, PNG, atau GIF. Maksimal 1 MB.</div>
                      <img id="add-foto-preview" src="" alt="" class="img-size-32 rounded-circle mt-2 d-none"
                        style="width:64px;height:64px;object-fit:cover;" />
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btn-simpan-tambah" disabled>Simpan</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <!--end::Add User Modal-->

          <!--begin::Delete User Modal-->
          <div class="modal fade" id="modal-delete-user" tabindex="-1" aria-labelledby="modal-delete-user-label"
            aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="modal-delete-user-label">Hapus Data Nilai</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form-delete-nilai">
                  <div class="modal-body">
                    <p class="mb-0">
                      Anda yakin akan menghapus data ini? Aksi ini tidak dapat dikembalikan!
                    </p>
                    <input type="hidden" name="nim" id="delete-nilai-nim">
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                      Batal
                    </button>
                    <button type="submit" class="btn btn-danger">
                      Hapus
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <!--end::Delete User Modal-->

          <!--begin::Edit User Modal-->
          <div class="modal fade" id="modal-edit-user" tabindex="-1" aria-labelledby="modal-edit-user-label"
            aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <form action="updatenilai.php" method="post" enctype="multipart/form-data">
                  <div class="modal-header">
                    <h5 class="modal-title" id="modal-edit-user-label">Ubah Data Nilai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <div class="mb-3">
                      <label for="edit-nim" class="form-label"> NIM </label>
                      <input type="text" class="form-control" id="edit-nim" name="nim" readonly />
                      <div class="form-text">NIM tidak dapat diubah.</div>
                    </div>
                    <div class="mb-3">
                      <label for="edit-nama" class="form-label"> Nama Lengkap </label>
                      <input type="text" class="form-control" id="edit-nama" name="namalengkap" required />
                    </div>
                    <div class="mb-3">
                      <label for="edit-nilai" class="form-label"> Nilai Akhir </label>
                      <input type="number" class="form-control" id="edit-nilai"
                        min=0 max=100 step=0.01 name="nilaiakhir" required />
                    </div>
                    <div class="mb-3">
                      <label for="edit-foto" class="form-label"> Foto </label>
                      <input type="file" class="form-control" id="edit-foto" name="photo"
                        accept=".jpg,.jpeg,.png,.gif" />
                      <div class="form-text">Biarkan kosong jika tidak ingin mengganti foto. Maks 1 MB.</div>
                      <input type="hidden" name="file_path" id="edit-file_path" />
                      <img id="edit-foto-preview" src="" alt=""
                        class="rounded-circle mt-2 d-none" style="width:64px;height:64px;object-fit:cover;" />
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <!--end::Edit User Modal-->
        </div>
        <!--end::Container-->
      </div>
      <!--end::App Content-->
    </main>
    <!--end::App Main-->
    <!--begin::Footer-->
    <footer class="app-footer">
      <!--begin::To the end-->
      <div class="float-end d-none d-sm-inline">Web Programming Semester 5</div>
      <!--end::To the end-->
      <!--begin::Copyright-->
      <strong>
        Copyright &copy; 2014-2026&nbsp;
        <a href="https://adminlte.io" class="text-decoration-none">AdminLTE.io</a>.
      </strong>
      All rights reserved.
      <!--end::Copyright-->
    </footer>
    <!--end::Footer-->
  </div>
  <!--end::App Wrapper-->
  <!--begin::Script-->
  <!--begin::Third Party Plugin(OverlayScrollbars)-->
  <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"
    crossorigin="anonymous"></script>
  <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    crossorigin="anonymous"></script>
  <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
  <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
  <script src="./js/adminlte.js"></script>
  
  <!--end::Required Plugin(AdminLTE)-->
  <!--begin::OverlayScrollbars Configure-->
  <script>
  document.addEventListener('DOMContentLoaded', function () {
    
    // Saat tombol hapus diklik, isi NIM ke input hidden di modal hapus
    const deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(button => {
      button.addEventListener('click', function () {
        const nim = this.getAttribute('data-nim');
        document.getElementById('delete-nilai-nim').value = nim; 
      });
    });

    // Saat tombol edit diklik, isi form modal edit dengan data baris tersebut
    const editButtons = document.querySelectorAll('.btn-edit');
    editButtons.forEach(button => {
      button.addEventListener('click', function () {
        document.getElementById('edit-nim').value = this.getAttribute('data-nim');
        document.getElementById('edit-nama').value = this.getAttribute('data-nama');
        document.getElementById('edit-nilai').value = this.getAttribute('data-nilai');

        // Isi preview foto lama di modal edit (jika ada)
        const fp = this.getAttribute('data-file_path') || '';
        const preview = document.getElementById('edit-foto-preview');
        const hidden  = document.getElementById('edit-file_path');
        if (hidden) hidden.value = fp;
        if (preview) {
          if (fp) {
            preview.src = './' + fp.replace(/^\.?\//, '');
            preview.classList.remove('d-none');
          } else {
            preview.src = '';
            preview.classList.add('d-none');
          }
        }
      });
    });

    // Elemen form tambah data dan preview foto
    const formTambah = document.getElementById('form-tambah-nilai');
    const btnSimpanTambah = document.getElementById('btn-simpan-tambah');
    const fotoInput = document.getElementById('add-foto');
    const fotoPreview = document.getElementById('add-foto-preview');

    // Tombol Simpan hanya aktif jika semua input form sudah valid
    function cekFormTambah() {
      btnSimpanTambah.disabled = !formTambah.checkValidity();
    }
    formTambah.addEventListener('input', cekFormTambah);
    formTambah.addEventListener('change', cekFormTambah);

    // Tampilkan preview foto yang dipilih sebelum disimpan
    fotoInput.addEventListener('change', function () {
      const file = this.files[0];
      if (file) {
        fotoPreview.src = URL.createObjectURL(file);
        fotoPreview.classList.remove('d-none');
      } else {
        fotoPreview.classList.add('d-none');
      }
    });

    // Reset form saat modal ditutup
    document.getElementById('modal-add-user').addEventListener('hidden.bs.modal', function () {
      formTambah.reset();
      fotoPreview.classList.add('d-none');
      btnSimpanTambah.disabled = true;
    });

    // Preview saat pilih file baru di form edit
    const editFotoInput   = document.getElementById('edit-foto');
    const editFotoPreview = document.getElementById('edit-foto-preview');
    if (editFotoInput) {
      editFotoInput.addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
          editFotoPreview.src = URL.createObjectURL(file);
          editFotoPreview.classList.remove('d-none');
        }
      });
    }

    // Blok script AJAX hapus data tanpa pindah halaman
    const formDeleteNilai = document.getElementById('form-delete-nilai');
    if (formDeleteNilai) {
      formDeleteNilai.addEventListener('submit', function (e) {
        e.preventDefault();

        // Kirim NIM ke hapusnilai.php lewat fetch
        const formData = new FormData(formDeleteNilai);
        fetch('hapusnilai.php', {
        method: 'POST',
        body: formData
      })
      .then(response => {
        // Jika server mengembalikan error, lempar ke catch
        if (!response.ok) throw new Error('Gagal menghapus data');
        return response.text();
      })
      .then(data => {
        // Memuat ulang halaman agar tabel, total data, dan paging ter-update
        location.reload();
      })
      .catch(error => {
        // Tampilkan pesan jika penghapusan gagal
        console.error('Error:', error);
        alert('Data gagal dihapus.');
      });
      });
    }
  });
</script>
  <!--end::OverlayScrollbars Configure-->

  <!--begin::Color Mode Toggle-->
  <!-- The light/dark/auto switcher ships in adminlte.js as the ColorMode
     module (since 4.1) — no page script needed. Only the no-flash snippet
     in <head> stays inline, because it must run before first paint. -->
  <!--end::Color Mode Toggle-->

  <!--end::Script-->
</body>
<!--end::Body-->

</html>