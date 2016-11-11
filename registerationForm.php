<!DOCTYPE html>
<!--
This web page will be taking care of displaying the Registration form and
create the user's account.

@author Shifat Khan
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>City Search - Register</title>
        <link rel="stylesheet" type="text/css" href="myCss.css"/>
    </head>
    <body>
        <h1 id="title">Register</h1>
        <div id="loginWrapper">
            <form action="register.php" method="post">
                <br>
                <label style="color: white">User: </label>
                <input type="text" name="user" />
                <br>

                <br>
                <label style="color: white">Password: </label>
                <input type="password" name="password" />
                <br>
                
                <br>
                <label style="color: white">Confirm password: </label>
                <input type="password" name="cpassword" />
                <br>
                
                <input class="button" type="submit" name="register" value="Register"/>
            </form>
            <?php
            if (isset($_GET['register'])) {
                if ($_GET['register'] == 1) {
                    echo "<p class=\"alert\" style=\"color: white\">"
                    . "Please fill out the form.</p>";
                }
                if ($_GET['register'] == 2) {
                    echo "<p class=\"alert\" style=\"color: white\">"
                    . "Passwords don't match.</p>";
                }
            }
            ?>
        </div>
        <div id="footerWrapper">
            <footer>Shifat Khan &copy; Dawson College, 2016</footer>
        </div>
    </body>
</html>
