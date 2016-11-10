<!DOCTYPE html>
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
        <title>City search engine</title>
        <link rel="stylesheet" type="text/css" href="myCss.css"/>
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
            <form id="searchForm" action="" method="get">
                <label>Keyword:</label>
                <input type="text" name="keyword" 
                       value="<?php if (isset($_GET['keyword'])) echo $_GET['keyword']; ?>"/>
                <input type="submit" name="search" value="Search"/>
            </form>
        </div>
<?php
//Check if there's something in the field
if (isset($_GET['search'])) {
    if (isset($_GET['keyword']) && $_GET['keyword'] != '') {
        
    } else {
        echo "<div class='tableResult'>";
        echo "<p class='error'>Enter a keyword</p>";
        echo "</div>";
    }
}
?>
        <footer>Shifat Khan &copy; Dawson College, 2016</footer>
    </body>
</html>
