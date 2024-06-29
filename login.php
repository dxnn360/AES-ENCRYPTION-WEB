<?php
session_start();
include('db.php');
include('functions.php'); // Include the file with encryption/decryption functions

if(isset($_POST['submit'])){
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    $query = "SELECT * FROM form";
    $result = mysqli_query($con, $query);
    $user_found = false;
    while($row = mysqli_fetch_assoc($result)){
        $iv = hex2bin($row['iv']);
        $decrypted_email = str_openssl_dec($row['email'], $iv);
        if($decrypted_email == $email){
            $user_found = true;
            if(password_verify($password, $row['password'])){
                $_SESSION['loggedin'] = true;
                $_SESSION['name'] = str_openssl_dec($row['name'], $iv);
                $_SESSION['email'] = $decrypted_email;
                header("Location: register.php");
                exit();
            } else {
                $error = "Invalid password.";
            }
            break;
        }
    }
    if(!$user_found){
        $error = "No user found with that email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
    }
    .card {
        background-color: #ffffff;
        border: 1px solid #ced4da;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        width: 500px; /* Sesuaikan dengan lebar yang diinginkan */
        margin: auto;
        padding: 20px;
        z-index: 1;
    }
    .card-header {
        background-color: #ffffff;
        color: skyblue;
        text-align: center;
        padding: 1rem;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
    }
    .btn-primary {
        background-color: blue;
        border-color: blue;
    }
    .btn-primary:hover {
        background-color: darkblue;
        border-color: blue;
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
        position: fixed;
        bottom: 0;
        left: 0;
        z-index: 0;
    }
</style>

</head>
<body>
    <div class="card">
        <div class="card-header">
            <h2>Login</h2>
        </div>
        <div class="card-body">
            <?php if(isset($error)) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error; ?>
                </div>
            <?php } ?>
            <form method="post">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" name="submit" class="btn btn-primary btn-block">Login</button>
            </form>
        </div>
    </div>
    
    <!-- Footer -->
    <footer class="footer">

        <div class="text-center p-3">
            © 2024 Montego Bus Company. All rights reserved.
            <a class="text-white" href="https://montegobuscompany.com/">Montego Bus Company</a>
        </div>
    </footer>
    <!-- End of Footer -->
    
    <!-- Bootstrap JS and dependencies (optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
