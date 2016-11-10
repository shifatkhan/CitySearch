<?php

if (isset($_POST['login'])) {
    signin();
}

function signin() {
    session_start();

    // Check if the user field wasn't empty
    if (!empty($_POST['user']) AND !empty($_POST['password'])) {
        try {
            // Search if user exists in the db
            $pdo = new PDO('mysql:host=localhost;dbname=homestead', 'homestead', 'secret');
            $query = 'SELECT * FROM users WHERE username = ? AND password = ?;';
            $stmt = $pdo->prepare($query);

            $stmt->bindParam(1, $_POST['user']);
            $stmt->bindParam(2, $_POST['password']);
            $stmt->execute();

            // Check if there's a row and if the user was found
            if ($row = $stmt->fetch()) {
                if (!empty($row['username']) AND ! empty($row['password'])) {
                    $_SESSION['username'] = $row['username'];
                    header("location:searchForm.php");
                    exit;
                } else {
                    header("location:index.php?login=1");
                    exit;
                }
            } else {
                header("location:index.php?login=1");
                exit;
            }
        } catch (PDOException $pdoe) {
            echo $pdoe->getMessage();
        } finally {
            unset($pdo);
        }
    }
    else{
        header("location:index.php?login=2");
        exit;
    }
}
