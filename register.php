<?php

/**
 * This php script will be validating data from the registeration form. If 
 * everything is ok, it will add the user to the database and return value 
 * accordingly.
 * TODO: Use ajax instead
 * 
 * @author Shifat Khan
 */
define("MYSQL_CODE_DUPLICATE_KEY", 1062);
register();

/**
 * User input validation and inputing data to database
 */
function register() {
    session_start();
    session_regenerate_id();
    
    $user = $_POST['user'];
    $user = strip_tags($user);
    $user = trim($user);
    
    $password = $_POST['password'];
    $password = strip_tags($password);
    $password = trim($password);
    
    $cpassword = $_POST['cpassword'];
    $cpassword = strip_tags($cpassword);
    $cpassword = trim($cpassword);
    
    // Check if the fields aren't empty
    if (!empty($user) AND ! empty($password) AND ! empty($cpassword)) {
        if ($cpassword === $password) {
            try {
                // Store user into database
                $pdo = new PDO('mysql:host=localhost;dbname=homestead', 'homestead', 'secret');
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $query = 'INSERT INTO users(username, hashpass) VALUES(?, ?);';
                $stmt = $pdo->prepare($query);
                
                $stmt->bindParam(1, $user);

                $options = [
                    'cost' => 10,
                ];
                $hash = password_hash($password, PASSWORD_BCRYPT, $options);
                $stmt->bindParam(2, $hash);

                $stmt->execute();

                // Create session id for the user logged in
                $_SESSION['username'] = $user;

                // Redirect to the search form
                header("location:searchForm.php");
            } catch (PDOException $pdoe) {
                if ($pdoe->errorInfo[1] == MYSQL_CODE_DUPLICATE_KEY) {
                    // Return value indicating username already taken
                    header("location:registerationForm.php?register=3");
                    exit;
                } else {
                    echo "<h1>".$pdoe->getMessage()."Something went wrong. Please try again later</h1>";
                }
            } finally {
                unset($pdo);
            }
        } else {
            // Return value indicating password fields doesn't match
            header("location:registerationForm.php?register=2");
            exit;
        }
    } else {
        // Return value indicating user didn't fill out the form
        header("location:registerationForm.php?register=1");
        exit;
    }
}
