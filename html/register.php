<?php
ob_start();
if(!isset($_SESSION)) { session_start(); } 

$conn = require (__DIR__."/login/connection.php");
include(__DIR__."/login/function.php");
?>


<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Zilla+Slab:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="stylesheet" href="/css/login.css">
    <title>Whiteboard Forum-Register</title>
</head>

<body>
    <!-- Nav bar -->
    <nav class="navbar navbar-light d-flex justify-content-between">
        <a class="navbar-brand d-flex flex-rows align-items-center" href="/">
            <img src="/images/icon.png" width="50" height="50" class="d-inline-block align-center p-2" alt="">
            <div style="font-weight: 500">Whiteboard Forum</div>
        </a>
    </nav>

    <!-- form -->
    <div class="h-75 d-flex flex-column align-items-center justify-content-center">
        <form class="d-flex flex-column align-items-center" action="" method="post">
            <h1 class="h3 mb-3 font-weight-normal text-center">Register your account</h1>
            <label for="email" class="sr-only">Email address</label>
            <input name="email" type="email" id="email" class="form-control mb-1" placeholder="Email address" required autofocus>

            <label for="username" class="sr-only">Username</label>
            <input name="username" type="username" id="username" class="form-control mb-1" placeholder="Username" required>

            <label for="password" class="sr-only">Password</label>
            <input name="password" type="password" id="password" class="form-control mb-1" placeholder="Password" required>

            <button class="btn btn-lg btn-primary m-2" type="submit" name="submit">Register</button>
        </form>

        <?php
            if (isset($_POST['submit'])) {
                //prevent sql injection
                $email = mysqli_real_escape_string($conn, trim($_POST['email']));
                $username = mysqli_real_escape_string($conn, trim($_POST['username']));
                $password = mysqli_real_escape_string($conn, trim($_POST['password']));
                
                // Validate password strength
                $uppercase    = preg_match('@[A-Z]@', $password);
                $lowercase    = preg_match('@[a-z]@', $password);
                $number       = preg_match('@[0-9]@', $password);
                $specialchars = preg_match('@[^\w]@', $password);
                
                if (!is_email_avail($conn, $email)){
                    echo 'Email has been registered.';
                }
                elseif (strlen($username) < 6){
                    echo 'Username requies minimum length of 6 digits.';
                }
                elseif (!$uppercase || !$lowercase || !$number || !$specialchars || strlen($password) < 8) {
                    echo 'Password requires uppercase letter, lowercase letter, number and specialchars.';
                } else {
                    if(is_username_avail($conn, $username)){
						if(!isset($_SESSION)) { session_start(); } 
                        register_user($conn, $email, $username, $password);
                        header("Location: /register-success.php");
						ob_end_flush();
                        exit();
                    }else{
                        echo 'Username has been used.';
                    }
                }
            }
        ?>
    </div>

    <!-- Optional JS -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
</body>

</html>
