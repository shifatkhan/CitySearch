<?php

/**
 * this php file will be storing the keyword from the client to the database 
 * as a search history.
 * 
 * @author Shifat Khan
 */
include ('databaseConstants.php');

session_start();
// Check if the keyword textbox was set OR if there's a user logged in
if (!isset($_POST['keyword']) || !isset($_SESSION['username'])) {
    die("");
}

// Prepare variables
$keyword = $_POST['keyword'];
$user = $_SESSION['username'];
$keyword = strip_tags($keyword);


// Store city name in history if it exists.
if (checkIfCityExists($keyword)) {
    // Check if user searched the city already.
    if(checkIfCityExistsInHistory($keyword, $user)){
        // Delete the older version of the city you searched and store the new one.
        deleteSearchHistorySpecified($keyword, $user);
        $status = storeKeywordAsHistory($keyword, $user);
    
        // Return the status indicating if it was successful or not
        echo $status;
    }else{
        $status = storeKeywordAsHistory($keyword, $user);
    
        // Return the status indicating if it was successful or not
        echo $status;
    }
} else {
    echo "Not a valid city.";
}

/**
 * Store the keyword in the database with the username.
 * @param String $keyword
 * @param String $user
 */
function storeKeywordAsHistory($keyword, $user) {
    try {
        //Add data to database
        $pdo = new PDO('mysql:host=' . HOST . ';dbname=' . DB_NAME, USER, PASSWORD);
        $query = 'INSERT INTO history (keyword, datesearched, username) '
                . 'VALUES (?, now(), ?);';
        $stmt = $pdo->prepare($query);

        $stmt->bindParam(1, $keyword);
        $stmt->bindParam(2, $user);
        $stmt->execute();

        return "Added \"$keyword\" to search history";
    } catch (PDOException $pdoe) {
        echo $pdoe->getMessage();
    } finally {
        unset($pdo);
    }
    return "Couldn't add keyword to search history. Try again later.";
}

/**
 * This will return the number of cities found. Per php rules, if it returns 0, 
 * it's false. Otherwise, it's true.
 * 
 * @param type $keyword
 */
function checkIfCityExists($keyword) {
    try {
        $pdo = new PDO('mysql:host=' . HOST . ';dbname=' . DB_NAME, USER, PASSWORD);
        $pdo->setAttribute(PDO::FETCH_NUM,PDO::ERRMODE_EXCEPTION);
        $query = "SELECT COUNT(city) FROM cities WHERE city = ?;";

        $stmt = $pdo->prepare($query);

        $stmt->bindParam(1, $keyword);
        $stmt->execute();

        // Check if there's a city or not
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
 * This will return the number of cities found in the history. Per php rules, 
 * if it returns 0, it's false. Otherwise, it's true.
 * 
 * @param String $keyword
 * @param String $user
 */
function checkIfCityExistsInHistory($keyword, $user){
    try {
        $pdo = new PDO('mysql:host=' . HOST . ';dbname=' . DB_NAME, USER, PASSWORD);
        $pdo->setAttribute(PDO::FETCH_NUM,PDO::ERRMODE_EXCEPTION);
        $query = "SELECT COUNT(keyword) FROM history WHERE username = ? AND "
                . "keyword = ?;";

        $stmt = $pdo->prepare($query);

        $stmt->bindParam(1, $user);
        $stmt->bindParam(2, $keyword);
        
        $stmt->execute();

        // Check if there's a city or not
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
 * Deletes the keyword specified for the specific user.
 * 
 * @param String $keyword
 * @param String $user
 */
function deleteSearchHistorySpecified($keyword, $user){
    try {
        $pdo = new PDO('mysql:host='.HOST.';dbname='.DB_NAME, USER, PASSWORD);
        $query = 'DELETE FROM history WHERE username = ? AND keyword = ?;';
        $stmt = $pdo->prepare($query);

        $stmt->bindParam(1, $user);
        $stmt->bindParam(2, $keyword);
        $stmt->execute();
    } catch (PDOException $pdoe) {
        echo $pdoe->getMessage();
    } finally {
        unset($pdo);
    }
}
