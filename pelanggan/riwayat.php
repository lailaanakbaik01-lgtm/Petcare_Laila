<?php

session_start();

if (!isset($_SESSION['id_user']) || $_SESSION['role'] != 'pelanggan') {
    header("Location: ../login.php");
    exit;
}

include "../src/koneksi.php";

$id_user = $_SESSION['id_user'];

// Ambil data pemilik
$query_pemilik = mysqli_query(
    $koneksi,
    "SELECT * FROM pemilik
     WHERE id_user='$id_user'"
);

$data_pemilik = mysqli_fetch_assoc($query_pemilik);

if (!$data_pemilik) {
    die("Data pemilik belum tersedia.");
}

$id_pemilik = $data_pemilik['id_pemilik'];

// Ambil riwayat booking
$query_riwayat = mysqli_query(
    $koneksi,
    "SELECT
        booking.*,
        hewan.nama_hewan,
        hewan.jenis,
        layanan.nama_layanan,
        layanan.harga
     FROM booking
     INNER JOIN hewan
        ON booking.id_hewan = hewan.id_hewan
     INNER JOIN layanan
        ON booking.id_layanan = layanan.id_layanan
     WHERE booking.id_pemilik='$id_pemilik'
     ORDER BY booking.tanggal_booking DESC"
);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Riwayat Booking - Pet Care</title>

    <link rel="stylesheet"
          href="../src/style.css">

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
            margin-bottom: 25px;
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

        .kembali:hover {
            background: #C95B71;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
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

        .status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: bold;
        }

        .menunggu {
            background: #FFF0B8;
            color: #8A6D00;
        }

        .diproses {
            background: #DDEBFF;
            color: #3565A8;
        }

        .selesai {
            background: #DDF5E4;
            color: #287A43;
        }

        .dibatalkan {
            background: #FFDDE3;
            color: #B13B50;
        }

    </style>

</head>

<body>

<div class="container">

    <a href="index.php" class="kembali">
        ← Kembali ke Dashboard
    </a>

    <h1 class="judul">
        🐾 Riwayat Booking Saya 🐾
    </h1>

    <table>

        <tr>
            <th>No</th>
            <th>Tanggal Booking</th>
            <th>Nama Hewan</th>
            <th>Jenis</th>
            <th>Layanan</th>
            <th>Harga</th>
            <th>Status</th>
        </tr>

        <?php

        $no = 1;

        if (mysqli_num_rows($query_riwayat) > 0) {

            while ($data = mysqli_fetch_assoc($query_riwayat)) {

                $status = strtolower($data['status']);

                $class_status = '';

                if ($status == 'menunggu') {
                    $class_status = 'menunggu';
                } elseif ($status == 'diproses') {
                    $class_status = 'diproses';
                } elseif ($status == 'selesai') {
                    $class_status = 'selesai';
                } elseif ($status == 'dibatalkan') {
                    $class_status = 'dibatalkan';
                }

        ?>

        <tr>

            <td>
                <?php echo $no++; ?>
            </td>

            <td>
                <?php echo $data['tanggal_booking']; ?>
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
                Rp <?php echo number_format(
                    $data['harga'],
                    0,
                    ',',
                    '.'
                ); ?>
            </td>

            <td>

                <span class="status <?php echo $class_status; ?>">

                    <?php echo $data['status']; ?>

                </span>

            </td>

        </tr>

        <?php

            }

        } else {

        ?>

        <tr>

            <td colspan="7">
                🐶 Belum ada riwayat booking.
            </td>

        </tr>

        <?php } ?>

    </table>

</div>

</body>

</html>