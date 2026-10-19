<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

include "../src/koneksi.php";


/* =========================
   TAMBAH DATA LAYANAN
========================= */

if (isset($_POST['tambah'])) {

    $nama_layanan = $_POST['nama_layanan'];
    $harga = $_POST['harga'];

    mysqli_query($koneksi, "INSERT INTO layanan
        (nama_layanan, harga)
        VALUES
        ('$nama_layanan', '$harga')
    ");

    header("Location: layanan.php");
    exit;
}


/* =========================
   EDIT DATA LAYANAN
========================= */

if (isset($_POST['edit'])) {

    $id_layanan = $_POST['id_layanan'];
    $nama_layanan = $_POST['nama_layanan'];
    $harga = $_POST['harga'];

    mysqli_query($koneksi, "UPDATE layanan SET
        nama_layanan='$nama_layanan',
        harga='$harga'
        WHERE id_layanan='$id_layanan'
    ");

    header("Location: layanan.php");
    exit;
}


/* =========================
   HAPUS DATA LAYANAN
========================= */

if (isset($_GET['hapus'])) {

    $id_layanan = $_GET['hapus'];

    mysqli_query(
        $koneksi,
        "DELETE FROM layanan WHERE id_layanan='$id_layanan'"
    );

    header("Location: layanan.php");
    exit;
}


/* =========================
   MENAMPILKAN DATA LAYANAN
========================= */

$data_layanan = mysqli_query(
    $koneksi,
    "SELECT * FROM layanan
     ORDER BY id_layanan DESC"
);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Data Layanan - Pet Care</title>

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

        .edit-box {
            background: #fff8fb;
            border: 2px solid #ffb6d2;
        }

        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
            color: #555;
        }

        input {
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

    </style>

</head>

<body>

<div class="container">

    <a href="index.php" class="kembali">
        ← Kembali ke Dashboard
    </a>

    <h1>🐾 DATA LAYANAN PET CARE 🐾</h1>


    <!-- =========================
         FORM TAMBAH
    ========================== -->

    <div class="box">

        <h2>➕ Tambah Data Layanan</h2>

        <form method="POST">

            <label>Nama Layanan</label>

            <input
                type="text"
                name="nama_layanan"
                placeholder="Contoh: Grooming Basic"
                required
            >


            <label>Harga</label>

            <input
                type="number"
                name="harga"
                min="0"
                placeholder="Contoh: 40000"
                required
            >


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
            "SELECT * FROM layanan
             WHERE id_layanan='$id_edit'"
        );

        $layanan_edit = mysqli_fetch_assoc($edit_data);

        if ($layanan_edit) {

    ?>

    <div class="box edit-box">

        <h2>✏️ Edit Data Layanan</h2>

        <form method="POST">

            <input
                type="hidden"
                name="id_layanan"
                value="<?= $layanan_edit['id_layanan']; ?>"
            >


            <label>Nama Layanan</label>

            <input
                type="text"
                name="nama_layanan"
                value="<?= htmlspecialchars($layanan_edit['nama_layanan']); ?>"
                required
            >


            <label>Harga</label>

            <input
                type="number"
                name="harga"
                min="0"
                value="<?= htmlspecialchars($layanan_edit['harga']); ?>"
                required
            >


            <button type="submit" name="edit">
                ✏️ Update Data
            </button>


            <a
                href="layanan.php"
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
         TABEL DATA LAYANAN
    ========================== -->

    <div class="box">

        <h2>📋 Daftar Layanan</h2>

        <table>

            <tr>

                <th>No</th>
                <th>Nama Layanan</th>
                <th>Harga</th>
                <th>Aksi</th>

            </tr>

            <?php

            $no = 1;

            while ($data = mysqli_fetch_assoc($data_layanan)) {

            ?>

            <tr>

                <td>
                    <?= $no++; ?>
                </td>

                <td>
                    <?= htmlspecialchars($data['nama_layanan']); ?>
                </td>

                <td>
                    Rp <?= number_format($data['harga'], 0, ',', '.'); ?>
                </td>

                <td>

                    <a
                        href="layanan.php?edit=<?= $data['id_layanan']; ?>"
                        class="btn-edit"
                    >
                        ✏️ Edit
                    </a>

                    <a
                        href="layanan.php?hapus=<?= $data['id_layanan']; ?>"
                        class="btn-hapus"
                        onclick="return confirm('Yakin ingin menghapus layanan ini?')"
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