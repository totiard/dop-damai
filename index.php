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
    header("Location: ".$_SERVER['PHP_SELF']); // Refresh halaman setelah data dihapus
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pengirim dan Penerima</title>
    <!-- Link to Bootstrap CSS for Modal Styling -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Styling Tab */
        .tabs {
            display: flex;
            cursor: pointer;
            margin: 10px;
        }
        .tab {
            padding: 10px 20px;
            margin-right: 5px;
            background-color: #ddd;
            border: 1px solid #ccc;
            border-radius: 5px 5px 0 0;
        }
        .tab:hover {
            background-color: #ccc;
        }
        .tab-content {
            display: none;
            margin-top: 20px;
        }
        .active-tab {
            background-color: #4CAF50;
            color: white;
        }
        .active-content {
            display: block;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

    <h2>Data Pengirim dan Penerima</h2>
    
    <!-- Tab navigation -->
    <div class="tabs">
        <div class="tab active-tab" onclick="showTab('pengirim')">Pengirim</div>
        <div class="tab" onclick="showTab('penerima')">Penerima</div>
    </div>
    
    <!-- Tab Content Pengirim -->
    <div id="pengirim" class="tab-content active-content">
        <h3>Daftar Pengirim</h3>
        <button class="btn btn-primary" data-toggle="modal" data-target="#modalPengirim">Tambah Pengirim</button>
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
                                <button class='btn btn-warning' data-toggle='modal' data-target='#modalEditPengirim' onclick='editPengirim(" . $row['id'] . ", \"" . $row['nama'] . "\", \"" . $row['no_telp'] . "\")'>Edit</button>
                                <a href='?deletePengirim=" . $row['id'] . "' class='btn btn-danger' onclick='return confirm(\"Apakah Anda yakin ingin menghapus?\")'>Hapus</a>
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
        <button class="btn btn-primary" data-toggle="modal" data-target="#modalPenerima">Tambah Penerima</button>
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
                                <button class='btn btn-warning' data-toggle='modal' data-target='#modalEditPenerima' onclick='editPenerima(" . $row['id'] . ", \"" . $row['nama'] . "\", \"" . $row['no_telp'] . "\", \"" . $row['alamat'] . "\", \"" . $row['kode_pos'] . "\")'>Edit</button>
                                <a href='?deletePenerima=" . $row['id'] . "' class='btn btn-danger' onclick='return confirm(\"Apakah Anda yakin ingin menghapus?\")'>Hapus</a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Tidak ada data penerima</td></tr>";
            }
            ?>
        </table>
    </div>

    <!-- Modal Pengirim -->
    <div class="modal" id="modalPengirim">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Pengirim</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="namaPengirim">Nama:</label>
                            <input type="text" class="form-control" id="namaPengirim" name="namaPengirim" required>
                        </div>
                        <div class="form-group">
                            <label for="noTelpPengirim">No Telp:</label>
                            <input type="text" class="form-control" id="noTelpPengirim" name="noTelpPengirim" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="submitPengirim" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Penerima -->
    <div class="modal" id="modalPenerima">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Penerima</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="namaPenerima">Nama:</label>
                            <input type="text" class="form-control" id="namaPenerima" name="namaPenerima" required>
                        </div>
                        <div class="form-group">
                            <label for="noTelpPenerima">No Telp:</label>
                            <input type="text" class="form-control" id="noTelpPenerima" name="noTelpPenerima" required>
                        </div>
                        <div class="form-group">
                            <label for="alamatPenerima">Alamat:</label>
                            <textarea class="form-control" id="alamatPenerima" name="alamatPenerima" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="kodePosPenerima">Kode Pos:</label>
                            <input type="text" class="form-control" id="kodePosPenerima" name="kodePosPenerima" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="submitPenerima" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Pengirim -->
    <div class="modal" id="modalEditPengirim">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Pengirim</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <input type="hidden" name="idPengirim" id="idPengirim">
                        <div class="form-group">
                            <label for="editNamaPengirim">Nama:</label>
                            <input type="text" class="form-control" id="editNamaPengirim" name="namaPengirim" required>
                        </div>
                        <div class="form-group">
                            <label for="editNoTelpPengirim">No Telp:</label>
                            <input type="text" class="form-control" id="editNoTelpPengirim" name="noTelpPengirim" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="editPengirim" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Penerima -->
    <div class="modal" id="modalEditPenerima">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Penerima</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <input type="hidden" name="idPenerima" id="idPenerima">
                        <div class="form-group">
                            <label for="editNamaPenerima">Nama:</label>
                            <input type="text" class="form-control" id="editNamaPenerima" name="namaPenerima" required>
                        </div>
                        <div class="form-group">
                            <label for="editNoTelpPenerima">No Telp:</label>
                            <input type="text" class="form-control" id="editNoTelpPenerima" name="noTelpPenerima" required>
                        </div>
                        <div class="form-group">
                            <label for="editAlamatPenerima">Alamat:</label>
                            <textarea class="form-control" id="editAlamatPenerima" name="alamatPenerima" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="editKodePosPenerima">Kode Pos:</label>
                            <input type="text" class="form-control" id="editKodePosPenerima" name="kodePosPenerima" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="editPenerima" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript for tab switching -->
    <script>
        function showTab(tabName) {
            // Hide all tab content
            var contents = document.querySelectorAll('.tab-content');
            contents.forEach(function(content) {
                content.classList.remove('active-content');
            });

            // Remove active class from all tabs
            var tabs = document.querySelectorAll('.tab');
            tabs.forEach(function(tab) {
                tab.classList.remove('active-tab');
            });

            // Show selected tab content
            document.getElementById(tabName).classList.add('active-content');

            // Highlight selected tab
            var activeTab = document.querySelector('[onclick="showTab(\'' + tabName + '\')"]');
            activeTab.classList.add('active-tab');
        }

        // Function untuk mempersiapkan data edit pengirim
        function editPengirim(id, nama, noTelp) {
            document.getElementById('idPengirim').value = id;
            document.getElementById('editNamaPengirim').value = nama;
            document.getElementById('editNoTelpPengirim').value = noTelp;
        }

        // Function untuk mempersiapkan data edit penerima
        function editPenerima(id, nama, noTelp, alamat, kodePos) {
            document.getElementById('idPenerima').value = id;
            document.getElementById('editNamaPenerima').value = nama;
            document.getElementById('editNoTelpPenerima').value = noTelp;
            document.getElementById('editAlamatPenerima').value = alamat;
            document.getElementById('editKodePosPenerima').value = kodePos;
        }
    </script>

    <!-- Link to Bootstrap JS for Modal functionality -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

<?php
// Menutup koneksi
$conn->close();
?>
