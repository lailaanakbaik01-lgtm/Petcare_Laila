<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login Pet Care</title>

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Cherry+Bomb+One&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #FFF5F7;
            min-height: 100vh;

            display: flex;
            justify-content: center;
            align-items: center;

            position: relative;
            overflow: hidden;
        }

        /* Dekorasi paw */

        .paw {
            position: absolute;
            font-size: 55px;
            opacity: 0.18;
        }

        .p1 {
            top: 8%;
            left: 10%;
            transform: rotate(-20deg);
        }

        .p2 {
            top: 15%;
            right: 12%;
            transform: rotate(25deg);
        }

        .p3 {
            bottom: 12%;
            left: 12%;
            transform: rotate(15deg);
        }

        .p4 {
            bottom: 8%;
            right: 10%;
            transform: rotate(-25deg);
        }

        .p5 {
            top: 45%;
            left: 3%;
            font-size: 40px;
        }

        .p6 {
            top: 50%;
            right: 4%;
            font-size: 40px;
        }


        /* Card Login */

        .login-card {
            width: 380px;
            max-width: 90%;

            background: #FFFFFF;

            padding: 35px 35px 30px;

            border-radius: 25px;

            box-shadow:
                0 15px 40px rgba(217, 108, 130, 0.20);

            text-align: center;

            position: relative;
            z-index: 10;

            border: 3px solid #F7C6D0;
        }


        /* Icon */

        .icon {
            width: 90px;
            height: 90px;

            margin: 0 auto 15px;

            border-radius: 50%;

            background: #FFF0F3;

            display: flex;
            justify-content: center;
            align-items: center;

            font-size: 50px;

            border: 4px solid #F7C6D0;
        }


        /* Judul */

        h1 {
            font-family: 'Cherry Bomb One', cursive;

            color: #D96C82;

            font-size: 38px;

            margin-bottom: 3px;
        }

        .subtitle {
            color: #60454D;

            font-size: 14px;

            margin-bottom: 25px;
        }


        /* Input */

        .input-group {
            text-align: left;

            margin-bottom: 18px;
        }

        .input-group label {
            display: block;

            color: #60454D;

            font-size: 14px;

            font-weight: 600;

            margin-bottom: 7px;
        }

        .input-group input {
            width: 100%;

            padding: 13px 15px;

            border: 2px solid #F7C6D0;

            border-radius: 12px;

            background: #FFF5F7;

            color: #60454D;

            font-family: 'Poppins', sans-serif;

            outline: none;

            transition: 0.3s;
        }

        .input-group input:focus {
            border-color: #D96C82;

            background: #FFFFFF;

            box-shadow: 0 0 0 3px rgba(217, 108, 130, 0.10);
        }


        /* Tombol */

        .btn-login {
            width: 100%;

            padding: 13px;

            border: none;

            border-radius: 12px;

            background: #D96C82;

            color: white;

            font-family: 'Poppins', sans-serif;

            font-size: 15px;

            font-weight: 700;

            cursor: pointer;

            transition: 0.3s;

            margin-top: 5px;
        }

        .btn-login:hover {
            background: #C95770;

            transform: translateY(-2px);

            box-shadow: 0 8px 18px rgba(217, 108, 130, 0.25);
        }


        /* Pesan gagal */

        .error {
            background: #FFE1E7;

            color: #B8425A;

            padding: 10px;

            border-radius: 10px;

            font-size: 13px;

            margin-bottom: 18px;

            font-weight: 500;
        }


        /* Footer */

        .footer {
            margin-top: 22px;

            color: #9A727D;

            font-size: 12px;
        }

        .footer span {
            color: #D96C82;
        }


        /* Responsive */

        @media (max-width: 500px) {

            .login-card {
                padding: 30px 25px 25px;
            }

            h1 {
                font-size: 32px;
            }

            .icon {
                width: 80px;
                height: 80px;
                font-size: 43px;
            }

            .paw {
                font-size: 40px;
            }
        }

    </style>

</head>


<body>


    <!-- Dekorasi -->

    <div class="paw p1">🐾</div>
    <div class="paw p2">🐾</div>
    <div class="paw p3">🐾</div>
    <div class="paw p4">🐾</div>
    <div class="paw p5">🐾</div>
    <div class="paw p6">🐾</div>


    <!-- Login -->

    <div class="login-card">

        <div class="icon">
            🐶
        </div>


        <h1>PET CARE</h1>

        <p class="subtitle">
            Sistem Informasi Perawatan Hewan
        </p>


        <?php

        if (isset($_GET['pesan']) && $_GET['pesan'] == 'gagal') {

            echo '<div class="error">
                    Username atau password salah!
                  </div>';

        }

        ?>


        <form action="proses_login.php" method="POST">


            <div class="input-group">

                <label for="username">
                    Username
                </label>

                <input
                    type="text"
                    id="username"
                    name="username"
                    placeholder="Masukkan username"
                    required
                >

            </div>


            <div class="input-group">

                <label for="password">
                    Password
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Masukkan password"
                    required
                >

            </div>


            <button
                type="submit"
                class="btn-login"
            >
                🐾 LOGIN
            </button>


        </form>


        <div class="footer">
            Selamat datang di <span>Pet Care</span> 🩷
        </div>

    </div>


</body>

</html>