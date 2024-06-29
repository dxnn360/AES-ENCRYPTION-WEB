<?php
session_start();

include('db.php');
include('functions.php'); // Include the file with encryption/decryption functions

if(isset($_GET['id'])){
    $id = $_GET['id'];
    $result = mysqli_query($con, "SELECT * FROM form WHERE id = $id");
    $row = mysqli_fetch_assoc($result);
    $iv = hex2bin($row['iv']);
    $name = str_openssl_dec($row['name'], $iv);
    $email = str_openssl_dec($row['email'], $iv);
    $phone = str_openssl_dec($row['phone'], $iv);
    $tujuan_awal = str_openssl_dec($row['tujuan_awal'], $iv);
    $tujuan_akhir = str_openssl_dec($row['tujuan_akhir'], $iv);
    $nama_bus = str_openssl_dec($row['nama_bus'], $iv);
}

if(isset($_POST['submit'])){
    $iv = openssl_random_pseudo_bytes(16);
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $tujuan_awal = mysqli_real_escape_string($con, $_POST['tujuan_awal']);
    $tujuan_akhir = mysqli_real_escape_string($con, $_POST['tujuan_akhir']);
    $nama_bus = mysqli_real_escape_string($con, $_POST['nama_bus']);

    $name_encrypted = str_openssl_enc($name, $iv);
    $email_encrypted = str_openssl_enc($email, $iv);
    $phone_encrypted = str_openssl_enc($phone, $iv);
    $tujuan_awal_encrypted = str_openssl_enc($tujuan_awal, $iv);
    $tujuan_akhir_encrypted = str_openssl_enc($tujuan_akhir, $iv);
    $nama_bus_encrypted = str_openssl_enc($nama_bus, $iv);

    $iv_hex = bin2hex($iv);

    $query = "UPDATE form SET name='$name_encrypted', email='$email_encrypted', phone='$phone_encrypted', tujuan_awal='$tujuan_awal_encrypted', tujuan_akhir='$tujuan_akhir_encrypted', nama_bus='$nama_bus_encrypted', iv='$iv_hex' WHERE id=$id";
    $result = mysqli_query($con, $query);

    if($result) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Passenger</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2 class="mb-4">Edit Passenger</h2>
        <form method="post">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" class="form-control" value="<?php echo $name; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo $email; ?>" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" name="phone" class="form-control" value="<?php echo $phone; ?>" required>
            </div>
            <div class="form-group">
                <label for="tujuan_awal">Tujuan Awal</label>
                <input type="text" name="tujuan_awal" class="form-control" value="<?php echo $tujuan_awal; ?>" required>
            </div>
            <div class="form-group">
                <label for="tujuan_akhir">Tujuan Akhir</label>
                <input type="text" name="tujuan_akhir" class="form-control" value="<?php echo $tujuan_akhir; ?>" required>
            </div>
            <div class="form-group">
                <label for="nama_bus">Nama Bus</label>
                <input type="text" name="nama_bus" class="form-control" value="<?php echo $nama_bus; ?>" required>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</body>
</html>
