<?php

createUsersTable();
addDefaultsToUsersTable();
createCitiesTable();
addDataToCitiesTable();

function createUsersTable() {
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=homestead', "homestead", "secret");
        $dropQuery = "DROP TABLE IF EXISTS users;";
        $tableQuery = "CREATE TABLE users("
                . 'userid INT NOT NULL PRIMARY KEY AUTO_INCREMENT,'
                . 'username VARCHAR(255) NOT NULL,'
                . 'password VARCHAR(40) NOT NULL'
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

function addDefaultsToUsersTable() {
    try {
        //Add data to database
        $pdo = new PDO('mysql:host=localhost;dbname=homestead', 'homestead', 'secret');
        $query = 'INSERT INTO users (username, password) '
                . 'VALUES (?, ?);';
        $stmt = $pdo->prepare($query);

        $stmt->bindValue(1, "shifatkhan");
        $stmt->bindValue(2, "Shifat66");
        $stmt->execute();
        echo "Added shifatkhan\t(1/1)\n";
    } catch (PDOException $pdoe) {
        echo $pdoe->getMessage();
    } finally {
        unset($pdo);
    }
}

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

function addDataToCitiesTable() {
    $countries = file('cities.txt');
    $size = count($countries);

    try {
        //Add data to database
        $pdo = new PDO('mysql:host=localhost;dbname=homestead', 'homestead', 'secret');
        $query = 'INSERT INTO cities (city, country, population) '
                . 'VALUES (?, ?, ?);';

        for ($i = 0; $i < $size; $i++) {
            $row1 = explode(";", $countries[$i]);
            $population = $row1[0];
            $population = trim($population);

            $row2 = explode(",", $row1[1]);
            $city = $row2[0];
            $city = trim($city);
            
            array_shift($row2);
            $country = implode(",", $row2);
            $country = trim($country);
            
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(1, $city);
            $stmt->bindParam(2, $country);
            $stmt->bindParam(3, $population);
            $stmt->execute();
            
            echo "Added city\t(".($i+1)."/$size)\n";
        }
    } catch (PDOException $pdoe) {
        echo $pdoe->getMessage();
    } finally {
        unset($pdo);
    }
}

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
