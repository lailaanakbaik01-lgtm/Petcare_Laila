<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

include "../src/koneksi.php";

/* =========================
   TAMBAH DATA HEWAN
========================= */
if (isset($_POST['tambah'])) {

    $id_pemilik = $_POST['id_pemilik'];
    $nama_hewan = $_POST['nama_hewan'];
    $jenis = $_POST['jenis'];
    $ras = $_POST['ras'];
    $umur = $_POST['umur'];
    $jenis_kelamin = $_POST['jenis_kelamin'];

    mysqli_query($koneksi, "INSERT INTO hewan
        (id_pemilik, nama_hewan, jenis, ras, umur, jenis_kelamin)
        VALUES
        ('$id_pemilik', '$nama_hewan', '$jenis', '$ras', '$umur', '$jenis_kelamin')
    ");

    header("Location: hewan.php");
    exit;
}


/* =========================
   EDIT DATA HEWAN
========================= */
if (isset($_POST['edit'])) {

    $id_hewan = $_POST['id_hewan'];
    $id_pemilik = $_POST['id_pemilik'];
    $nama_hewan = $_POST['nama_hewan'];
    $jenis = $_POST['jenis'];
    $ras = $_POST['ras'];
    $umur = $_POST['umur'];
    $jenis_kelamin = $_POST['jenis_kelamin'];

    mysqli_query($koneksi, "UPDATE hewan SET
        id_pemilik='$id_pemilik',
        nama_hewan='$nama_hewan',
        jenis='$jenis',
        ras='$ras',
        umur='$umur',
        jenis_kelamin='$jenis_kelamin'
        WHERE id_hewan='$id_hewan'
    ");

    header("Location: hewan.php");
    exit;
}


/* =========================
   HAPUS DATA HEWAN
========================= */
if (isset($_GET['hapus'])) {

    $id_hewan = $_GET['hapus'];

    mysqli_query(
        $koneksi,
        "DELETE FROM hewan WHERE id_hewan='$id_hewan'"
    );

    header("Location: hewan.php");
    exit;
}


/* =========================
   DATA PEMILIK
========================= */
$data_pemilik = mysqli_query($koneksi, "
    SELECT *
    FROM pemilik
    ORDER BY nama_pemilik ASC
");


/* =========================
   DATA HEWAN
========================= */
$data_hewan = mysqli_query($koneksi, "
    SELECT 
        hewan.*,
        pemilik.nama_pemilik
    FROM hewan
    LEFT JOIN pemilik
        ON hewan.id_pemilik = pemilik.id_pemilik
    ORDER BY hewan.id_hewan DESC
");

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Data Hewan - Pet Care</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background: #ffeaf3;
            margin: 0;
            padding: 30px;
        }

        .container {
            width: 95%;
            margin: auto;
        }

        h1 {
            color: #d63384;
            text-align: center;
        }

        .box {
            background: white;
            padding: 25px;
            border-radius: 20px;
            margin-bottom: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
            color: #555;
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-sizing: border-box;
        }

        button {
            background: #ff69a4;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
            margin-top: 15px;
        }

        button:hover {
            background: #d63384;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th {
            background: #ff69a4;
            color: white;
            padding: 12px;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #eee;
            text-align: center;
        }

        tr:hover {
            background: #fff4f8;
        }

        .btn-edit {
            background: #ffc107;
            color: black;
            text-decoration: none;
            padding: 7px 12px;
            border-radius: 8px;
        }

        .btn-hapus {
            background: #dc3545;
            color: white;
            text-decoration: none;
            padding: 7px 12px;
            border-radius: 8px;
        }

        .kembali {
            display: inline-block;
            margin-bottom: 20px;
            background: #d63384;
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 10px;
        }

        .kembali:hover {
            background: #b52b70;
        }

        .edit-box {
            background: #fff8fb;
            border: 2px solid #ffb6d2;
        }

    </style>

</head>

<body>

<div class="container">

    <a href="index.php" class="kembali">
        ← Kembali ke Dashboard
    </a>

    <h1>🐾 DATA HEWAN 🐾</h1>


    <!-- =========================
         FORM TAMBAH
    ========================== -->

    <div class="box">

        <h2>➕ Tambah Data Hewan</h2>

        <form method="POST">

            <label>Pemilik</label>

            <select name="id_pemilik" required>

                <option value="">-- Pilih Pemilik --</option>

                <?php while ($pemilik = mysqli_fetch_assoc($data_pemilik)) { ?>

                    <option value="<?= $pemilik['id_pemilik']; ?>">
                        <?= htmlspecialchars($pemilik['nama_pemilik']); ?>
                    </option>

                <?php } ?>

            </select>


            <label>Nama Hewan</label>

            <input
                type="text"
                name="nama_hewan"
                placeholder="Contoh: Mochi"
                required
            >


            <label>Jenis Hewan</label>

            <select name="jenis" required>

                <option value="">-- Pilih Jenis --</option>
                <option value="Kucing">Kucing</option>
                <option value="Anjing">Anjing</option>
                <option value="Kelinci">Kelinci</option>
                <option value="Hamster">Hamster</option>
                <option value="Burung">Burung</option>
                <option value="Lainnya">Lainnya</option>

            </select>


            <label>Ras</label>

            <input
                type="text"
                name="ras"
                placeholder="Contoh: Persia"
            >


            <label>Umur</label>

            <input
                type="number"
                name="umur"
                min="0"
                placeholder="Umur dalam tahun"
                required
            >


            <label>Jenis Kelamin</label>

            <select name="jenis_kelamin" required>

                <option value="">-- Pilih Jenis Kelamin --</option>
                <option value="Jantan">Jantan</option>
                <option value="Betina">Betina</option>

            </select>


            <button type="submit" name="tambah">
                💾 Simpan Data
            </button>

        </form>

    </div>


    <!-- =========================
         FORM EDIT
    ========================== -->

    <?php

    if (isset($_GET['edit'])) {

        $id_edit = $_GET['edit'];

        $edit_data = mysqli_query(
            $koneksi,
            "SELECT * FROM hewan
             WHERE id_hewan='$id_edit'"
        );

        $hewan_edit = mysqli_fetch_assoc($edit_data);

        if ($hewan_edit) {

    ?>

    <div class="box edit-box">

        <h2>✏️ Edit Data Hewan</h2>

        <form method="POST">

            <input
                type="hidden"
                name="id_hewan"
                value="<?= $hewan_edit['id_hewan']; ?>"
            >


            <label>Pemilik</label>

            <select name="id_pemilik" required>

                <?php

                $pemilik_edit = mysqli_query(
                    $koneksi,
                    "SELECT * FROM pemilik
                     ORDER BY nama_pemilik ASC"
                );

                while ($p = mysqli_fetch_assoc($pemilik_edit)) {

                ?>

                    <option
                        value="<?= $p['id_pemilik']; ?>"
                        <?= ($p['id_pemilik'] == $hewan_edit['id_pemilik']) ? 'selected' : ''; ?>
                    >
                        <?= htmlspecialchars($p['nama_pemilik']); ?>
                    </option>

                <?php } ?>

            </select>


            <label>Nama Hewan</label>

            <input
                type="text"
                name="nama_hewan"
                value="<?= htmlspecialchars($hewan_edit['nama_hewan']); ?>"
                required
            >


            <label>Jenis Hewan</label>

            <select name="jenis" required>

                <option value="Kucing"
                    <?= ($hewan_edit['jenis'] == 'Kucing') ? 'selected' : ''; ?>>
                    Kucing
                </option>

                <option value="Anjing"
                    <?= ($hewan_edit['jenis'] == 'Anjing') ? 'selected' : ''; ?>>
                    Anjing
                </option>

                <option value="Kelinci"
                    <?= ($hewan_edit['jenis'] == 'Kelinci') ? 'selected' : ''; ?>>
                    Kelinci
                </option>

                <option value="Hamster"
                    <?= ($hewan_edit['jenis'] == 'Hamster') ? 'selected' : ''; ?>>
                    Hamster
                </option>

                <option value="Burung"
                    <?= ($hewan_edit['jenis'] == 'Burung') ? 'selected' : ''; ?>>
                    Burung
                </option>

                <option value="Lainnya"
                    <?= ($hewan_edit['jenis'] == 'Lainnya') ? 'selected' : ''; ?>>
                    Lainnya
                </option>

            </select>


            <label>Ras</label>

            <input
                type="text"
                name="ras"
                value="<?= htmlspecialchars($hewan_edit['ras']); ?>"
            >


            <label>Umur</label>

            <input
                type="number"
                name="umur"
                min="0"
                value="<?= htmlspecialchars($hewan_edit['umur']); ?>"
                required
            >


            <label>Jenis Kelamin</label>

            <select name="jenis_kelamin" required>

                <option value="Jantan"
                    <?= ($hewan_edit['jenis_kelamin'] == 'Jantan') ? 'selected' : ''; ?>>
                    Jantan
                </option>

                <option value="Betina"
                    <?= ($hewan_edit['jenis_kelamin'] == 'Betina') ? 'selected' : ''; ?>>
                    Betina
                </option>

            </select>


            <button type="submit" name="edit">
                ✏️ Update Data
            </button>

            <a
                href="hewan.php"
                style="
                    display:inline-block;
                    margin-left:10px;
                    background:#777;
                    color:white;
                    padding:10px 20px;
                    border-radius:10px;
                    text-decoration:none;
                "
            >
                Batal
            </a>

        </form>

    </div>

    <?php

        }

    }

    ?>


    <!-- =========================
         TABEL DATA HEWAN
    ========================== -->

    <div class="box">

        <h2>📋 Daftar Data Hewan</h2>

        <table>

            <tr>

                <th>No</th>
                <th>Nama Hewan</th>
                <th>Jenis</th>
                <th>Ras</th>
                <th>Umur</th>
                <th>Jenis Kelamin</th>
                <th>Pemilik</th>
                <th>Aksi</th>

            </tr>

            <?php

            $no = 1;

            while ($data = mysqli_fetch_assoc($data_hewan)) {

            ?>

            <tr>

                <td><?= $no++; ?></td>

                <td>
                    <?= htmlspecialchars($data['nama_hewan']); ?>
                </td>

                <td>
                    <?= htmlspecialchars($data['jenis']); ?>
                </td>

                <td>
                    <?= htmlspecialchars($data['ras']); ?>
                </td>

                <td>
                    <?= htmlspecialchars($data['umur']); ?> tahun
                </td>

                <td>
                    <?= htmlspecialchars($data['jenis_kelamin']); ?>
                </td>

                <td>
                    <?= htmlspecialchars($data['nama_pemilik']); ?>
                </td>

                <td>

                    <a
                        href="hewan.php?edit=<?= $data['id_hewan']; ?>"
                        class="btn-edit"
                    >
                        ✏️ Edit
                    </a>

                    <a
                        href="hewan.php?hapus=<?= $data['id_hewan']; ?>"
                        class="btn-hapus"
                        onclick="return confirm('Yakin ingin menghapus data hewan ini?')"
                    >
                        🗑️ Hapus
                    </a>

                </td>

            </tr>

            <?php } ?>

        </table>

    </div>

</div>

</body>

</html>