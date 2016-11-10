<!DOCTYPE html>
<?php
session_start();
if (isset($_SESSION['username'])) {
    if ($_SESSION['username']) {
        //redirect to login page to secure search form page without login access.  
        header("Location: searchForm.php");
        exit;
    }
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>City search engine</title>
        <link rel="stylesheet" type="text/css" href="myCss.css"/>
    </head>
    <body>
        <h1 id="title">Log in</h1>
        <div id="loginWrapper">
            <form action="authentication.php" method="post">
                <br>
                <label style="color: white">User: </label>
                <input type="text" name="user" />
                <br>

                <br>
                <label style="color: white">Password: </label>
                <input type="password" name="password" />
                <br>

                <input id="button" type="submit" name="login" value="Login"/>
            </form>
            <?php
            if (isset($_GET['login'])) {
                if ($_GET['login'] == 1) {
                    echo "<p class=\"error\" style=\"color: white\">"
                    . "Wrong username or password</p>";
                } else if ($_GET['login'] == 2) {
                    echo "<p class=\"error\" style=\"color: white\">"
                    . "Please fill out the form</p>";
                }
            }
            ?>
        </div>
        <div id="footerWrapper">
            <footer>Shifat Khan &copy; Dawson College, 2016</footer>
        </div>
    </body>
</html>
