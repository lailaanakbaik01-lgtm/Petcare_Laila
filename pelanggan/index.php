<?php

session_start();

if (!isset($_SESSION['id_user']) || $_SESSION['role'] != 'pelanggan') {
    header("Location: ../src/login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Dashboard Pelanggan - Pet Care</title>

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
            max-width: 1000px;
            margin: 30px auto;
        }

        .welcome {
            background: white;
            padding: 25px;
            border-radius: 18px;
            box-shadow: 0 5px 20px rgba(217, 108, 130, 0.15);
        }

        .welcome h2 {
            color: #D96C82;
            margin-top: 0;
        }

        .menu {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 25px;
        }

        .menu a {
            background: white;
            padding: 30px 20px;
            text-align: center;
            text-decoration: none;
            color: #60454D;
            border-radius: 18px;
            border: 2px solid #F7C6D0;
            font-weight: bold;
            transition: 0.3s;
        }

        .menu a:hover {
            background: #FFF0F3;
            transform: translateY(-3px);
        }

        .icon {
            font-size: 40px;
            margin-bottom: 10px;
        }

        .logout {
            display: inline-block;
            margin-top: 25px;
            background: #D96C82;
            color: white;
            padding: 11px 22px;
            border-radius: 10px;
            text-decoration: none;
        }

        .logout:hover {
            background: #C95770;
        }

    </style>

</head>

<body>


    <div class="header">

        <h1>🐾 PET CARE 🐾</h1>

        <p>Dashboard Pelanggan</p>

    </div>


    <div class="container">


        <div class="welcome">

            <h2>
                Halo,
                <?php echo $_SESSION['nama_lengkap']; ?>! 🩷
            </h2>

            <p>
                Selamat datang di sistem Pet Care.
                Silakan pilih menu yang ingin kamu gunakan.
            </p>

        </div>


        <div class="menu">


            <a href="hewan.php">

                <div class="icon">🐶</div>

                Data Hewan

            </a>


            <a href="booking.php">

                <div class="icon">📅</div>

                Booking Perawatan

            </a>


            <a href="status.php">

                <div class="icon">🐾</div>

                Status Perawatan

            </a>


            <a href="riwayat.php">

                <div class="icon">📋</div>

                Riwayat Perawatan

            </a>


        </div>


        <a href="../logout.php" class="logout">
            Logout
        </a>


    </div>


</body>

</html>