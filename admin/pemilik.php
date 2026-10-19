<?php

session_start();

if (!isset($_SESSION['id_user']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

include "../src/koneksi.php";


// ==========================
// TAMBAH DATA PEMILIK
// ==========================

if (isset($_POST['tambah'])) {

    $id_user = $_POST['id_user'];
    $nama_pemilik = $_POST['nama_pemilik'];
    $no_hp = $_POST['no_hp'];
    $alamat = $_POST['alamat'];

    mysqli_query(
        $koneksi,
        "INSERT INTO pemilik
        (id_user, nama_pemilik, no_hp, alamat)
        VALUES
        ('$id_user', '$nama_pemilik', '$no_hp', '$alamat')"
    );

    header("Location: pemilik.php");
    exit;
}


// ==========================
// HAPUS DATA
// ==========================

if (isset($_GET['hapus'])) {

    $id_pemilik = $_GET['hapus'];

    mysqli_query(
        $koneksi,
        "DELETE FROM pemilik
         WHERE id_pemilik='$id_pemilik'"
    );

    header("Location: pemilik.php");
    exit;
}


// ==========================
// EDIT DATA
// ==========================

if (isset($_POST['edit'])) {

    $id_pemilik = $_POST['id_pemilik'];
    $id_user = $_POST['id_user'];
    $nama_pemilik = $_POST['nama_pemilik'];
    $no_hp = $_POST['no_hp'];
    $alamat = $_POST['alamat'];

    mysqli_query(
        $koneksi,
        "UPDATE pemilik SET
            id_user='$id_user',
            nama_pemilik='$nama_pemilik',
            no_hp='$no_hp',
            alamat='$alamat'
         WHERE id_pemilik='$id_pemilik'"
    );

    header("Location: pemilik.php");
    exit;
}


// ==========================
// AMBIL DATA UNTUK EDIT
// ==========================

$data_edit = null;

if (isset($_GET['edit'])) {

    $id_edit = $_GET['edit'];

    $query_edit = mysqli_query(
        $koneksi,
        "SELECT * FROM pemilik
         WHERE id_pemilik='$id_edit'"
    );

    $data_edit = mysqli_fetch_assoc($query_edit);
}


// ==========================
// AMBIL DATA USER
// ==========================

$query_user = mysqli_query(
    $koneksi,
    "SELECT * FROM user
     WHERE role='pelanggan'
     ORDER BY username ASC"
);


// ==========================
// TAMPIL DATA PEMILIK
// ==========================

$query_pemilik = mysqli_query(
    $koneksi,
    "SELECT
        pemilik.*,
        user.username
     FROM pemilik
     LEFT JOIN user
        ON pemilik.id_user = user.id_user
     ORDER BY pemilik.id_pemilik DESC"
);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Data Pemilik - Pet Care</title>

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
        }

        .box {
            background: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        }

        label {
            display: block;
            margin-top: 12px;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #F0C9D2;
            border-radius: 8px;
            background: #FFF0F3;
        }

        textarea {
            resize: vertical;
            height: 80px;
        }

        button {
            padding: 10px 18px;
            border: none;
            border-radius: 8px;
            color: white;
            cursor: pointer;
            margin-top: 15px;
            font-size: 15px;
        }

        .tambah {
            background: #D96C82;
        }

        .tambah:hover {
            background: #C95B71;
        }

        .edit {
            background: #E7A84B;
        }

        .hapus {
            background: #D9536F;
        }

        .batal {
            display: inline-block;
            padding: 10px 18px;
            background: #888;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin-left: 5px;
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

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
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

        .aksi {
            display: inline-block;
            padding: 7px 12px;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            margin: 2px;
        }

    </style>

</head>

<body>

<div class="container">

    <a href="index.php" class="kembali">
        ← Kembali ke Dashboard
    </a>

    <h1 class="judul">
        👤 Data Pemilik / Pelanggan
    </h1>


    <!-- ========================== -->
    <!-- FORM TAMBAH / EDIT -->
    <!-- ========================== -->

    <div class="box">

        <?php if ($data_edit) { ?>

            <h2>✏️ Edit Data Pemilik</h2>

            <form method="POST">

                <input
                    type="hidden"
                    name="id_pemilik"
                    value="<?php echo $data_edit['id_pemilik']; ?>"
                >

                <label>Username Pelanggan</label>

                <select name="id_user" required>

                    <option value="">
                        -- Pilih Pelanggan --
                    </option>

                    <?php

                    mysqli_data_seek($query_user, 0);

                    while ($user = mysqli_fetch_assoc($query_user)) {

                    ?>

                        <option
                            value="<?php echo $user['id_user']; ?>"
                            <?php
                            if ($user['id_user'] == $data_edit['id_user']) {
                                echo "selected";
                            }
                            ?>
                        >

                            <?php echo $user['username']; ?>

                        </option>

                    <?php } ?>

                </select>


                <label>Nama Pemilik</label>

                <input
                    type="text"
                    name="nama_pemilik"
                    value="<?php echo $data_edit['nama_pemilik']; ?>"
                    required
                >


                <label>No. HP</label>

                <input
                    type="text"
                    name="no_hp"
                    value="<?php echo $data_edit['no_hp']; ?>"
                    required
                >


                <label>Alamat</label>

                <textarea
                    name="alamat"
                    required
                ><?php echo $data_edit['alamat']; ?></textarea>


                <button
                    type="submit"
                    name="edit"
                    class="tambah">

                    💾 Simpan Perubahan

                </button>

                <a
                    href="pemilik.php"
                    class="batal">

                    Batal

                </a>

            </form>

        <?php } else { ?>

            <h2>➕ Tambah Data Pemilik</h2>

            <form method="POST">

                <label>Username Pelanggan</label>

                <select name="id_user" required>

                    <option value="">
                        -- Pilih Pelanggan --
                    </option>

                    <?php

                    while ($user = mysqli_fetch_assoc($query_user)) {

                    ?>

                        <option value="<?php echo $user['id_user']; ?>">

                            <?php echo $user['username']; ?>

                        </option>

                    <?php } ?>

                </select>


                <label>Nama Pemilik</label>

                <input
                    type="text"
                    name="nama_pemilik"
                    placeholder="Masukkan nama pemilik"
                    required
                >


                <label>No. HP</label>

                <input
                    type="text"
                    name="no_hp"
                    placeholder="08xxxxxxxxxx"
                    required
                >


                <label>Alamat</label>

                <textarea
                    name="alamat"
                    placeholder="Masukkan alamat"
                    required
                ></textarea>


                <button
                    type="submit"
                    name="tambah"
                    class="tambah">

                    ➕ Tambah Data

                </button>

            </form>

        <?php } ?>

    </div>


    <!-- ========================== -->
    <!-- TABEL DATA PEMILIK -->
    <!-- ========================== -->

    <div class="box">

        <h2>📋 Daftar Pemilik</h2>

        <table>

            <tr>

                <th>No</th>
                <th>Username</th>
                <th>Nama Pemilik</th>
                <th>No. HP</th>
                <th>Alamat</th>
                <th>Aksi</th>

            </tr>

            <?php

            $no = 1;

            if (mysqli_num_rows($query_pemilik) > 0) {

                while ($data = mysqli_fetch_assoc($query_pemilik)) {

            ?>

            <tr>

                <td>
                    <?php echo $no++; ?>
                </td>

                <td>
                    <?php echo $data['username']; ?>
                </td>

                <td>
                    <?php echo $data['nama_pemilik']; ?>
                </td>

                <td>
                    <?php echo $data['no_hp']; ?>
                </td>

                <td>
                    <?php echo $data['alamat']; ?>
                </td>

                <td>

                    <a
                        href="pemilik.php?edit=<?php echo $data['id_pemilik']; ?>"
                        class="aksi edit">

                        ✏️ Edit

                    </a>

                    <a
                        href="pemilik.php?hapus=<?php echo $data['id_pemilik']; ?>"
                        class="aksi hapus"
                        onclick="return confirm('Yakin ingin menghapus data pemilik ini?');">

                        🗑️ Hapus

                    </a>

                </td>

            </tr>

            <?php

                }

            } else {

            ?>

            <tr>

                <td colspan="6">
                    Belum ada data pemilik.
                </td>

            </tr>

            <?php } ?>

        </table>

    </div>

</div>

</body>

</html>