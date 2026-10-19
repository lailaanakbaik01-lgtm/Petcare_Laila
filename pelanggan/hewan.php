<?php

session_start();

if (!isset($_SESSION['id_user']) || $_SESSION['role'] != 'pelanggan') {
    header("Location: ../login.php");
    exit;
}

include "../src/koneksi.php";

$id_user = $_SESSION['id_user'];

// Ambil data pelanggan
$query_pemilik = mysqli_query(
    $koneksi,
    "SELECT * FROM pemilik WHERE id_user='$id_user'"
);

$data_pemilik = mysqli_fetch_assoc($query_pemilik);

if (!$data_pemilik) {
    die("Data pelanggan belum tersedia.");
}

$id_pemilik = $data_pemilik['id_pemilik'];


// =========================
// TAMBAH DATA
// =========================

if (isset($_POST['tambah'])) {

    $nama_hewan = $_POST['nama_hewan'];
    $jenis = $_POST['jenis'];
    $ras = $_POST['ras'];
    $umur = $_POST['umur'];
    $jenis_kelamin = $_POST['jenis_kelamin'];

    mysqli_query(
        $koneksi,
        "INSERT INTO hewan
        (id_pemilik, nama_hewan, jenis, ras, umur, jenis_kelamin)
        VALUES
        ('$id_pemilik', '$nama_hewan', '$jenis', '$ras', '$umur', '$jenis_kelamin')"
    );

    header("Location: hewan.php");
    exit;
}


// =========================
// HAPUS DATA
// =========================

if (isset($_GET['hapus'])) {

    $id_hewan = $_GET['hapus'];

    mysqli_query(
        $koneksi,
        "DELETE FROM hewan
         WHERE id_hewan='$id_hewan'
         AND id_pemilik='$id_pemilik'"
    );

    header("Location: hewan.php");
    exit;
}


// =========================
// EDIT DATA
// =========================

if (isset($_POST['edit'])) {

    $id_hewan = $_POST['id_hewan'];
    $nama_hewan = $_POST['nama_hewan'];
    $jenis = $_POST['jenis'];
    $ras = $_POST['ras'];
    $umur = $_POST['umur'];
    $jenis_kelamin = $_POST['jenis_kelamin'];

    mysqli_query(
        $koneksi,
        "UPDATE hewan SET
            nama_hewan='$nama_hewan',
            jenis='$jenis',
            ras='$ras',
            umur='$umur',
            jenis_kelamin='$jenis_kelamin'
         WHERE id_hewan='$id_hewan'
         AND id_pemilik='$id_pemilik'"
    );

    header("Location: hewan.php");
    exit;
}


// =========================
// MODE EDIT
// =========================

$data_edit = null;

if (isset($_GET['edit'])) {

    $id_edit = $_GET['edit'];

    $query_edit = mysqli_query(
        $koneksi,
        "SELECT * FROM hewan
         WHERE id_hewan='$id_edit'
         AND id_pemilik='$id_pemilik'"
    );

    $data_edit = mysqli_fetch_assoc($query_edit);
}


// =========================
// TAMPIL DATA
// =========================

$query_hewan = mysqli_query(
    $koneksi,
    "SELECT * FROM hewan
     WHERE id_pemilik='$id_pemilik'
     ORDER BY id_hewan DESC"
);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Data Hewan - Pet Care</title>

    <style>

        body {
            background: #FFF5F7;
            font-family: Arial, sans-serif;
            color: #60454D;
        }

        .container {
            width: 95%;
            margin: 30px auto;
        }

        .judul {
            text-align: center;
            color: #D96C82;
        }

        .box {
            background: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        }

        label {
            display: block;
            margin-top: 12px;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #F0C9D2;
            border-radius: 8px;
            background: #FFF0F3;
        }

        button {
            padding: 10px 18px;
            border: none;
            border-radius: 8px;
            color: white;
            cursor: pointer;
            margin-top: 15px;
        }

        .tambah {
            background: #D96C82;
        }

        .edit {
            background: #E7A84B;
            text-decoration: none;
        }

        .hapus {
            background: #D9536F;
            text-decoration: none;
        }

        .batal {
            background: #888;
            text-decoration: none;
        }

        .kembali {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 18px;
            background: #D96C82;
            color: white;
            text-decoration: none;
            border-radius: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th {
            background: #E88B9C;
            color: white;
            padding: 12px;
        }

        td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #F3DDE2;
        }

        .aksi {
            padding: 7px 12px;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            display: inline-block;
            margin: 2px;
        }

    </style>

</head>

<body>

<div class="container">

    <a href="index.php" class="kembali">
        ← Kembali ke Dashboard
    </a>

    <h1 class="judul">
        🐾 Data Hewan Saya 🐾
    </h1>


    <!-- ========================= -->
    <!-- FORM TAMBAH / EDIT -->
    <!-- ========================= -->

    <div class="box">

        <?php if ($data_edit) { ?>

            <h2>✏️ Edit Data Hewan</h2>

            <form method="POST">

                <input
                    type="hidden"
                    name="id_hewan"
                    value="<?php echo $data_edit['id_hewan']; ?>"
                >

                <label>Nama Hewan</label>

                <input
                    type="text"
                    name="nama_hewan"
                    value="<?php echo $data_edit['nama_hewan']; ?>"
                    required
                >

                <label>Jenis Hewan</label>

                <select name="jenis" required>

                    <option value="Kucing"
                        <?php if ($data_edit['jenis'] == 'Kucing') echo 'selected'; ?>>
                        Kucing
                    </option>

                    <option value="Anjing"
                        <?php if ($data_edit['jenis'] == 'Anjing') echo 'selected'; ?>>
                        Anjing
                    </option>

                    <option value="Kelinci"
                        <?php if ($data_edit['jenis'] == 'Kelinci') echo 'selected'; ?>>
                        Kelinci
                    </option>

                    <option value="Hamster"
                        <?php if ($data_edit['jenis'] == 'Hamster') echo 'selected'; ?>>
                        Hamster
                    </option>

                    <option value="Burung"
                        <?php if ($data_edit['jenis'] == 'Burung') echo 'selected'; ?>>
                        Burung
                    </option>

                </select>

                <label>Ras</label>

                <input
                    type="text"
                    name="ras"
                    value="<?php echo $data_edit['ras']; ?>"
                    required
                >

                <label>Umur</label>

                <input
                    type="number"
                    name="umur"
                    value="<?php echo $data_edit['umur']; ?>"
                    min="0"
                    required
                >

                <label>Jenis Kelamin</label>

                <select name="jenis_kelamin" required>

                    <option value="Jantan"
                        <?php if ($data_edit['jenis_kelamin'] == 'Jantan') echo 'selected'; ?>>
                        Jantan
                    </option>

                    <option value="Betina"
                        <?php if ($data_edit['jenis_kelamin'] == 'Betina') echo 'selected'; ?>>
                        Betina
                    </option>

                </select>

                <button
                    type="submit"
                    name="edit"
                    class="tambah">

                    💾 Simpan Perubahan

                </button>

                <a
                    href="hewan.php"
                    class="aksi batal">

                    Batal

                </a>

            </form>

        <?php } else { ?>

            <h2>➕ Tambah Data Hewan</h2>

            <form method="POST">

                <label>Nama Hewan</label>

                <input
                    type="text"
                    name="nama_hewan"
                    placeholder="Contoh: Milo"
                    required
                >

                <label>Jenis Hewan</label>

                <select name="jenis" required>

                    <option value="">
                        -- Pilih Jenis --
                    </option>

                    <option value="Kucing">Kucing</option>
                    <option value="Anjing">Anjing</option>
                    <option value="Kelinci">Kelinci</option>
                    <option value="Hamster">Hamster</option>
                    <option value="Burung">Burung</option>

                </select>

                <label>Ras</label>

                <input
                    type="text"
                    name="ras"
                    placeholder="Contoh: Persia"
                    required
                >

                <label>Umur</label>

                <input
                    type="number"
                    name="umur"
                    min="0"
                    placeholder="Contoh: 2"
                    required
                >

                <label>Jenis Kelamin</label>

                <select name="jenis_kelamin" required>

                    <option value="">
                        -- Pilih Jenis Kelamin --
                    </option>

                    <option value="Jantan">Jantan</option>
                    <option value="Betina">Betina</option>

                </select>

                <button
                    type="submit"
                    name="tambah"
                    class="tambah">

                    ➕ Tambah Data

                </button>

            </form>

        <?php } ?>

    </div>


    <!-- ========================= -->
    <!-- TABEL DATA -->
    <!-- ========================= -->

    <div class="box">

        <h2>📋 Daftar Hewan</h2>

        <table>

            <tr>
                <th>No</th>
                <th>Nama Hewan</th>
                <th>Jenis</th>
                <th>Ras</th>
                <th>Umur</th>
                <th>Jenis Kelamin</th>
                <th>Aksi</th>
            </tr>

            <?php

            $no = 1;

            if (mysqli_num_rows($query_hewan) > 0) {

                while ($data = mysqli_fetch_assoc($query_hewan)) {

            ?>

            <tr>

                <td>
                    <?php echo $no++; ?>
                </td>

                <td>
                    <?php echo $data['nama_hewan']; ?>
                </td>

                <td>
                    <?php echo $data['jenis']; ?>
                </td>

                <td>
                    <?php echo $data['ras']; ?>
                </td>

                <td>
                    <?php echo $data['umur']; ?> tahun
                </td>

                <td>
                    <?php echo $data['jenis_kelamin']; ?>
                </td>

                <td>

                    <a
                        href="hewan.php?edit=<?php echo $data['id_hewan']; ?>"
                        class="aksi edit">

                        ✏️ Edit

                    </a>

                    <a
                        href="hewan.php?hapus=<?php echo $data['id_hewan']; ?>"
                        class="aksi hapus"
                        onclick="return confirm('Yakin ingin menghapus data hewan ini?');">

                        🗑️ Hapus

                    </a>

                </td>

            </tr>

            <?php

                }

            } else {

            ?>

            <tr>

                <td colspan="7">
                    🐾 Belum ada data hewan.
                </td>

            </tr>

            <?php } ?>

        </table>

    </div>

</div>

</body>

</html>