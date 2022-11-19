<?php
    if(isset($_POST['sign-out'])) {
        if(!isset($_SESSION)) { session_start(); } 
        session_destroy();
        header("Location: /");
        exit();
    }

    if(!isset($_SESSION)) { session_start(); } 
    include(__DIR__."/login/function.php");

    $conn = require (__DIR__."/login/connection.php");
    $user = is_login($conn);
    
    if(empty($user)){
        header("Location: /");
        exit();
    }
?>
<?php 
    
    // get user name
    $current_user = get_user($conn, $_SESSION["email"]);
    $name = $current_user['username'];

    $admin = check_admin($conn, $user['email']);
    if($admin == 'YES') {
        $name = 'Admin '.$name;
    }
?>

<!DOCTYPE>
<html>

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
        <link rel="stylesheet" href="/css/forum.css">
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
        <main class="text-white d-flex flex-column container">
            <div class="white_box">        
                <?php
                    // if edit button is pressed
                    if(isset($_POST['edit']) && isset($_POST['post_id'])) {
                        $edit_post_id = $_POST['post_id'];
                        
                        // debug_to_console('trying to edit post '.$edit_post_id);
                        $fetched_posts = $conn->query("SELECT * FROM `posts` WHERE post_id = $edit_post_id");

                        while($row = $fetched_posts->fetch_row()) {
                            $current_post_post_id = $row[0];
                            $current_post_user_id = $row[1];
                            $current_post_date_time = $row[2];
                            $current_post_content = $row[3];

                            $current_post_username = get_username_from_id($conn, $current_post_user_id);

                            echo "<div style='border-bottom: 1px whitesmoke solid; width: 100%;>";
                            echo "
                            <p class='post_text'>
                                <span class='post_user'>Posted by $current_post_username at $current_post_date_time</span>
                                <br>$current_post_content
                            </p>";                       

                            if($current_post_user_id == $current_user['user_id'] || $admin == 'YES') {                        
                                echo <<<_END
                                <form action="forum.php" method="post" class="form-inline">
                                    <input class="btn btn-warning btn-default mx-1" type="submit" name="edit" value="Edit">
                                    <input type="text" name="edit" class="mx-1" required>
                                    <input type="hidden" name="post_id" value="$current_post_post_id" />
                                </form>
                                _END;
                            }
                        }
                    }
                ?>  
            </div> 
        </main>     

        <!-- footer -->
        <footer class="cus_footer text-center text-white p-1">
            <div class="inner">
                <p>@COMP3335 Project</p>
            </div>
        </footer>
    </body>
</html>

