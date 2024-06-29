<?php
session_start();

include('db.php');
include('functions.php'); // Include the file with encryption/decryption functions

// Set the timezone and get the current date
date_default_timezone_set('Asia/Jakarta');
$current_date = date('d-m-Y');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passenger List</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 20px;
        }
        .logout-btn {
            margin-top: 20px;
        }
        .login-btn {
            margin-top: 20px;
            float: right;
        }
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-section">
            <h2 class="mb-4">List Penumpang Agen Montego</h2>
        </div>
        <p>Tanggal: <?php echo $current_date; ?></p>
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $res = mysqli_query($con, "SELECT * FROM form ORDER BY id DESC");
                while($row = mysqli_fetch_assoc($res)){
                    $iv = hex2bin($row['iv']);
                    $name = str_openssl_dec($row['name'], $iv);
                    
                    echo "<tr>";
                    echo "<td>".$name."</td>";
                    echo "<td>
                            <a href='details.php?id=".$row['id']."' class='btn btn-info btn-sm'>Cek Selengkapnya</a>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
        <form action="login.php" method="POST" class="text-center logout-btn">
            <button type="submit" class="btn btn-primary">Login to Add Data</button>
        </form>
    </div>
    
    <!-- Bootstrap JS and dependencies (optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
