<?php
/**
 * This php script will simply log out the user by destroying its session. It 
 * will then redirect them to the login page.
 * 
 * @author Shifat Khan
 */

session_start();
session_destroy();  

// Redirect to login page
header("Location: index.php");

