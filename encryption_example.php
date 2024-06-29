<?php
session_start();

include('db.php');
include('functions.php');

// Data yang akan dienkripsi
$original_string = "Sensitive data";

// Menghasilkan IV
$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('AES-128-CTR'));

// Melakukan enkripsi
$encrypted_string = str_openssl_enc($original_string, $iv);

// Menyimpan data terenkripsi ke dalam database
$iv_hex = bin2hex($iv);
$query = "INSERT INTO encrypted_data (data, iv) VALUES ('$encrypted_string', '$iv_hex')";
mysqli_query($con, $query);

// Mengambil data dari database
$result = mysqli_query($con, "SELECT * FROM encrypted_data ORDER BY id DESC LIMIT 1");
$row = mysqli_fetch_assoc($result);

// Mendekripsi data yang diambil
$iv_from_db = hex2bin($row['iv']);
$decrypted_string = str_openssl_dec($row['data'], $iv_from_db);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encryption Example</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Encryption Example</h2>
        <table class="table table-bordered">
            <tr>
                <th>Original String</th>
                <td><?php echo htmlspecialchars($original_string); ?></td>
            </tr>
            <tr>
                <th>Encrypted String</th>
                <td><?php echo htmlspecialchars($encrypted_string); ?></td>
            </tr>
            <tr>
                <th>Decrypted String</th>
                <td><?php echo htmlspecialchars($decrypted_string); ?></td>
            </tr>
            <tr>
                <th>IV (hex)</th>
                <td><?php echo htmlspecialchars($iv_hex); ?></td>
            </tr>
            <tr>
                <th>Key</th>
                <td><?php echo htmlspecialchars($key); ?></td>
            </tr>
        </table>
    </div>
    
    <!-- Bootstrap JS and dependencies (optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
