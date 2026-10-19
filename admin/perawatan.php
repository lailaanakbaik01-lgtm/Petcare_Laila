<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

include "../src/koneksi.php";


/* =========================
   TAMBAH DATA PERAWATAN
========================= */

if (isset($_POST['tambah'])) {

    $id_booking = $_POST['id_booking'];
    $status = $_POST['status'];

    mysqli_query($koneksi, "INSERT INTO perawatan
        (id_booking, status)
        VALUES
        ('$id_booking', '$status')
    ");

    header("Location: perawatan.php");
    exit;
}


/* =========================
   EDIT DATA PERAWATAN
========================= */

if (isset($_POST['edit'])) {

    $id_perawatan = $_POST['id_perawatan'];
    $id_booking = $_POST['id_booking'];
    $status = $_POST['status'];

    mysqli_query($koneksi, "UPDATE perawatan SET
        id_booking='$id_booking',
        status='$status'
        WHERE id_perawatan='$id_perawatan'
    ");

    header("Location: perawatan.php");
    exit;
}


/* =========================
   HAPUS DATA PERAWATAN
========================= */

if (isset($_GET['hapus'])) {

    $id_perawatan = $_GET['hapus'];

    mysqli_query(
        $koneksi,
        "DELETE FROM perawatan
         WHERE id_perawatan='$id_perawatan'"
    );

    header("Location: perawatan.php");
    exit;
}


/* =========================
   DATA BOOKING
========================= */

$data_booking = mysqli_query(
    $koneksi,
    "SELECT
        booking.id_booking,
        booking.tanggal_booking,
        booking.status AS status_booking,
        pemilik.nama_pemilik,
        hewan.nama_hewan,
        layanan.nama_layanan
     FROM booking
     LEFT JOIN pemilik
        ON booking.id_pemilik = pemilik.id_pemilik
     LEFT JOIN hewan
        ON booking.id_hewan = hewan.id_hewan
     LEFT JOIN layanan
        ON booking.id_layanan = layanan.id_layanan
     ORDER BY booking.id_booking DESC"
);


/* =========================
   DATA PERAWATAN
========================= */

$data_perawatan = mysqli_query(
    $koneksi,
    "SELECT
        perawatan.*,
        booking.tanggal_booking,
        pemilik.nama_pemilik,
        hewan.nama_hewan,
        layanan.nama_layanan
     FROM perawatan
     LEFT JOIN booking
        ON perawatan.id_booking = booking.id_booking
     LEFT JOIN pemilik
        ON booking.id_pemilik = pemilik.id_pemilik
     LEFT JOIN hewan
        ON booking.id_hewan = hewan.id_hewan
     LEFT JOIN layanan
        ON booking.id_layanan = layanan.id_layanan
     ORDER BY perawatan.id_perawatan DESC"
);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Data Perawatan - Pet Care</title>

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

        .status {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 10px;
            background: #ffe0ed;
            color: #d63384;
        }

    </style>

</head>

<body>

<div class="container">

    <a href="index.php" class="kembali">
        ← Kembali ke Dashboard
    </a>

    <h1>🐾 DATA PERAWATAN HEWAN 🐾</h1>


    <!-- =========================
         FORM TAMBAH
    ========================== -->

    <div class="box">

        <h2>➕ Tambah Data Perawatan</h2>

        <form method="POST">

            <label>Booking</label>

            <select name="id_booking" required>

                <option value="">
                    -- Pilih Booking --
                </option>

                <?php while ($b = mysqli_fetch_assoc($data_booking)) { ?>

                    <option value="<?= $b['id_booking']; ?>">

                        <?= htmlspecialchars($b['nama_hewan']); ?>

                        -

                        <?= htmlspecialchars($b['nama_pemilik']); ?>

                        -

                        <?= htmlspecialchars($b['nama_layanan']); ?>

                        -

                        <?= htmlspecialchars($b['tanggal_booking']); ?>

                    </option>

                <?php } ?>

            </select>


            <label>Status Perawatan</label>

            <select name="status" required>

                <option value="">
                    -- Pilih Status --
                </option>

                <option value="Menunggu">
                    Menunggu
                </option>

                <option value="Sedang Dirawat">
                    Sedang Dirawat
                </option>

                <option value="Selesai">
                    Selesai
                </option>

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
            "SELECT *
             FROM perawatan
             WHERE id_perawatan='$id_edit'"
        );

        $perawatan_edit = mysqli_fetch_assoc($edit_data);

        if ($perawatan_edit) {

    ?>

    <div class="box edit-box">

        <h2>✏️ Edit Data Perawatan</h2>

        <form method="POST">

            <input
                type="hidden"
                name="id_perawatan"
                value="<?= $perawatan_edit['id_perawatan']; ?>"
            >


            <label>Booking</label>

            <select name="id_booking" required>

                <?php

                $booking_edit = mysqli_query(
                    $koneksi,
                    "SELECT
                        booking.id_booking,
                        booking.tanggal_booking,
                        pemilik.nama_pemilik,
                        hewan.nama_hewan,
                        layanan.nama_layanan
                     FROM booking
                     LEFT JOIN pemilik
                        ON booking.id_pemilik = pemilik.id_pemilik
                     LEFT JOIN hewan
                        ON booking.id_hewan = hewan.id_hewan
                     LEFT JOIN layanan
                        ON booking.id_layanan = layanan.id_layanan
                     ORDER BY booking.id_booking DESC"
                );

                while ($b = mysqli_fetch_assoc($booking_edit)) {

                ?>

                    <option
                        value="<?= $b['id_booking']; ?>"
                        <?= ($b['id_booking'] == $perawatan_edit['id_booking']) ? 'selected' : ''; ?>
                    >

                        <?= htmlspecialchars($b['nama_hewan']); ?>

                        -

                        <?= htmlspecialchars($b['nama_pemilik']); ?>

                        -

                        <?= htmlspecialchars($b['nama_layanan']); ?>

                        -

                        <?= htmlspecialchars($b['tanggal_booking']); ?>

                    </option>

                <?php } ?>

            </select>


            <label>Status Perawatan</label>

            <select name="status" required>

                <option value="Menunggu"
                    <?= ($perawatan_edit['status'] == 'Menunggu') ? 'selected' : ''; ?>>
                    Menunggu
                </option>

                <option value="Sedang Dirawat"
                    <?= ($perawatan_edit['status'] == 'Sedang Dirawat') ? 'selected' : ''; ?>>
                    Sedang Dirawat
                </option>

                <option value="Selesai"
                    <?= ($perawatan_edit['status'] == 'Selesai') ? 'selected' : ''; ?>>
                    Selesai
                </option>

            </select>


            <button type="submit" name="edit">
                ✏️ Update Data
            </button>


            <a
                href="perawatan.php"
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
         TABEL DATA
    ========================== -->

    <div class="box">

        <h2>📋 Daftar Perawatan</h2>

        <table>

            <tr>

                <th>No</th>
                <th>Pemilik</th>
                <th>Hewan</th>
                <th>Layanan</th>
                <th>Tanggal Booking</th>
                <th>Status</th>
                <th>Aksi</th>

            </tr>

            <?php

            $no = 1;

            while ($data = mysqli_fetch_assoc($data_perawatan)) {

            ?>

            <tr>

                <td>
                    <?= $no++; ?>
                </td>

                <td>
                    <?= htmlspecialchars($data['nama_pemilik']); ?>
                </td>

                <td>
                    <?= htmlspecialchars($data['nama_hewan']); ?>
                </td>

                <td>
                    <?= htmlspecialchars($data['nama_layanan']); ?>
                </td>

                <td>
                    <?= htmlspecialchars($data['tanggal_booking']); ?>
                </td>

                <td>

                    <span class="status">
                        <?= htmlspecialchars($data['status']); ?>
                    </span>

                </td>

                <td>

                    <a
                        href="perawatan.php?edit=<?= $data['id_perawatan']; ?>"
                        class="btn-edit"
                    >
                        ✏️ Edit
                    </a>

                    <a
                        href="perawatan.php?hapus=<?= $data['id_perawatan']; ?>"
                        class="btn-hapus"
                        onclick="return confirm('Yakin ingin menghapus data perawatan ini?')"
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