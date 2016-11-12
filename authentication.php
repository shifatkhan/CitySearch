<?php
/**
 * This php script will be validation and authenticating the username and 
 * password. If the user has sucessfully logged in, it will start a session and 
 * redirect them to the web app.
 * 
 * @author Shifat Khan
 */

if (isset($_POST['login'])) {
    signin();
} else if(isset($_POST['register'])){
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
    if (!empty($_POST['user']) AND !empty($_POST['password'])) {
        try {
            // Search if user exists in the db
            $pdo = new PDO('mysql:host=localhost;dbname=homestead', 'homestead', 'secret');
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
                    storeSessionInfo($row['username']);
                    header("location:searchForm.php");
                    exit;
                } else {
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
        }finally {
            unset($pdo);
        }
    }
    else{
        // Return value indicating user didn't fill out the form
        header("location:index.php?login=2");
        exit;
    }
}

/**
 * Store info about the user that logged in (Date and Ip address)
 * @param type $userLoggedIn
 */
function storeSessionInfo($userLoggedIn){
    try {
        //Add data to database
        $pdo = new PDO('mysql:host=localhost;dbname=homestead', 'homestead', 'secret');
        $query = 'UPDATE users SET lastlogin=NOW(), lastipaddress=? '
                . 'WHERE username=?;';
        $stmt = $pdo->prepare($query);
        
        $stmt->bindValue(1, $_SERVER['REMOTE_ADDR']);
        $stmt->bindParam(2, $userLoggedIn);
        $stmt->execute();

        // For debugging purposes
        echo "(1/1)\t Added Shifat\n";
    } catch (PDOException $pdoe) {
        echo $pdoe->getMessage();
    } finally {
        unset($pdo);
    }
}
