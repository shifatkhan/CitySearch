<!DOCTYPE html>
<!--
This web page will be taking care of displaying the login form

@author Shifat Khan
-->

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
        <title>City Search - Log in</title>
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

                <input class="button" type="submit" name="login" value="Login"/>
                <input class="button" type="submit" name="register" value="Sign up"/>
            </form>
            <?php
            if (isset($_GET['login'])) {
                if ($_GET['login'] == 1) {
                    echo "<p class=\"alert\" style=\"color: white\">"
                    . "Wrong username or password</p>";
                } else if ($_GET['login'] == 2) {
                    echo "<p class=\"alert\" style=\"color: white\">"
                    . "Please fill out the form</p>";
                } else if ($_GET['login'] == 3) {
                    echo "<p class=\"alert\" style=\"color: white\">"
                    . "Account created. You can now log in</p>";
                }
            }
            if (isset($_GET['register'])) {
                if ($_GET['register'] == 3) {
                    echo "<p class=\"alert\" style=\"color: white\">"
                    . "Account successfully created. You can log in now.</p>";
                }
            }
            ?>
        </div>
        <div id="footerWrapper">
            <footer>Shifat Khan &copy; Dawson College, 2016</footer>
        </div>
    </body>
</html>
