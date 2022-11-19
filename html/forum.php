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
    
    // debug_to_console("logged in user is ".$name);
    // debug_to_console("logged in user id is ".$current_user['user_id']);

    // if create button is pressed
    if(isset($_POST['create'])) {
        $create_post_user = $current_user['user_id'];
        $create_content_user = mysqli_real_escape_string($conn, trim($_POST['create']));
        $t = date('Y-m-d h:i:s');

        $query = "INSERT INTO `posts` VALUES (DEFAULT, '$create_post_user', DEFAULT, '$create_content_user')";
        $result = $conn->query($query);         
        // debug_to_console("trying to make  (".$create_post_user.") create new post with (".$create_content_user.") at (".$t.") success");
    }
    
    // if delete button is pressed
    if(isset($_POST['delete']) && isset($_POST['post_id'])) {
        $post_id_delete = $_POST['post_id'];
        $query = "DELETE FROM posts WHERE post_id=$post_id_delete";            
        $result = $conn->query($query);
        // debug_to_console("deleted post (".$post_id_delete.") success");
    }
    
    // if edit button is pressed (returned from edit-post.php)
    if(isset($_POST['edit']) && isset($_POST['post_id'])) {
        $post_id_edit = $_POST['post_id'];
        $edited_content = mysqli_real_escape_string($conn, trim($_POST['edit']));
        $query = "UPDATE `posts` SET `content` = '$edited_content' WHERE `post_id` = '$post_id_edit';";
        $result = $conn->query($query);
        // debug_to_console("updated post (".$post_id_edit.") (".$edited_content.") success");
    }
?>

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
                    <form method="post" style="margin-bottom: 0px">
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
        <main class="text-white d-flex flex-column container">
            <p class="lead align-items-center white_box"><?php echo $name,', welcome back!<br>'; ?></p>
            <?php
                echo <<<_END
                <form class="form-inline" action="forum.php" method="post">               
                    <div class="form-group green_box">     
                        <input type="submit" class="btn btn-success mx-2" value="Create post">    
                        <input type="text" class="form-control mx-2" id="createPost" name="create" placeholder="Create a post here" required>
                        <input type="hidden" name="username" value="$name" />
                        
                        
                    </div>
                </form>
                _END;
            ?>
            <div class="white_box">            
                <?php
                    // fetch 50 latest posts
                    $fetched_posts = $conn->query("SELECT * FROM `posts` ORDER BY `post_id` DESC"); // add LIMIT 0,50 if nessasery
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
                            <table>
                                <tr>
                                    <td>
                                        <form action="forum.php" method="post" class="form-inline">
                                            <input class="btn btn-danger btn-default mx-1" type="submit" name="delete" value="Delete">
                                            <input type="hidden" name="post_id" value="$current_post_post_id" />
                                        </form>
                                    </td>
                                    <td>
                                        <form action="edit-post.php" method="post" class="form-inline">
                                            <input class="btn btn-warning btn-default mx-1" type="submit" name="edit" value="Edit">
                                            <input type="hidden" name="post_id" value="$current_post_post_id" />
                                        </form>    
                                    </td>
                                </tr>
                            </table>
                            _END;
                        }
                        echo "</div>";
                        
                    }
                ?>      
                <p>End of posts</p>
            </div>
        </main>

        <!-- footer -->
        <footer class="page-footer text-center text-white p-1">
            <div class="inner">
                <p>@COMP3335 Project</p>
            </div>
        </footer>

    </body>

</html>