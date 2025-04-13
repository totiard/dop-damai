<?php
// Koneksi ke database
$host = 'localhost'; // atau '127.0.0.1'
$user = 'root'; // Ganti dengan username MySQL Anda
$password = ''; // Ganti dengan password MySQL Anda
$dbname = 'dop_damai'; // Nama database

// Membuat koneksi
$conn = new mysqli($host, $user, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mengambil data pengirim
$queryPengirim = "SELECT id, nama, no_telp FROM pengirim";
$resultPengirim = $conn->query($queryPengirim);

// Query untuk mengambil data penerima
$queryPenerima = "SELECT id, nama, no_telp, alamat, kode_pos FROM penerima";
$resultPenerima = $conn->query($queryPenerima);

// Handle form submission untuk menambah pengirim
if (isset($_POST['submitPengirim'])) {
    $namaPengirim = $_POST['namaPengirim'];
    $noTelpPengirim = $_POST['noTelpPengirim'];
    $queryInsertPengirim = "INSERT INTO pengirim (nama, no_telp) VALUES ('$namaPengirim', '$noTelpPengirim')";
    $conn->query($queryInsertPengirim);
    header("Location: ".$_SERVER['PHP_SELF']); // Refresh halaman setelah data ditambahkan
}

// Handle form submission untuk menambah penerima
if (isset($_POST['submitPenerima'])) {
    $namaPenerima = $_POST['namaPenerima'];
    $noTelpPenerima = $_POST['noTelpPenerima'];
    $alamatPenerima = $_POST['alamatPenerima'];
    $kodePosPenerima = $_POST['kodePosPenerima'];
    $queryInsertPenerima = "INSERT INTO penerima (nama, no_telp, alamat, kode_pos) 
                            VALUES ('$namaPenerima', '$noTelpPenerima', '$alamatPenerima', '$kodePosPenerima')";
    $conn->query($queryInsertPenerima);
    // Set tab aktif ke penerima setelah form submit
    echo '<script>localStorage.setItem("activeTab", "penerima");</script>';
    header("Location: ".$_SERVER['PHP_SELF']); // Refresh halaman setelah data ditambahkan
}

// Handle edit pengirim
if (isset($_POST['editPengirim'])) {
    $idPengirim = $_POST['idPengirim'];
    $namaPengirim = $_POST['namaPengirim'];
    $noTelpPengirim = $_POST['noTelpPengirim'];
    $queryUpdatePengirim = "UPDATE pengirim SET nama='$namaPengirim', no_telp='$noTelpPengirim' WHERE id=$idPengirim";
    $conn->query($queryUpdatePengirim);
    header("Location: ".$_SERVER['PHP_SELF']); // Refresh halaman setelah data diperbarui
}

// Handle edit penerima
if (isset($_POST['editPenerima'])) {
    $idPenerima = $_POST['idPenerima'];
    $namaPenerima = $_POST['namaPenerima'];
    $noTelpPenerima = $_POST['noTelpPenerima'];
    $alamatPenerima = $_POST['alamatPenerima'];
    $kodePosPenerima = $_POST['kodePosPenerima'];
    $queryUpdatePenerima = "UPDATE penerima SET nama='$namaPenerima', no_telp='$noTelpPenerima', alamat='$alamatPenerima', kode_pos='$kodePosPenerima' WHERE id=$idPenerima";
    $conn->query($queryUpdatePenerima);
    // Set tab aktif ke penerima setelah form submit
    echo '<script>localStorage.setItem("activeTab", "penerima");</script>';
    header("Location: ".$_SERVER['PHP_SELF']); // Refresh halaman setelah data diperbarui
}

// Handle delete pengirim
if (isset($_GET['deletePengirim'])) {
    $idPengirim = $_GET['deletePengirim'];
    $queryDeletePengirim = "DELETE FROM pengirim WHERE id=$idPengirim";
    $conn->query($queryDeletePengirim);
    header("Location: ".$_SERVER['PHP_SELF']); // Refresh halaman setelah data dihapus
}

// Handle delete penerima
if (isset($_GET['deletePenerima'])) {
    $idPenerima = $_GET['deletePenerima'];
    $queryDeletePenerima = "DELETE FROM penerima WHERE id=$idPenerima";
    $conn->query($queryDeletePenerima);
    // Set tab aktif ke penerima setelah form submit
    echo '<script>localStorage.setItem("activeTab", "penerima");</script>';
    header("Location: ".$_SERVER['PHP_SELF']); // Refresh halaman setelah data dihapus
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pengirim dan Penerima</title>
    <!-- Link to Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* Custom CSS styles */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
            color: #333;
        }

        h2 {
            font-family: 'Lora', serif;
            font-weight: 700;
            color: #2c3e50;
        }

        .tabs {
            display: flex;
            justify-content: center;
            margin-top: 30px;
            margin-bottom: 20px;
            cursor: pointer;
        }

        .tab {
            padding: 12px 20px;
            margin: 0 10px;
            background-color: #ffffff;
            border: 2px solid #ccc;
            border-radius: 5px 5px 0 0;
            font-size: 16px;
            font-weight: 600;
            transition: background-color 0.3s ease, transform 0.2s;
        }

        .tab:hover {
            background-color: #f39c12;
            color: white;
            transform: scale(1.05);
        }

        .active-tab {
            background-color: #3498db;
            color: white;
        }

        .tab-content {
            /* width: 90vw;
            margin-left: calc(-45vw + 50%); */
            width: 95vw;
            margin-left: calc(-47.5vw + 50%);
            display: none;
            padding: 20px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .active-content {
            display: block;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #f0f0f0;
        }

        th {
            background-color: #f1f1f1;
        }

        .btn-primary, .btn-danger {
            border-radius: 5px;
            font-weight: 600;
            transition: transform 0.3s ease, background-color 0.3s ease;
        }

        .btn-primary {
            background-color: #f8c271;
            border-color: #f8c271;
        }

        .btn-primary:hover {
            background-color: #f39c12;
            border-color: #f39c12;
            transform: scale(1.05);
        }

        .btn-danger {
            background-color: #e74c3c;
            border-color: #e74c3c;
        }

        .btn-danger:hover {
            background-color: #c0392b;
            border-color: #c0392b;
            transform: scale(1.05);
        }

        .modal-content {
            border-radius: 10px;
            padding: 20px;
        }

        .modal-header {
            border-bottom: 1px solid #ddd;
        }

        .modal-footer {
            border-top: 1px solid #ddd;
        }

        .modal-title {
            font-family: 'Lora', serif;
            font-weight: 700;
            color: #2c3e50;
        }
        
        footer {
            width: 95vw;
            margin-left: calc(-47.5vw + 50%);
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4">Data Pengirim dan Penerima</h2>
    
    <!-- Tab navigation -->
    <div class="tabs">
        <div class="tab" id="tabPengirim" onclick="showTab('pengirim')">Pengirim</div>
        <div class="tab" id="tabPenerima" onclick="showTab('penerima')">Penerima</div>
    </div>

    <!-- Tab Content Pengirim -->
    <div id="pengirim" class="tab-content">
        <h3>Daftar Pengirim</h3>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalPengirim">
            <i class="fas fa-plus-circle"></i> Tambah Pengirim
        </button>
        <table>
            <tr>
                <th>Nama</th>
                <th>No Telp</th>
                <th>Action</th>
            </tr>
            <?php
            // Menampilkan data pengirim
            if ($resultPengirim->num_rows > 0) {
                while($row = $resultPengirim->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $row['nama'] . "</td>
                            <td>" . $row['no_telp'] . "</td>
                            <td>
                                <button class='btn btn-warning' data-bs-toggle='modal' data-bs-target='#modalEditPengirim' onclick='editPengirim(" . $row['id'] . ", \"" . $row['nama'] . "\", \"" . $row['no_telp'] . "\")'>
                                    <i class='fas fa-edit'></i> Edit
                                </button>
                                <a href='?deletePengirim=" . $row['id'] . "' class='btn btn-danger' onclick='return confirm(\"Apakah Anda yakin ingin menghapus?\")'>
                                    <i class='fas fa-trash'></i> Hapus
                                </a>
                            </td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='3'>Tidak ada data pengirim</td></tr>";
            }
            ?>
        </table>
    </div>

    <!-- Tab Content Penerima -->
    <div id="penerima" class="tab-content">
        <h3>Daftar Penerima</h3>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalPenerima">
            <i class="fas fa-plus-circle"></i> Tambah Penerima
        </button>
        <table>
            <tr>
                <th>Nama</th>
                <th>No Telp</th>
                <th>Alamat</th>
                <th>Kode Pos</th>
                <th>Action</th>
            </tr>
            <?php
            // Menampilkan data penerima
            if ($resultPenerima->num_rows > 0) {
                while($row = $resultPenerima->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $row['nama'] . "</td>
                            <td>" . $row['no_telp'] . "</td>
                            <td>" . $row['alamat'] . "</td>
                            <td>" . $row['kode_pos'] . "</td>
                            <td>
                                <button class='btn btn-warning' data-bs-toggle='modal' data-bs-target='#modalEditPenerima' onclick='editPenerima(" . $row['id'] . ", \"" . $row['nama'] . "\", \"" . $row['no_telp'] . "\", \"" . $row['alamat'] . "\", \"" . $row['kode_pos'] . "\")'>
                                    <i class='fas fa-edit'></i> Edit
                                </button>
                                <a href='?deletePenerima=" . $row['id'] . "' class='btn btn-danger' onclick='return confirm(\"Apakah Anda yakin ingin menghapus?\")'>
                                    <i class='fas fa-trash'></i> Hapus
                                </a>
                            </td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Tidak ada data penerima</td></tr>";
            }
            ?>
        </table>
    </div>
</div>

<!-- Modal Pengirim -->
<div class="modal fade" id="modalPengirim" tabindex="-1" aria-labelledby="modalPengirimLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPengirimLabel">Tambah Pengirim</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="namaPengirim" class="form-label">Nama:</label>
                        <input type="text" class="form-control" id="namaPengirim" name="namaPengirim" required>
                    </div>
                    <div class="mb-3">
                        <label for="noTelpPengirim" class="form-label">No Telp:</label>
                        <input type="text" class="form-control" id="noTelpPengirim" name="noTelpPengirim" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="submitPengirim" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Pengirim -->
<div class="modal fade" id="modalEditPengirim" tabindex="-1" aria-labelledby="modalEditPengirimLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditPengirimLabel">Edit Pengirim</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post">
                <input type="hidden" id="idPengirim" name="idPengirim">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editNamaPengirim" class="form-label">Nama:</label>
                        <input type="text" class="form-control" id="editNamaPengirim" name="namaPengirim" required>
                    </div>
                    <div class="mb-3">
                        <label for="editNoTelpPengirim" class="form-label">No Telp:</label>
                        <input type="text" class="form-control" id="editNoTelpPengirim" name="noTelpPengirim" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="editPengirim" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Penerima -->
<div class="modal fade" id="modalPenerima" tabindex="-1" aria-labelledby="modalPenerimaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPenerimaLabel">Tambah Penerima</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="namaPenerima" class="form-label">Nama:</label>
                        <input type="text" class="form-control" id="namaPenerima" name="namaPenerima" required>
                    </div>
                    <div class="mb-3">
                        <label for="noTelpPenerima" class="form-label">No Telp:</label>
                        <input type="text" class="form-control" id="noTelpPenerima" name="noTelpPenerima" required>
                    </div>
                    <div class="mb-3">
                        <label for="alamatPenerima" class="form-label">Alamat:</label>
                        <input type="text" class="form-control" id="alamatPenerima" name="alamatPenerima" required>
                    </div>
                    <div class="mb-3">
                        <label for="kodePosPenerima" class="form-label">Kode Pos:</label>
                        <input type="text" class="form-control" id="kodePosPenerima" name="kodePosPenerima" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="submitPenerima" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Penerima -->
<div class="modal fade" id="modalEditPenerima" tabindex="-1" aria-labelledby="modalEditPenerimaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditPenerimaLabel">Edit Penerima</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post">
                <input type="hidden" id="idPenerima" name="idPenerima">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editNamaPenerima" class="form-label">Nama:</label>
                        <input type="text" class="form-control" id="editNamaPenerima" name="namaPenerima" required>
                    </div>
                    <div class="mb-3">
                        <label for="editNoTelpPenerima" class="form-label">No Telp:</label>
                        <input type="text" class="form-control" id="editNoTelpPenerima" name="noTelpPenerima" required>
                    </div>
                    <div class="mb-3">
                        <label for="editAlamatPenerima" class="form-label">Alamat:</label>
                        <input type="text" class="form-control" id="editAlamatPenerima" name="alamatPenerima" required>
                    </div>
                    <div class="mb-3">
                        <label for="editKodePosPenerima" class="form-label">Kode Pos:</label>
                        <input type="text" class="form-control" id="editKodePosPenerima" name="kodePosPenerima" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="editPenerima" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script Bootstrap 5 dan JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Menyimpan tab yang aktif menggunakan localStorage
    window.onload = function() {
        var activeTab = localStorage.getItem("activeTab") || "pengirim"; // Default tab adalah pengirim
        showTab(activeTab);
    }

    function showTab(tabName) {
        const tabs = document.querySelectorAll('.tab');
        const contents = document.querySelectorAll('.tab-content');

        // Remove active class from all tabs and contents
        tabs.forEach(tab => tab.classList.remove('active-tab'));
        contents.forEach(content => content.classList.remove('active-content'));

        // Add active class to clicked tab and corresponding content
        document.querySelector(`#tab${capitalizeFirstLetter(tabName)}`).classList.add('active-tab');
        document.getElementById(tabName).classList.add('active-content');

        // Simpan tab yang aktif di localStorage
        localStorage.setItem("activeTab", tabName);
    }

    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    function editPengirim(id, nama, noTelp) {
        document.getElementById('idPengirim').value = id;
        document.getElementById('editNamaPengirim').value = nama;
        document.getElementById('editNoTelpPengirim').value = noTelp;
    }

    function editPenerima(id, nama, noTelp, alamat, kodePos) {
        document.getElementById('idPenerima').value = id;
        document.getElementById('editNamaPenerima').value = nama;
        document.getElementById('editNoTelpPenerima').value = noTelp;
        document.getElementById('editAlamatPenerima').value = alamat;
        document.getElementById('editKodePosPenerima').value = kodePos;
    }
</script>

<footer class="text-center mt-5 mb-4" style="background-color: #222; color: #bbb; padding: 15px;">
    <p style="margin: 0;">
        &copy; 2025 <strong><span style="color: #f39c12;">DOP DAMAI</span></strong> | by 
        <a href="https://totiard.github.io/Profile-New" target="_blank" style="color: #3498db; text-decoration: none;" onmouseover="this.style.color='#f39c12'" onmouseout="this.style.color='#3498db'"> <i class="fab fa-github"></i> Toti Ardiansyah</a>
    </p>
</footer>

</body>
</html>

<?php
// Menutup koneksi
$conn->close();
?>
