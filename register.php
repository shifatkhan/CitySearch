<?php

/**
 * This php script will be validating data from the registeration form. If 
 * everything is ok, it will add the user to the database and return value 
 * accordingly.
 * 
 * @author Shifat Khan
 */
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
                $query = 'INSERT INTO users(username, password) VALUES(?, ?);';
                $stmt = $pdo->prepare($query);

                $stmt->bindParam(1, $_POST['user']);
                $stmt->bindParam(2, $_POST['password']);
                $stmt->execute();

                // Return value indicating user was created
                header("location:index.php?register=3");
            } catch (PDOException $pdoe) {
                echo "<h1>Something went wrong. Please try again.</h1>";
            } catch (Exception $e) {
                echo "<h1>Something went wrong. Please try again.</h1>";
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
