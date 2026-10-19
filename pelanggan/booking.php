<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'pelanggan') {
    header("Location: ../login.php");
    exit;
}

include "../src/koneksi.php";

$id_user = $_SESSION['id_user'];


/* =========================
   CARI DATA PEMILIK
========================= */

$query_pemilik = mysqli_query(
    $koneksi,
    "SELECT * FROM pemilik
     WHERE id_user='$id_user'"
);

$pemilik = mysqli_fetch_assoc($query_pemilik);

if (!$pemilik) {
    die("Data pemilik belum tersedia. Silakan hubungi admin.");
}

$id_pemilik = $pemilik['id_pemilik'];


/* =========================
   TAMBAH BOOKING
========================= */

if (isset($_POST['tambah'])) {

    $id_hewan = $_POST['id_hewan'];
    $id_layanan = $_POST['id_layanan'];
    $tanggal_booking = $_POST['tanggal_booking'];

    mysqli_query($koneksi, "INSERT INTO booking
        (id_pemilik, id_hewan, id_layanan, tanggal_booking, status)
        VALUES
        ('$id_pemilik', '$id_hewan', '$id_layanan', '$tanggal_booking', 'Menunggu')
    ");

    header("Location: booking.php");
    exit;
}


/* =========================
   EDIT BOOKING
========================= */

if (isset($_POST['edit'])) {

    $id_booking = $_POST['id_booking'];
    $id_hewan = $_POST['id_hewan'];
    $id_layanan = $_POST['id_layanan'];
    $tanggal_booking = $_POST['tanggal_booking'];

    mysqli_query($koneksi, "UPDATE booking SET
        id_hewan='$id_hewan',
        id_layanan='$id_layanan',
        tanggal_booking='$tanggal_booking'
        WHERE id_booking='$id_booking'
        AND id_pemilik='$id_pemilik'
    ");

    header("Location: booking.php");
    exit;
}


/* =========================
   HAPUS BOOKING
========================= */

if (isset($_GET['hapus'])) {

    $id_booking = $_GET['hapus'];

    mysqli_query(
        $koneksi,
        "DELETE FROM booking
         WHERE id_booking='$id_booking'
         AND id_pemilik='$id_pemilik'"
    );

    header("Location: booking.php");
    exit;
}


/* =========================
   DATA HEWAN MILIK SENDIRI
========================= */

$data_hewan = mysqli_query(
    $koneksi,
    "SELECT * FROM hewan
     WHERE id_pemilik='$id_pemilik'
     ORDER BY nama_hewan ASC"
);


/* =========================
   DATA LAYANAN
========================= */

$data_layanan = mysqli_query(
    $koneksi,
    "SELECT * FROM layanan
     ORDER BY nama_layanan ASC"
);


/* =========================
   DATA BOOKING SENDIRI
========================= */

$data_booking = mysqli_query(
    $koneksi,
    "SELECT
        booking.*,
        hewan.nama_hewan,
        layanan.nama_layanan,
        layanan.harga
     FROM booking
     LEFT JOIN hewan
        ON booking.id_hewan = hewan.id_hewan
     LEFT JOIN layanan
        ON booking.id_layanan = layanan.id_layanan
     WHERE booking.id_pemilik='$id_pemilik'
     ORDER BY booking.id_booking DESC"
);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Booking Pet Care</title>

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

    <h1>🐾 BOOKING PET CARE 🐾</h1>


    <!-- =========================
         FORM TAMBAH
    ========================== -->

    <div class="box">

        <h2>➕ Buat Booking</h2>

        <form method="POST">

            <label>Hewan</label>

            <select name="id_hewan" required>

                <option value="">
                    -- Pilih Hewan --
                </option>

                <?php while ($h = mysqli_fetch_assoc($data_hewan)) { ?>

                    <option value="<?= $h['id_hewan']; ?>">
                        <?= htmlspecialchars($h['nama_hewan']); ?>
                        -
                        <?= htmlspecialchars($h['jenis']); ?>
                    </option>

                <?php } ?>

            </select>


            <label>Layanan</label>

            <select name="id_layanan" required>

                <option value="">
                    -- Pilih Layanan --
                </option>

                <?php while ($l = mysqli_fetch_assoc($data_layanan)) { ?>

                    <option value="<?= $l['id_layanan']; ?>">

                        <?= htmlspecialchars($l['nama_layanan']); ?>

                        -
                        Rp <?= number_format($l['harga'], 0, ',', '.'); ?>

                    </option>

                <?php } ?>

            </select>


            <label>Tanggal Booking</label>

            <input
                type="date"
                name="tanggal_booking"
                required
            >


            <button type="submit" name="tambah">
                💾 Buat Booking
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
             FROM booking
             WHERE id_booking='$id_edit'
             AND id_pemilik='$id_pemilik'"
        );

        $booking_edit = mysqli_fetch_assoc($edit_data);

        if ($booking_edit) {

    ?>

    <div class="box edit-box">

        <h2>✏️ Edit Booking</h2>

        <form method="POST">

            <input
                type="hidden"
                name="id_booking"
                value="<?= $booking_edit['id_booking']; ?>"
            >


            <label>Hewan</label>

            <select name="id_hewan" required>

                <?php

                $hewan_edit = mysqli_query(
                    $koneksi,
                    "SELECT * FROM hewan
                     WHERE id_pemilik='$id_pemilik'
                     ORDER BY nama_hewan ASC"
                );

                while ($h = mysqli_fetch_assoc($hewan_edit)) {

                ?>

                    <option
                        value="<?= $h['id_hewan']; ?>"
                        <?= ($h['id_hewan'] == $booking_edit['id_hewan']) ? 'selected' : ''; ?>
                    >
                        <?= htmlspecialchars($h['nama_hewan']); ?>
                        -
                        <?= htmlspecialchars($h['jenis']); ?>
                    </option>

                <?php } ?>

            </select>


            <label>Layanan</label>

            <select name="id_layanan" required>

                <?php

                $layanan_edit = mysqli_query(
                    $koneksi,
                    "SELECT * FROM layanan
                     ORDER BY nama_layanan ASC"
                );

                while ($l = mysqli_fetch_assoc($layanan_edit)) {

                ?>

                    <option
                        value="<?= $l['id_layanan']; ?>"
                        <?= ($l['id_layanan'] == $booking_edit['id_layanan']) ? 'selected' : ''; ?>
                    >

                        <?= htmlspecialchars($l['nama_layanan']); ?>

                        -
                        Rp <?= number_format($l['harga'], 0, ',', '.'); ?>

                    </option>

                <?php } ?>

            </select>


            <label>Tanggal Booking</label>

            <input
                type="date"
                name="tanggal_booking"
                value="<?= htmlspecialchars($booking_edit['tanggal_booking']); ?>"
                required
            >


            <button type="submit" name="edit">
                ✏️ Update Booking
            </button>


            <a
                href="booking.php"
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
         DAFTAR BOOKING
    ========================== -->

    <div class="box">

        <h2>📋 Booking Saya</h2>

        <table>

            <tr>

                <th>No</th>
                <th>Hewan</th>
                <th>Layanan</th>
                <th>Harga</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Aksi</th>

            </tr>

            <?php

            $no = 1;

            while ($data = mysqli_fetch_assoc($data_booking)) {

            ?>

            <tr>

                <td>
                    <?= $no++; ?>
                </td>

                <td>
                    <?= htmlspecialchars($data['nama_hewan']); ?>
                </td>

                <td>
                    <?= htmlspecialchars($data['nama_layanan']); ?>
                </td>

                <td>
                    Rp <?= number_format($data['harga'], 0, ',', '.'); ?>
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
                        href="booking.php?edit=<?= $data['id_booking']; ?>"
                        class="btn-edit"
                    >
                        ✏️ Edit
                    </a>

                    <a
                        href="booking.php?hapus=<?= $data['id_booking']; ?>"
                        class="btn-hapus"
                        onclick="return confirm('Yakin ingin menghapus booking ini?')"
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