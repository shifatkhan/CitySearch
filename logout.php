<?php

/**
 * This php script will simply log out the user by destroying its session. It 
 * will then redirect them to the login page.
 * 
 * @author Shifat Khan
 */
session_start();
session_regenerate_id();

if (isset($_SESSION['username'])) {
    setcookie("logout", session_name(), time()-42000);
    $_SESSION = [];
    session_destroy();
    // Redirect to login page
    header("Location: index.php");
    exit;
}