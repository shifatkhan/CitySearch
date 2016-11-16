<?php

/**
 * This php script will be validation and authenticating the username and 
 * password. If the user has sucessfully logged in, it will start a session and 
 * redirect them to the web app.
 * TODO: Use ajax instead
 * 
 * @author Shifat Khan
 */

include ('databaseConstants.php');

// Check if the user attempted to login more than three times or not
if (isset($_POST['login'])) {
    if (countLoginAttempts() < 3) {
        signin();
    } else {
        header("location:index.php?login=3");
        exit;
    }
} else if (isset($_POST['register'])) {
    header("Location: registerationForm.php");
    exit;
}

/**
 * Authenticate the user and start a session. Redirect if successful
 */
function signin() {
    session_start();
    session_regenerate_id();

    // Check if the user field wasn't empty
    if (!empty($_POST['user']) AND ! empty($_POST['password'])) {
        try {
            // Search if user exists in the db
            $pdo = new PDO('mysql:host='.HOST.';dbname='.DB_NAME, USER, PASSWORD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $query = 'SELECT * FROM users WHERE username = ?;';
            $stmt = $pdo->prepare($query);

            $stmt->bindParam(1, $_POST['user']);
            $stmt->execute();

            // Check if there's a row and if the user was found
            if ($row = $stmt->fetch()) {
                if (password_verify($_POST['password'], $row['hashpass'])) {
                    // Create session id for the user logged in
                    $_SESSION['username'] = $row['username'];
                    
                    // Store information about the current session
                    storeSessionInfo($row['username']);
                    
                    // Reset the number of attempts
                    resetLoginAttempts();
                    
                    header("location:searchForm.php");
                    exit;
                } else {
                    // Add a login attempt in the database
                    storeLoginAttempt();
                    
                    // Return value indicating user inputed wrong username or password
                    header("location:index.php?login=1");
                    exit;
                }
            } else {
                // Return value indicating user doesn't exist
                header("location:index.php?login=1");
                exit;
            }
        } catch (PDOException $pdoe) {
            echo "<h1>Something went wrong. Please try again later</h1>";
        } catch (Exception $e) {
            echo "<h1>Something went wrong. Please try again later</h1>";
        } finally {
            unset($pdo);
        }
    } else {
        // Return value indicating user didn't fill out the form
        header("location:index.php?login=2");
        exit;
    }
}

/**
 * This function will count how many failed attempt there was by the current user
 * during the past 5 minutes.
 */
function countLoginAttempts() {
    try {
        $pdo = new PDO('mysql:host='.HOST.';dbname='.DB_NAME, USER, PASSWORD);
        $pdo->setAttribute(PDO::FETCH_NUM);
        $query = "SELECT COUNT(*) FROM loginAttempts WHERE (lastlogin > NOW()"
                . " - INTERVAL 5 MINUTE) AND lastipaddress = ?;";

        $stmt = $pdo->prepare($query);

        $stmt->bindValue(1, $_SERVER['REMOTE_ADDR']);
        $stmt->execute();

        // Check if there was any attempts before, else return 0
        if ($row = $stmt->fetch()) {
            return $row[0];
        } else {
            return 0;
        }
    } catch (PDOException $pdoe) {
        echo $pdoe->getMessage();
    } finally {
        unset($pdo);
    }

    return 0;
}

/**
 * Put information about the login attempt in the database.
 */
function storeLoginAttempt() {
    try {
        $pdo = new PDO('mysql:host='.HOST.';dbname='.DB_NAME, USER, PASSWORD);
        $query = "INSERT INTO loginAttempts(lastipaddress, lastlogin)
          VALUES(?, CURRENT_TIMESTAMP);";
        $stmt = $pdo->prepare($query);

        $stmt->bindValue(1, $_SERVER['REMOTE_ADDR']);

        $stmt->execute();
    } catch (PDOException $pdoe) {
        echo $pdoe->getMessage();
    } finally {
        unset($pdo);
    }
}

/**
 * Store info about the user that logged in (Date and Ip address)
 * @param type $userLoggedIn
 */
function storeSessionInfo($userLoggedIn) {
    try {
        $pdo = new PDO('mysql:host='.HOST.';dbname='.DB_NAME, USER, PASSWORD);
        $query = 'UPDATE users SET lastlogin=CURRENT_TIMESTAMP, lastipaddress=? '
                . 'WHERE username=?;';
        $stmt = $pdo->prepare($query);

        $stmt->bindValue(1, $_SERVER['REMOTE_ADDR']);
        $stmt->bindParam(2, $userLoggedIn);
        $stmt->execute();
    } catch (PDOException $pdoe) {
        echo $pdoe->getMessage();
    } finally {
        unset($pdo);
    }
}

/**
 * Delete previous login attempts made by the current client.
 */
function resetLoginAttempts(){
    try {
        $pdo = new PDO('mysql:host='.HOST.';dbname='.DB_NAME, USER, PASSWORD);
        $query = 'DELETE FROM loginAttempts WHERE lastipaddress = ?;';
        $stmt = $pdo->prepare($query);

        $stmt->bindValue(1, $_SERVER['REMOTE_ADDR']);
        $stmt->execute();
    } catch (PDOException $pdoe) {
        echo $pdoe->getMessage();
    } finally {
        unset($pdo);
    }
}
