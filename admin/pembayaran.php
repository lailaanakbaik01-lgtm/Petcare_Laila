<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

include "../src/koneksi.php";

$pesan = "";

// TAMBAH PEMBAYARAN
if (isset($_POST['tambah'])) {
    $id_booking = (int) $_POST['id_booking'];
    $status = $_POST['status'];

    $stmt = mysqli_prepare(
        $koneksi,
        "INSERT INTO pembayaran (id_booking, status)
         VALUES (?, ?)"
    );

    mysqli_stmt_bind_param($stmt, "is", $id_booking, $status);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: pembayaran.php");
        exit;
    }

    $pesan = "Gagal menambahkan pembayaran: " .
             mysqli_stmt_error($stmt);
}

// EDIT PEMBAYARAN
if (isset($_POST['edit'])) {
    $id_pembayaran = (int) $_POST['id_pembayaran'];
    $id_booking = (int) $_POST['id_booking'];
    $status = $_POST['status'];

    $stmt = mysqli_prepare(
        $koneksi,
        "UPDATE pembayaran
         SET id_booking = ?, status = ?
         WHERE id_pembayaran = ?"
    );

    mysqli_stmt_bind_param(
        $stmt, "isi",
        $id_booking, $status, $id_pembayaran
    );

    if (mysqli_stmt_execute($stmt)) {
        header("Location: pembayaran.php");
        exit;
    }

    $pesan = "Gagal mengedit pembayaran: " .
             mysqli_stmt_error($stmt);
}

// HAPUS PEMBAYARAN
if (isset($_GET['hapus'])) {
    $id_pembayaran = (int) $_GET['hapus'];

    $stmt = mysqli_prepare(
        $koneksi,
        "DELETE FROM pembayaran
         WHERE id_pembayaran = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id_pembayaran);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: pembayaran.php");
        exit;
    }

    $pesan = "Gagal menghapus pembayaran: " .
             mysqli_stmt_error($stmt);
}

// DAFTAR BOOKING
$data_booking = mysqli_query(
    $koneksi,
    "SELECT
        b.id_booking,
        b.tanggal_booking,
        p.nama_pemilik,
        h.nama_hewan,
        l.nama_layanan
     FROM booking b
     LEFT JOIN pemilik p ON b.id_pemilik = p.id_pemilik
     LEFT JOIN hewan h ON b.id_hewan = h.id_hewan
     LEFT JOIN layanan l ON b.id_layanan = l.id_layanan
     ORDER BY b.id_booking DESC"
);

if (!$data_booking) {
    die("Query booking gagal: " . mysqli_error($koneksi));
}

// DAFTAR PEMBAYARAN
$data_pembayaran = mysqli_query(
    $koneksi,
    "SELECT
        pb.id_pembayaran,
        pb.id_booking,
        pb.status,
        b.tanggal_booking,
        p.nama_pemilik,
        h.nama_hewan,
        l.nama_layanan
     FROM pembayaran pb
     LEFT JOIN booking b ON pb.id_booking = b.id_booking
     LEFT JOIN pemilik p ON b.id_pemilik = p.id_pemilik
     LEFT JOIN hewan h ON b.id_hewan = h.id_hewan
     LEFT JOIN layanan l ON b.id_layanan = l.id_layanan
     ORDER BY pb.id_pembayaran DESC"
);

if (!$data_pembayaran) {
    die("Query pembayaran gagal: " . mysqli_error($koneksi));
}

// DATA YANG AKAN DIEDIT
$edit_data = null;

if (isset($_GET['edit'])) {
    $id_edit = (int) $_GET['edit'];

    $stmt = mysqli_prepare(
        $koneksi,
        "SELECT * FROM pembayaran WHERE id_pembayaran = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id_edit);
    mysqli_stmt_execute($stmt);

    $hasil_edit = mysqli_stmt_get_result($stmt);
    $edit_data = mysqli_fetch_assoc($hasil_edit);

    if (!$edit_data) {
        header("Location: pembayaran.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Pembayaran - Pet Care</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #ffeaf3;
            margin: 0;
            padding: 25px;
            color: #555;
        }

        .container {
            width: 95%;
            max-width: 1200px;
            margin: auto;
        }

        h1 {
            color: #d63384;
            text-align: center;
        }

        .box {
            background: white;
            padding: 22px;
            border-radius: 18px;
            margin-bottom: 25px;
            box-shadow: 0 5px 15px #00000010;
            overflow-x: auto;
        }

        label {
            display: block;
            margin-top: 12px;
            font-weight: bold;
        }

        select, button {
            padding: 11px;
            border-radius: 9px;
            border: 1px solid #ddd;
            margin-top: 6px;
        }

        select {
            width: 100%;
            box-sizing: border-box;
        }

        button {
            background: #ff69a4;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background: #d63384;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            text-align: center;
        }

        th {
            background: #ff69a4;
            color: white;
        }

        .kembali, .aksi {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 8px;
            text-decoration: none;
            margin: 2px;
        }

        .kembali {
            background: #d63384;
            color: white;
        }

        .edit {
            background: #ffc107;
            color: #333;
        }

        .hapus {
            background: #dc3545;
            color: white;
        }

        .pesan {
            background: #fff0f0;
            padding: 12px;
            border-radius: 8px;
            overflow-wrap: anywhere;
        }

        .status {
            background: #ffe0ed;
            color: #d63384;
            padding: 6px 10px;
            border-radius: 8px;
        }
    </style>
</head>

<body>
<div class="container">

    <a href="index.php" class="kembali">
        ← Kembali ke Dashboard
    </a>

    <h1>🐾 DATA PEMBAYARAN 🐾</h1>

    <?php if ($pesan != "") { ?>
        <p class="pesan">
            <?= htmlspecialchars($pesan); ?>
        </p>
    <?php } ?>

    <div class="box">
        <h2>
            <?= $edit_data ? "✏️ Edit Pembayaran" : "➕ Tambah Pembayaran"; ?>
        </h2>

        <form method="POST" action="pembayaran.php">

            <?php if ($edit_data) { ?>
                <input type="hidden"
                       name="id_pembayaran"
                       value="<?= (int) $edit_data['id_pembayaran']; ?>">
            <?php } ?>

            <label>Booking</label>

            <select name="id_booking" required>
                <option value="">-- Pilih Booking --</option>

                <?php while ($b = mysqli_fetch_assoc($data_booking)) { ?>
                    <option
                        value="<?= (int) $b['id_booking']; ?>"
                        <?= ($edit_data &&
                             $b['id_booking'] == $edit_data['id_booking'])
                             ? 'selected' : ''; ?>>

                        <?= htmlspecialchars(
                            ($b['nama_hewan'] ?? '-') . ' - ' .
                            ($b['nama_pemilik'] ?? '-') . ' - ' .
                            ($b['nama_layanan'] ?? '-') . ' - ' .
                            ($b['tanggal_booking'] ?? '-')
                        ); ?>
                    </option>
                <?php } ?>
            </select>

            <label>Status Pembayaran</label>

            <select name="status" required>
                <option value="">-- Pilih Status --</option>

                <option value="Belum Bayar"
                    <?= ($edit_data &&
                        $edit_data['status'] == 'Belum Bayar')
                        ? 'selected' : ''; ?>>
                    Belum Bayar
                </option>

                <option value="Lunas"
                    <?= ($edit_data &&
                        $edit_data['status'] == 'Lunas')
                        ? 'selected' : ''; ?>>
                    Lunas
                </option>
            </select>

            <br>

            <?php if ($edit_data) { ?>
                <button type="submit" name="edit">
                    Simpan Perubahan
                </button>

                <a href="pembayaran.php" class="kembali">
                    Batal
                </a>
            <?php } else { ?>
                <button type="submit" name="tambah">
                    Simpan Pembayaran
                </button>
            <?php } ?>

        </form>
    </div>

    <div class="box">
        <h2>📋 Daftar Pembayaran</h2>

        <table>
            <tr>
                <th>No</th>
                <th>Pemilik</th>
                <th>Hewan</th>
                <th>Layanan</th>
                <th>Tanggal Booking</th>
                <th>Status Pembayaran</th>
                <th>Aksi</th>
            </tr>

            <?php $no = 1; ?>

            <?php while ($data = mysqli_fetch_assoc($data_pembayaran)) { ?>
                <tr>
                    <td><?= $no++; ?></td>

                    <td>
                        <?= htmlspecialchars($data['nama_pemilik'] ?? '-'); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($data['nama_hewan'] ?? '-'); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($data['nama_layanan'] ?? '-'); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($data['tanggal_booking'] ?? '-'); ?>
                    </td>

                    <td>
                        <span class="status">
                            <?= htmlspecialchars($data['status']); ?>
                        </span>
                    </td>

                    <td>
                        <a class="aksi edit"
                           href="pembayaran.php?edit=<?= (int) $data['id_pembayaran']; ?>">
                            Edit
                        </a>

                        <a class="aksi hapus"
                           href="pembayaran.php?hapus=<?= (int) $data['id_pembayaran']; ?>"
                           onclick="return confirm('Yakin ingin menghapus pembayaran ini?')">
                            Hapus
                        </a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>

</div>
</body>
</html>