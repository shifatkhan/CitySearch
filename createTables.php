<?php

createUsersTable();
addDefaultsToUsersTable();
//createCitiesTable();
//addDataToCitiesTable();
createLoginAttemptsTable();

/**
 * Creates the users table in the homestead database
 */
function createUsersTable() {
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=homestead', "homestead", "secret");
        $dropQuery = "DROP TABLE IF EXISTS users;";
        $tableQuery = "CREATE TABLE users("
                . 'userid INT NOT NULL PRIMARY KEY AUTO_INCREMENT,'
                . 'username VARCHAR(10) NOT NULL UNIQUE,'
                . 'hashpass VARCHAR(255) NOT NULL,'
                . 'attempt INT,'
                . 'lastlogin DATETIME,'
                . 'lastipaddress VARCHAR(255)'
                . ');';
        $pdo->exec($dropQuery);
        $pdo->exec($tableQuery);

        //Check if table has been created
        checkTables($pdo, 'users');
    } catch (PDOException $pdoe) {
        echo $pdoe->getMessage();
    } finally {
        unset($pdo);
    }
}

/**
 * Adds the the default users in the users table
 */
function addDefaultsToUsersTable() {
    try {
        //Add data to database
        $pdo = new PDO('mysql:host=localhost;dbname=homestead', 'homestead', 'secret');
        $query = 'INSERT INTO users (username, hashpass) '
                . 'VALUES (?, ?);';
        $stmt = $pdo->prepare($query);

        $options = [
            'cost' => 10,
        ];
        $passwordFromPost = 'Shifat66';
        $hash = password_hash($passwordFromPost, PASSWORD_BCRYPT, $options);

        $stmt->bindValue(1, "Shifat");
        $stmt->bindParam(2, $hash);
        $stmt->execute();

        // For debugging purposes
        echo "(1/1)\t Added Shifat\n";
    } catch (PDOException $pdoe) {
        echo $pdoe->getMessage();
    } finally {
        unset($pdo);
    }
}

/**
 * Creates the cities table in the homestead database
 */
function createCitiesTable() {
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=homestead', "homestead", "secret");
        $dropQuery = "DROP TABLE IF EXISTS cities;";
        $tableQuery = "CREATE TABLE cities("
                . 'cityid INT NOT NULL PRIMARY KEY AUTO_INCREMENT,'
                . 'city VARCHAR(255) NOT NULL,'
                . 'country VARCHAR(255) NOT NULL,'
                . 'population INT NOT NULL'
                . ');';
        $pdo->exec($dropQuery);
        $pdo->exec($tableQuery);

        //Check if table has been created
        checkTables($pdo, 'cities');
    } catch (PDOException $pdoe) {
        echo $pdoe->getMessage();
    } finally {
        unset($pdo);
    }
}

/**
 * Adds all the cities data from cities.txt in the cities table
 */
function addDataToCitiesTable() {
    $countries = file('cities.txt');
    $size = count($countries);

    try {
        //Add data to database
        $pdo = new PDO('mysql:host=localhost;dbname=homestead', 'homestead', 'secret');
        $query = 'INSERT INTO cities (city, country, population) '
                . 'VALUES (?, ?, ?);';

        // Algorithm to get each field of a city from the cities.txt
        for ($i = 0; $i < $size; $i++) {
            // Seperate population from the rest
            $row1 = explode(";", $countries[$i]);
            $population = $row1[0];
            $population = trim($population);

            // Excluding the population, make an array
            $row2 = explode(",", $row1[1]);
            $city = $row2[0];
            $city = trim($city);

            // Remove the city field and make a string for the country
            array_shift($row2);
            $country = implode(",", $row2);
            $country = trim($country);

            $stmt = $pdo->prepare($query);
            $stmt->bindParam(1, $city);
            $stmt->bindParam(2, $country);
            $stmt->bindParam(3, $population);
            $stmt->execute();

            // For debugging purposes
            echo "(" . ($i + 1) . "/$size)\t Added city: $city\n";
        }
    } catch (PDOException $pdoe) {
        echo $pdoe->getMessage();
    } finally {
        unset($pdo);
    }
}

/**
 * Creates the loginAttempts table to count how many times someone failed to 
 * login. This helps against bruteforces.
 */
function createLoginAttemptsTable() {
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=homestead', "homestead", "secret");
        $dropQuery = "DROP TABLE IF EXISTS loginAttempts;";
        $tableQuery = "CREATE TABLE loginAttempts("
                . 'id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,'
                . 'lastlogin DATETIME,'
                . 'lastipaddress VARCHAR(255)'
                . ');';
        $pdo->exec($dropQuery);
        $pdo->exec($tableQuery);

        //Check if table has been created
        checkTables($pdo, 'loginAttempts');
    } catch (PDOException $pdoe) {
        echo $pdoe->getMessage();
    } finally {
        unset($pdo);
    }
}

/**
 * Checks if the table was created or not and displays accordingly
 * (for debugging purposes)
 * 
 * @param type $pdo
 * @param type $tablename
 */
function checkTables($pdo, $tablename) {
    // Check if the table was created
    $tableCheck = $pdo->query("SELECT * FROM $tablename");
    if (!empty($tableCheck)) {
        echo "Table '$tablename' created\n";
    } else {
        echo "ERROR: Table '$tablename' not created\n";
    }
}

?>
