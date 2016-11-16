<?php
/**
 * This php file will be taking care of searching keywords through the database 
 * for autocomplete
 * 
 * @author Shifat Khan
 */

include ('databaseConstants.php');

session_start();
session_regenerate_id();

// Check if the keyword textbox was set
if (!isset($_GET['keyword'])) {
	die("");
}

$keyword = $_GET['keyword'];
$keyword = strip_tags($keyword);

$data = autocompleteKeyword($keyword);

// To facilitate the output, we return the array as json_encode
echo json_encode($data, JSON_HEX_APOS);

/**
 * Search through the database and return matching values for the first few
 * letters
 * @param String $keyword
 */
function autocompleteKeyword($keyword) {
    $results = array();
    try {
        $pdo = new PDO('mysql:host='.HOST.';dbname='.DB_NAME, USER, PASSWORD);
        $tableQuery = "SELECT city FROM cities WHERE city LIKE ? LIMIT 5;";
        
        $stmt = $pdo->prepare($tableQuery);
        
        $stmt->bindValue(1, $keyword.'%');
        
        if($stmt->execute()){
            $results = $stmt->fetchAll(PDO::FETCH_COLUMN);
        }
    } catch (PDOException $pdoe) {
        echo $pdoe->getMessage();
    } finally {
        unset($pdo);
    }
    
    return $results;
}

/**
 * Returns the search terms from the user's history table
 * @param String keyword
 */
function searchHistoryKeywords($keyword){
    $results = array();
    try {
        $pdo = new PDO('mysql:host='.HOST.';dbname='.DB_NAME, USER, PASSWORD);
        $tableQuery = "SELECT keyword FROM history WHERE keyword LIKE ? AND "
                . "username = ? ORDER BY datesearched DESC "
                . "LIMIT 5;";
        $stmt = $pdo->prepare($tableQuery);
        
        $stmt->bindValue(1, $keyword.'%');
        $stmt->bindParam(2, $_SESSION['username']);
        
        if($stmt->execute()){
            $results = $stmt->fetchAll(PDO::FETCH_COLUMN);
        }
    } catch (PDOException $pdoe) {
        echo $pdoe->getMessage();
    } finally {
        unset($pdo);
    }
    
    return $results;
}

/**
 * Counts how many search terms there is.
 */
function countHistory(){
    try {
        $pdo = new PDO('mysql:host='.HOST.';dbname='.DB_NAME, USER, PASSWORD);
        $tableQuery = "SELECT COUNT(keyword) FROM history WHERE username = ?;";
        $stmt = $pdo->prepare($tableQuery);
        
        $stmt->bindParam(1, $_SESSION['username']);
        
        // Check if there's past history
        if ($row = $stmt->fetch()) {
            return $row[0];
        }else {
            echo "nohistory1-".$_SESSION['username']."-".$row[0]."\n";
            return 0;
        }
    } catch (PDOException $pdoe) {
        echo $pdoe->getMessage();
    } finally {
        unset($pdo);
    }
    return 0;
}
