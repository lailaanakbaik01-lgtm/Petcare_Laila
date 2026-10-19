<?php

session_start();

include "../src/koneksi.php";

// Cek login
if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

// Cek role admin
if ($_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

// Ambil data laporan
$query = mysqli_query(
    $koneksi,
    "SELECT
        booking.id_booking,
        pemilik.nama_pemilik,
        hewan.nama_hewan,
        hewan.jenis,
        layanan.nama_layanan,
        layanan.harga,
        booking.tanggal_booking,
        booking.status,
        pembayaran.tanggal_bayar,
        pembayaran.jumlah,
        pembayaran.status AS status_pembayaran
     FROM booking
     JOIN pemilik
        ON booking.id_pemilik = pemilik.id_pemilik
     JOIN hewan
        ON booking.id_hewan = hewan.id_hewan
     JOIN layanan
        ON booking.id_layanan = layanan.id_layanan
     LEFT JOIN pembayaran
        ON booking.id_booking = pembayaran.id_booking
     ORDER BY booking.id_booking DESC"
);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Laporan Pet Care</title>

    <link rel="stylesheet"
          href="../src/style.css">

    <style>

        @media print {

            .menu,
            .tombol-cetak {
                display: none;
            }

            body {
                background: white;
            }

            .wrap {
                width: 100%;
            }

        }

    </style>

</head>

<body>

<div class="wrap">

    <h1 class="judul">
        🐾 LAPORAN PET CARE
    </h1>

    <p>
        Laporan Booking dan Pembayaran
    </p>

    <br>

    <!-- MENU -->

    <div class="menu">

        <a href="index.php">
            🏠 Dashboard
        </a>

        &nbsp;&nbsp;

        <a href="pemilik.php">
            👤 Pemilik
        </a>

        &nbsp;&nbsp;

        <a href="hewan.php">
            🐶 Hewan
        </a>

        &nbsp;&nbsp;

        <a href="layanan.php">
            ✂️ Layanan
        </a>

        &nbsp;&nbsp;

        <a href="booking.php">
            📅 Booking
        </a>

        &nbsp;&nbsp;

        <a href="perawatan.php">
            🩺 Perawatan
        </a>

        &nbsp;&nbsp;

        <a href="pembayaran.php">
            💰 Pembayaran
        </a>

        &nbsp;&nbsp;

        <a href="laporan.php">
            📊 Laporan
        </a>

        &nbsp;&nbsp;

        <a href="../logout.php">
            🚪 Logout
        </a>

    </div>

    <br>

    <!-- TOMBOL CETAK -->

    <div class="tombol-cetak">

        <button onclick="window.print()">
            🖨️ Cetak Laporan
        </button>

    </div>

    <br>

    <!-- JUDUL CETAK -->

    <div align="center">

        <h2>
            LAPORAN DATA BOOKING PET CARE
        </h2>

        <p>
            Dicetak pada:
            <?php echo date('d-m-Y'); ?>
        </p>

    </div>

    <br>

    <!-- TABEL -->

    <table border="1"
           cellpadding="8"
           cellspacing="0"
           width="100%">

        <tr>

            <th>No</th>

            <th>Nama Pemilik</th>

            <th>Nama Hewan</th>

            <th>Jenis</th>

            <th>Layanan</th>

            <th>Harga</th>

            <th>Tanggal Booking</th>

            <th>Status Booking</th>

            <th>Tanggal Bayar</th>

            <th>Jumlah Bayar</th>

            <th>Status Bayar</th>

        </tr>

        <?php

        $no = 1;
        $total = 0;

        if (mysqli_num_rows($query) > 0) {

            while ($data = mysqli_fetch_assoc($query)) {

                $jumlah_bayar = $data['jumlah'] ?? 0;

                $total += $jumlah_bayar;

        ?>

        <tr>

            <td>
                <?php echo $no++; ?>
            </td>

            <td>
                <?php echo $data['nama_pemilik']; ?>
            </td>

            <td>
                <?php echo $data['nama_hewan']; ?>
            </td>

            <td>
                <?php echo $data['jenis']; ?>
            </td>

            <td>
                <?php echo $data['nama_layanan']; ?>
            </td>

            <td>
                Rp
                <?php
                echo number_format(
                    $data['harga'],
                    0,
                    ',',
                    '.'
                );
                ?>
            </td>

            <td>
                <?php echo $data['tanggal_booking']; ?>
            </td>

            <td>
                <?php echo $data['status']; ?>
            </td>

            <td>
                <?php

                if (!empty($data['tanggal_bayar'])) {

                    echo $data['tanggal_bayar'];

                } else {

                    echo "-";

                }

                ?>
            </td>

            <td>

                <?php

                if ($data['jumlah'] !== null) {

                    echo "Rp " .
                    number_format(
                        $data['jumlah'],
                        0,
                        ',',
                        '.'
                    );

                } else {

                    echo "-";

                }

                ?>

            </td>

            <td>

                <?php

                if (!empty($data['status_pembayaran'])) {

                    echo $data['status_pembayaran'];

                } else {

                    echo "Belum Bayar";

                }

                ?>

            </td>

        </tr>

        <?php

            }

        } else {

        ?>

        <tr>

            <td colspan="11"
                align="center">

                Belum ada data laporan.

            </td>

        </tr>

        <?php

        }

        ?>

        <tr>

            <td colspan="9"
                align="right">

                <b>
                    TOTAL PEMBAYARAN
                </b>

            </td>

            <td colspan="2">

                <b>
                    Rp
                    <?php
                    echo number_format(
                        $total,
                        0,
                        ',',
                        '.'
                    );
                    ?>
                </b>

            </td>

        </tr>

    </table>

    <br><br>

    <div align="right">

        <p>
            Banjarbaru,
            <?php echo date('d-m-Y'); ?>
        </p>

        <br><br><br>

        <p>
            <b>
                Admin Pet Care
            </b>
        </p>

    </div>

</div>

</body>

</html>