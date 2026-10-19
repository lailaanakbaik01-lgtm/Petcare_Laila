<?php
session_start();

if (!isset($_SESSION['id_user']) || !isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

include "../src/koneksi.php";

// Menghitung total pemilik
$query_pemilik = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM pemilik");
$total_pemilik = mysqli_fetch_assoc($query_pemilik)['total'];

// Menghitung total hewan
$query_hewan = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM hewan");
$total_hewan = mysqli_fetch_assoc($query_hewan)['total'];

// Menghitung total layanan
$query_layanan = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM layanan");
$total_layanan = mysqli_fetch_assoc($query_layanan)['total'];

// Menghitung total booking
$query_booking = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM booking");
$total_booking = mysqli_fetch_assoc($query_booking)['total'];

// Mengambil jumlah booking berdasarkan status
$query_status = mysqli_query($koneksi, "
    SELECT status, COUNT(*) AS jumlah
    FROM booking
    GROUP BY status
");

// Menyiapkan status agar kategori tetap muncul meskipun jumlahnya nol
$status_data = [
    'Menunggu' => 0,
    'Diproses' => 0,
    'Selesai' => 0,
    'Dibatalkan' => 0
];

while ($row = mysqli_fetch_assoc($query_status)) {
    $status = $row['status'];

    if (array_key_exists($status, $status_data)) {
        $status_data[$status] = (int) $row['jumlah'];
    }
}

$label_status = array_keys($status_data);
$data_status = array_values($status_data);
$total_booking_status = array_sum($data_status);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Admin - Pet Care</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #FFF5F7;
            color: #60454D;
        }

        .header {
            background: #D96C82;
            color: white;
            padding: 25px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
        }

        .container {
            width: 90%;
            max-width: 1100px;
            margin: 30px auto;
        }

        .welcome {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(217, 108, 130, 0.15);
        }

        .ringkasan {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
            margin-top: 25px;
        }

        .kartu {
            background: white;
            padding: 22px;
            border-radius: 15px;
            border-left: 5px solid #D96C82;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        }

        .kartu .ikon {
            font-size: 28px;
        }

        .kartu h2 {
            color: #D96C82;
            margin: 12px 0 5px;
            font-size: 28px;
        }

        .kartu p {
            margin: 0;
            color: #777;
        }

        .menu {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
            margin-top: 25px;
        }

        .menu a {
            background: white;
            padding: 25px;
            text-align: center;
            text-decoration: none;
            color: #60454D;
            border-radius: 15px;
            border: 2px solid #F7C6D0;
            font-weight: bold;
            transition: 0.2s;
        }

        .menu a:hover {
            background: #FFE8ED;
            transform: translateY(-3px);
        }

        .diagram {
            background: white;
            padding: 25px;
            border-radius: 15px;
            margin-top: 25px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        }

        .diagram h2 {
            margin-top: 0;
            color: #D96C82;
        }

        .diagram p {
            color: #777;
        }

        .wadah-diagram {
            width: 100%;
            max-width: 450px;
            height: 350px;
            margin: 20px auto 0;
        }

        .logout {
            display: inline-block;
            margin-top: 25px;
            background: #D96C82;
            color: white;
            padding: 12px 22px;
            border-radius: 10px;
            text-decoration: none;
        }

        .logout:hover {
            background: #C95B71;
        }

        @media (max-width: 600px) {
            .container {
                width: 94%;
            }

            .wadah-diagram {
                height: 300px;
            }
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>🐾 PET CARE 🐾</h1>
        <p>Dashboard Admin</p>
    </div>

    <div class="container">

        <!-- Sambutan -->
        <div class="welcome">
            <h2>
                Selamat Datang,
                <?= htmlspecialchars($_SESSION['nama_lengkap'] ?? 'Admin'); ?> 🩷
            </h2>

            <p>Kamu login sebagai <b>Admin</b>.</p>
            <p>Kelola data layanan perawatan hewan melalui dashboard ini.</p>
        </div>

        <!-- Ringkasan Data -->
        <div class="ringkasan">

            <div class="kartu">
                <div class="ikon">👤</div>
                <h2><?= number_format((int) $total_pemilik); ?></h2>
                <p>Total Pemilik</p>
            </div>

            <div class="kartu">
                <div class="ikon">🐶</div>
                <h2><?= number_format((int) $total_hewan); ?></h2>
                <p>Total Hewan</p>
            </div>

            <div class="kartu">
                <div class="ikon">🛁</div>
                <h2><?= number_format((int) $total_layanan); ?></h2>
                <p>Total Layanan</p>
            </div>

            <div class="kartu">
                <div class="ikon">📅</div>
                <h2><?= number_format((int) $total_booking); ?></h2>
                <p>Total Booking</p>
            </div>

        </div>

        <!-- Diagram Pie -->
        <div class="diagram">
            <h2>📊 Diagram Pie Status Booking</h2>

            <p>
                Perbandingan jumlah booking berdasarkan status
                perawatan hewan.
            </p>

            <?php if ($total_booking_status > 0) : ?>

                <div class="wadah-diagram">
                    <canvas id="diagramBooking"></canvas>
                </div>

            <?php else : ?>

                <p style="text-align: center; padding: 35px 0;">
                    Belum ada data booking untuk ditampilkan.
                </p>

            <?php endif; ?>
        </div>

        <!-- Menu -->
        <div class="menu">

            <a href="pemilik.php">
                👤<br><br>
                Data Pemilik
            </a>

            <a href="hewan.php">
                🐶<br><br>
                Data Hewan
            </a>

            <a href="layanan.php">
                ✂️<br><br>
                Data Layanan
            </a>

            <a href="booking.php">
                📅<br><br>
                Data Booking
            </a>

            <a href="perawatan.php">
                🛁<br><br>
                Data Perawatan
            </a>

            <a href="pembayaran.php">
                💳<br><br>
                Data Pembayaran
            </a>

        </div>

        <a class="logout" href="../logout.php">
            Logout
        </a>

    </div>

    <?php if ($total_booking_status > 0) : ?>

        <!-- Library Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            const labelBooking = <?= json_encode(
                $label_status,
                JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP
            ); ?>;

            const dataBooking = <?= json_encode($data_status); ?>;

            const canvasBooking = document.getElementById('diagramBooking');

            new Chart(canvasBooking, {
                type: 'pie',

                data: {
                    labels: labelBooking,

                    datasets: [{
                        label: 'Jumlah Booking',

                        data: dataBooking,

                        backgroundColor: [
                            '#F48FB1',
                            '#CE93D8',
                            '#81C784',
                            '#FFB74D'
                        ],

                        borderColor: '#FFFFFF',
                        borderWidth: 3
                    }]
                },

                options: {
                    responsive: true,
                    maintainAspectRatio: false,

                    plugins: {
                        legend: {
                            position: 'bottom',

                            labels: {
                                padding: 18,
                                font: {
                                    size: 13
                                }
                            }
                        },

                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': ' +
                                        context.raw + ' booking';
                                }
                            }
                        }
                    }
                }
            });
        </script>

    <?php endif; ?>

</body>
</html>