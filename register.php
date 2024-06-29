<?php
include('db.php');
include('functions.php'); // Include the file with encryption/decryption functions

if(isset($_POST['submit'])){
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $tujuan_awal = mysqli_real_escape_string($con, $_POST['tujuan_awal']);
    $tujuan_akhir = mysqli_real_escape_string($con, $_POST['tujuan_akhir']);
    $nama_bus = mysqli_real_escape_string($con, $_POST['nama_bus']);
    $password = password_hash(mysqli_real_escape_string($con, $_POST['password']), PASSWORD_DEFAULT);
    $iv = openssl_random_pseudo_bytes(16);

    // Encrypting input data
    $name = str_openssl_enc($name, $iv);
    $email = str_openssl_enc($email, $iv);
    $phone = str_openssl_enc($phone, $iv);
    $tujuan_awal = str_openssl_enc($tujuan_awal, $iv);
    $tujuan_akhir = str_openssl_enc($tujuan_akhir, $iv);
    $nama_bus = str_openssl_enc($nama_bus, $iv);

    $iv_hex = bin2hex($iv);

    // Inserting encrypted data into database
    $query = "INSERT INTO form (name, email, phone, tujuan_awal, tujuan_akhir, nama_bus, password, iv) 
              VALUES ('$name', '$email', '$phone', '$tujuan_awal', '$tujuan_akhir', '$nama_bus', '$password', '$iv_hex')";
    if(mysqli_query($con, $query)){
        header("Location: login.php");
        exit();
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('https://images.pexels.com/photos/10907829/pexels-photo-10907829.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1');
            background-size: cover; 
            background-position: center; 
            height: 100vh; 
            margin: 0; 
            display: flex; 
            flex-direction: column;
            align-items: center; 
        }
        .form-group {
            margin-bottom: 1rem; 
        }
        .footer {
            background-color: rgba(0, 0, 0, 0.2);
            color: white;
            text-align: center;
            padding: 10px 0;
            width: 100%;
            position: absolute;
            bottom: 0;
            left: 0;
        }
        .container {
            max-width: 600px;
            width: 100%;
            padding: 20px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
        }
        .list-penumpang {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card mt-5">
                    <div class="card-header">
                        <h2 class="text-center">Registrasi Data Anda</h2>
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="phone">Phone:</label>
                                <input type="text" class="form-control" id="phone" name="phone" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="tujuan_awal">Tujuan Awal:</label>
                                <input type="text" class="form-control" id="tujuan_awal" name="tujuan_awal" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="tujuan_akhir">Tujuan Akhir:</label>
                                <input type="text" class="form-control" id="tujuan_akhir" name="tujuan_akhir" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="nama_bus">Nama Bus:</label>
                                <input type="text" class="form-control" id="nama_bus" name="nama_bus" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            
                            <button type="submit" name="submit" class="btn btn-primary btn-block">Register</button>
                            <div class="text-center mt-3">
                                <a href="logout.php" class="btn btn-secondary btn-block">Logout</a>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card mt-5 list-penumpang">
                    <div class="card-header">
                        <h2 class="text-center">List Penumpang</h2>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <?php
                            $res = mysqli_query($con, "SELECT id, name, iv FROM form ORDER BY id DESC");
                            while($row = mysqli_fetch_assoc($res)){
                                $iv = hex2bin($row['iv']);
                                $name = str_openssl_dec($row['name'], $iv);
                                echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
                                echo $name;
                                echo '<a href="details-admin.php?id='.$row['id'].'" class="btn btn-primary btn-sm">Cek Selengkapnya</a>';
                                echo '</li>';
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS and dependencies (optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
