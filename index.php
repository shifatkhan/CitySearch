<!DOCTYPE html>
<!--
This web page will be taking care of displaying the login form

@author Shifat Khan
-->

<?php
session_start();
if (isset($_SESSION['username'])) {
    if ($_SESSION['username']) {
        //redirect to search form page to secure login page. 
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
        <div id="formWrapper">
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
                    echo "<p class=\"alert\" style=\"color: LightCoral\">"
                    . "Too many login attempts. Please try again later.</p>";
                }
            }
            if (isset($_GET['register'])) {
                if ($_GET['register'] == 4) {
                    echo "<p class=\"alert\" style=\"color: LawnGreen\">"
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
