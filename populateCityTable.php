<?php
/**
 * This file will simply read data from the city.txt file and populate our db
 * @author Shifat Khan
 */

$cities = file('cities.txt');

/**
 * 
 * @param type $recipename
 * @param type $link
 * @param type $description
 * @param type $username
 * @param type $gawked
 */
function addToDatabase($recipename, $link, $description, $username, $gawked) {
    try {
        //Add data to database
        $pdo = new PDO('mysql:host=localhost;dbname=homestead', 'homestead', 'secret');
        $query = 'INSERT INTO recipes (recipename, username, description, link, gawked) VALUES (?, ?, ?, ?, ?);';
        $stmt = $pdo->prepare($query);
        
        $stmt->bindParam(1, $recipename);
        $stmt->bindParam(2, $username);
        $stmt->bindParam(3, $description);
        $stmt->bindParam(4, $link);
        $stmt->bindParam(5, $gawked);

        $stmt->execute();
    } catch (PDOException $pdoe) {
        echo $pdoe->getMessage();
    } finally {
        unset($pdo);
    }
}

