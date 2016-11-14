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
    // Check if the fields aren't empty
    if (!empty($_POST['user']) AND ! empty($_POST['password'])
            AND ! empty($_POST['cpassword'])) {
        if ($_POST['cpassword'] === $_POST['password']) {
            try {
                // Store user into database
                $pdo = new PDO('mysql:host=localhost;dbname=homestead', 'homestead', 'secret');
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $query = 'INSERT INTO users(username, hashpass) VALUES(?, ?);';
                $stmt = $pdo->prepare($query);

                $stmt->bindParam(1, $_POST['user']);
                
                $options = [
                    'cost' => 10,
                ];
                $passwordFromPost = $_POST['password'];
                $hash = password_hash($passwordFromPost, PASSWORD_BCRYPT, $options);
                $stmt->bindParam(2, $hash);

                $stmt->execute();

                // Return value indicating user was created
                header("location:index.php?register=4");
            } catch (PDOException $pdoe) {
                if ($pdoe->errorInfo[1] == MYSQL_CODE_DUPLICATE_KEY) {
                    // Return value indicating username already taken
                    header("location:registerationForm.php?register=3");
                    exit;
                } else {
                    echo "<h1>Something went wrong. Please try again later</h1>";
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
