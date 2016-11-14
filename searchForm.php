<!DOCTYPE html>
<!--
This web page will be taking care of displaying the search form.

@author Shifat Khan
-->

<?php
session_start();
if (!$_SESSION['username']) {
    //redirect to login page to secure search form page without login access.  
    header("Location: index.php");
    exit;
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>City Search</title>
        <link rel="stylesheet" type="text/css" href="myCss.css"/>

        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script src="myJavascript.js"></script>
    </head>
    <body>
        <div class="header">
            <div class="headerContent">
                <a href="#">
                    <img src="citySearchLogo.png" alt="City Search Logo" title="City Search Logo"/>
                </a>
            </div>
            <div class="nav">
                <ul>
                    <li><a href="#"><?php
                            $user = $_SESSION['username'];
                            echo "Logged in as: $user";
                            ?></a></li>
                    <li><a href="logout.php">Logout</a></li>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Contact Us</a></li>
                </ul>
            </div>
        </div>
        <div id="formWrapper">
            <form id="searchForm" method="get">
                <label>Keyword:</label>
                <input id="keyword" type="text" name="keyword" placeholder="Search"
                       list="datalist"/>
                <input type="submit" name="search" value="Search"/>
                <p class="alert" style="color: white"></p>
            </form>
            <?php
            // REMOVE/CHANGE THIS - NOT PART OF PROJECT
            //Check if there's something in the field
            if (isset($_GET['search'])) {
                if (isset($_GET['keyword']) && $_GET['keyword'] != '') {
                    
                } else {
                    echo "<p class=\"alert\" style=\"color: white\">Enter a keyword</p>";
                }
            }
            ?>
            <div id="results"></div>
        </div>


        <footer>Shifat Khan &copy; Dawson College, 2016</footer>
    </body>
</html>
