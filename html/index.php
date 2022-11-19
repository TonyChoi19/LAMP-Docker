<?php
ob_start();
if(!isset($_SESSION)) { session_start(); } 
include(__DIR__."/login/function.php");

$conn = require(__DIR__."/login/connection.php");
$user = is_login($conn);

if(isset($_POST['sign-out'])) {
	if(!isset($_SESSION)) { session_start(); } 
    session_destroy();
    header("Location: /");
	ob_end_flush();
    exit;
}
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
    <link rel="stylesheet" href="/css/index.css">
    <title>Whiteboard Forum</title>
</head>

<body>
    <!-- Nav bar -->
    <nav class="navbar navbar-light bg-light d-flex justify-content-between">
        <a class="navbar-brand d-flex flex-rows align-items-center" href="/">
            <img src="/images/icon.png" width="50" height="50" class="d-inline-block align-center p-2" alt="">
            <div style="font-weight: 500">Whiteboard Forum</div>
        </a>
        <?php if (!empty($user) && isset($user)) :?>
            <div class="d-flex flex-rows">
                <a class="btn btn-primary m-1" href="/forum.php" role="button">Forum</a>
                <form method="post">
                    <input class="btn btn-primary m-1" type="submit" name="sign-out" value="Sign out">
                </form>
            </div>
        <?php else :?>
            <div>
                <a class="btn btn-primary" href="/register.php" role="button">Register</a>
                <a class="btn btn-primary" href="/login.php" role="button">Sign In</a>
            </div>
        <?php endif; ?>
    </nav>

    <!-- Main content -->
    <main class="h-100 text-white d-flex flex-column align-items-center justify-content-center">
        <h1 class="cover-heading">Post your thoughts, Fill the white.</h1>
        <p class="lead">Whiteboard Forum is a forum to share, to discover...</p>
        <p class="lead">Login for more</p>
    </main>

    <!-- footer -->
    <footer class="cus_footer text-center text-white p-1">
        <div class="inner">
            <p>@COMP3335 Project</p>
        </div>
    </footer>

    <!-- Optional JavaScript -->
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