<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

include "../src/koneksi.php";


/* =========================
   TAMBAH DATA BOOKING
========================= */

if (isset($_POST['tambah'])) {

    $id_pemilik = $_POST['id_pemilik'];
    $id_hewan = $_POST['id_hewan'];
    $id_layanan = $_POST['id_layanan'];
    $tanggal_booking = $_POST['tanggal_booking'];
    $status = $_POST['status'];

    mysqli_query($koneksi, "INSERT INTO booking
        (id_pemilik, id_hewan, id_layanan, tanggal_booking, status)
        VALUES
        ('$id_pemilik', '$id_hewan', '$id_layanan', '$tanggal_booking', '$status')
    ");

    header("Location: booking.php");
    exit;
}


/* =========================
   EDIT DATA BOOKING
========================= */

if (isset($_POST['edit'])) {

    $id_booking = $_POST['id_booking'];
    $id_pemilik = $_POST['id_pemilik'];
    $id_hewan = $_POST['id_hewan'];
    $id_layanan = $_POST['id_layanan'];
    $tanggal_booking = $_POST['tanggal_booking'];
    $status = $_POST['status'];

    mysqli_query($koneksi, "UPDATE booking SET
        id_pemilik='$id_pemilik',
        id_hewan='$id_hewan',
        id_layanan='$id_layanan',
        tanggal_booking='$tanggal_booking',
        status='$status'
        WHERE id_booking='$id_booking'
    ");

    header("Location: booking.php");
    exit;
}


/* =========================
   HAPUS DATA BOOKING
========================= */

if (isset($_GET['hapus'])) {

    $id_booking = $_GET['hapus'];

    mysqli_query(
        $koneksi,
        "DELETE FROM booking WHERE id_booking='$id_booking'"
    );

    header("Location: booking.php");
    exit;
}


/* =========================
   DATA PEMILIK
========================= */

$data_pemilik = mysqli_query(
    $koneksi,
    "SELECT * FROM pemilik
     ORDER BY nama_pemilik ASC"
);


/* =========================
   DATA HEWAN
========================= */

$data_hewan = mysqli_query(
    $koneksi,
    "SELECT
        hewan.*,
        pemilik.nama_pemilik
     FROM hewan
     LEFT JOIN pemilik
        ON hewan.id_pemilik = pemilik.id_pemilik
     ORDER BY hewan.nama_hewan ASC"
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
   DATA BOOKING
========================= */

$data_booking = mysqli_query(
    $koneksi,
    "SELECT
        booking.*,
        pemilik.nama_pemilik,
        hewan.nama_hewan,
        layanan.nama_layanan,
        layanan.harga
     FROM booking
     LEFT JOIN pemilik
        ON booking.id_pemilik = pemilik.id_pemilik
     LEFT JOIN hewan
        ON booking.id_hewan = hewan.id_hewan
     LEFT JOIN layanan
        ON booking.id_layanan = layanan.id_layanan
     ORDER BY booking.id_booking DESC"
);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Data Booking - Pet Care</title>

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
            padding: 6px 10px;
            border-radius: 10px;
            display: inline-block;
            font-size: 13px;
        }

    </style>

</head>

<body>

<div class="container">

    <a href="index.php" class="kembali">
        ← Kembali ke Dashboard
    </a>

    <h1>🐾 DATA BOOKING PET CARE 🐾</h1>


    <!-- =========================
         FORM TAMBAH
    ========================== -->

    <div class="box">

        <h2>➕ Tambah Data Booking</h2>

        <form method="POST">

            <label>Pemilik</label>

            <select name="id_pemilik" required>

                <option value="">
                    -- Pilih Pemilik --
                </option>

                <?php while ($p = mysqli_fetch_assoc($data_pemilik)) { ?>

                    <option value="<?= $p['id_pemilik']; ?>">
                        <?= htmlspecialchars($p['nama_pemilik']); ?>
                    </option>

                <?php } ?>

            </select>


            <label>Hewan</label>

            <select name="id_hewan" required>

                <option value="">
                    -- Pilih Hewan --
                </option>

                <?php while ($h = mysqli_fetch_assoc($data_hewan)) { ?>

                    <option value="<?= $h['id_hewan']; ?>">
                        <?= htmlspecialchars($h['nama_hewan']); ?>
                        -
                        <?= htmlspecialchars($h['nama_pemilik']); ?>
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


            <label>Status</label>

            <select name="status" required>

                <option value="Menunggu">
                    Menunggu
                </option>

                <option value="Diproses">
                    Diproses
                </option>

                <option value="Selesai">
                    Selesai
                </option>

                <option value="Dibatalkan">
                    Dibatalkan
                </option>

            </select>


            <button type="submit" name="tambah">
                💾 Simpan Booking
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
            "SELECT * FROM booking
             WHERE id_booking='$id_edit'"
        );

        $booking_edit = mysqli_fetch_assoc($edit_data);

        if ($booking_edit) {

    ?>

    <div class="box edit-box">

        <h2>✏️ Edit Data Booking</h2>

        <form method="POST">

            <input
                type="hidden"
                name="id_booking"
                value="<?= $booking_edit['id_booking']; ?>"
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
                        <?= ($p['id_pemilik'] == $booking_edit['id_pemilik']) ? 'selected' : ''; ?>
                    >
                        <?= htmlspecialchars($p['nama_pemilik']); ?>
                    </option>

                <?php } ?>

            </select>


            <label>Hewan</label>

            <select name="id_hewan" required>

                <?php

                $hewan_edit = mysqli_query(
                    $koneksi,
                    "SELECT
                        hewan.*,
                        pemilik.nama_pemilik
                     FROM hewan
                     LEFT JOIN pemilik
                        ON hewan.id_pemilik = pemilik.id_pemilik
                     ORDER BY hewan.nama_hewan ASC"
                );

                while ($h = mysqli_fetch_assoc($hewan_edit)) {

                ?>

                    <option
                        value="<?= $h['id_hewan']; ?>"
                        <?= ($h['id_hewan'] == $booking_edit['id_hewan']) ? 'selected' : ''; ?>
                    >
                        <?= htmlspecialchars($h['nama_hewan']); ?>
                        -
                        <?= htmlspecialchars($h['nama_pemilik']); ?>
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


            <label>Status</label>

            <select name="status" required>

                <option value="Menunggu"
                    <?= ($booking_edit['status'] == 'Menunggu') ? 'selected' : ''; ?>>
                    Menunggu
                </option>

                <option value="Diproses"
                    <?= ($booking_edit['status'] == 'Diproses') ? 'selected' : ''; ?>>
                    Diproses
                </option>

                <option value="Selesai"
                    <?= ($booking_edit['status'] == 'Selesai') ? 'selected' : ''; ?>>
                    Selesai
                </option>

                <option value="Dibatalkan"
                    <?= ($booking_edit['status'] == 'Dibatalkan') ? 'selected' : ''; ?>>
                    Dibatalkan
                </option>

            </select>


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
         TABEL DATA BOOKING
    ========================== -->

    <div class="box">

        <h2>📋 Daftar Booking</h2>

        <table>

            <tr>

                <th>No</th>
                <th>Pemilik</th>
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
                    <?= htmlspecialchars($data['nama_pemilik']); ?>
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