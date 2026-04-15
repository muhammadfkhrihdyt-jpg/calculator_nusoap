<?php
// Memanggil library NuSOAP via Vendor (Autoload)
require_once '../../vendor/autoload.php';

$hasil = null;
$error = null;

// Logika ini HANYA berjalan jika tombol operasi diklik (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['operasi'])) {
    
    $a = (int)$_POST['angka1'];
    $b = (int)$_POST['angka2'];
    $operasi = $_POST['operasi']; // berisi: tambah, kurang, kali, atau bagi

    // URL Server SOAP kamu
    $url = "http://localhost/simple-app-nusoap-main/web/server/api.php?wsdl";
    
    // Inisialisasi Client
    $client = new nusoap_client($url, true);

    // Cek apakah ada error pada koneksi ke WSDL
    $err = $client->getError();
    if ($err) {
        $error = "Gagal terhubung ke Server: " . $err;
    } else {
        // Memanggil fungsi di server sesuai dengan value tombol yang diklik
        // Array parameter harus sesuai dengan nama 'a' dan 'b' di server.php
        $result = $client->call($operasi, array('a' => $a, 'b' => $b));

        // Cek jika ada fault (kesalahan internal server)
        if ($client->fault) {
            $error = "Server Fault: " . print_r($result, true);
        } else {
            // Cek jika ada error saat eksekusi
            $err = $client->getError();
            if ($err) {
                $error = "Error: " . $err;
            } else {
                // Berhasil, simpan ke variabel hasil
                $nama_operasi = ucfirst($operasi);
                $hasil = "<strong>$nama_operasi</strong> antara $a dan $b adalah <strong>$result</strong>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NuSOAP Calculator Client</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            min-height: 100vh; 
            margin: 0;
            background-color: #f0f2f5; 
        }
        .card { 
            background: white; 
            padding: 25px; 
            border-radius: 12px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.1); 
            width: 350px; 
        }
        h2 { text-align: center; color: #333; margin-bottom: 20px; }
        label { font-size: 14px; color: #666; }
        input { 
            width: 100%; 
            padding: 12px; 
            margin: 8px 0 15px 0; 
            box-sizing: border-box; 
            border: 1px solid #ddd; 
            border-radius: 6px; 
            font-size: 16px;
        }
        .btn-group { 
            display: grid; 
            grid-template-columns: 1fr 1fr; 
            gap: 10px; 
        }
        button { 
            padding: 10px; 
            background-color: #007bff; 
            color: white; 
            border: none; 
            border-radius: 6px; 
            cursor: pointer; 
            font-weight: bold;
            transition: 0.2s;
        }
        button:hover { background-color: #0056b3; }
        .btn-kali { background-color: #28a745; }
        .btn-kali:hover { background-color: #218838; }
        .btn-bagi { background-color: #ffc107; color: #333; }
        .btn-bagi:hover { background-color: #e0a800; }
        .btn-kurang { background-color: #dc3545; }
        .btn-kurang:hover { background-color: #c82333; }

        .result { 
            margin-top: 20px; 
            padding: 15px; 
            background-color: #d4edda; 
            border-left: 5px solid #28a745; 
            color: #155724;
            border-radius: 4px;
        }
        .error { 
            margin-top: 20px; 
            padding: 15px; 
            background-color: #f8d7da; 
            border-left: 5px solid #dc3545; 
            color: #721c24;
            border-radius: 4px;
            word-break: break-all;
        }
    </style>
</head>
<body>

<div class="card">
    <h2>Kalkulator SOAP</h2>
    <form method="POST">
        <label>Angka Pertama</label>
        <input type="number" name="angka1" placeholder="0" required value="<?= isset($_POST['angka1']) ? $_POST['angka1'] : '' ?>">
        
        <label>Angka Kedua</label>
        <input type="number" name="angka2" placeholder="0" required value="<?= isset($_POST['angka2']) ? $_POST['angka2'] : '' ?>">
        
        <div class="btn-group">
            <button type="submit" name="operasi" value="tambah">Tambah (+)</button>
            <button type="submit" name="operasi" value="kurang" class="btn-kurang">Kurang (-)</button>
            <button type="submit" name="operasi" value="kali" class="btn-kali">Kali (x)</button>
            <button type="submit" name="operasi" value="bagi" class="btn-bagi">Bagi (/)</button>
        </div>
    </form>

    <?php if ($hasil): ?>
        <div class="result">
            <?= $hasil ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="error">
            <strong>Terjadi Kesalahan:</strong><br>
            <small><?= $error ?></small>
        </div>
    <?php endif; ?>
</div>

</body>
</html>