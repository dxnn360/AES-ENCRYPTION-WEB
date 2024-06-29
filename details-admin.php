<?php
session_start();
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
    header("Location: login.php");
    exit();
}

include('db.php');
include('functions.php'); // Include the file with encryption/decryption functions

// Get passenger ID from URL
if(isset($_GET['id'])){
    $id = intval($_GET['id']);
} else {
    echo "Invalid Request";
    exit();
}

// Fetch passenger data from database
$result = mysqli_query($con, "SELECT * FROM form WHERE id = $id");
if($result && mysqli_num_rows($result) > 0){
    $row = mysqli_fetch_assoc($result);
    $iv = hex2bin($row['iv']);
    $name = str_openssl_dec($row['name'], $iv);
    $email = str_openssl_dec($row['email'], $iv);
    $phone = str_openssl_dec($row['phone'], $iv);
    $tujuan_awal = str_openssl_dec($row['tujuan_awal'], $iv);
    $tujuan_akhir = str_openssl_dec($row['tujuan_akhir'], $iv);
    $nama_bus = str_openssl_dec($row['nama_bus'], $iv);
} else {
    echo "Passenger not found";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passenger Details</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 20px;
        }
        .back-btn {
            margin-top: 20px;
        }
        .action-btns {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mb-4">Passenger Details</h2>
        <table class="table table-bordered">
            <tr>
                <th>ID</th>
                <td><?php echo $row['id']; ?></td>
            </tr>
            <tr>
                <th>Name</th>
                <td><?php echo $name; ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo $email; ?></td>
            </tr>
            <tr>
                <th>Phone</th>
                <td><?php echo $phone; ?></td>
            </tr>
            <tr>
                <th>Tujuan Awal</th>
                <td><?php echo $tujuan_awal; ?></td>
            </tr>
            <tr>
                <th>Tujuan Akhir</th>
                <td><?php echo $tujuan_akhir; ?></td>
            </tr>
            <tr>
                <th>Nama Bus</th>
                <td><?php echo $nama_bus; ?></td>
            </tr>
        </table>
        <div class="action-btns">
            <a href="register.php" class="btn btn-primary back-btn">Back to List</a>
            <div>
                <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning">Edit</a>
                <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this passenger?');">Delete</a>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS and dependencies (optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
