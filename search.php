<?php
/**
 * This php file will be taking care of searching keywords through the database 
 * for autocomplete
 * 
 * @author Shifat Khan
 */

// Check if the keyword textbox was set
if (!isset($_GET['keyword'])) {
	die("");
}

$keyword = $_GET['keyword'];

/*
$data = array();
if(countHistory() > 0){
    $data = searchHistoryKeywords();
    array_push($data, autocompleteKeyword($keyword));
}else{
    $data = autocompleteKeyword($keyword);
}*/
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
        $pdo = new PDO('mysql:host=localhost;dbname=homestead', "homestead", "secret");
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
 */
function searchHistoryKeywords(){
    $results = array();
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=homestead', "homestead", "secret");
        $tableQuery = "SELECT keyword FROM history WHERE username = ? "
                . "ORDER BY datesearched DESC "
                . "LIMIT 5;";
        
        $stmt = $pdo->prepare($tableQuery);
        
        $stmt->bindValue(1, $_SESSION['username']);
        
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
 * Counts how many search terms there is
 */
function countHistory(){
    $results = array();
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=homestead', "homestead", "secret");
        $tableQuery = "SELECT COUNT(*) from history WHERE username = ? ;";
        $stmt = $pdo->prepare($tableQuery);
        
        $stmt->bindValue(1, $_SESSION['username']);
        
        // Check if there's past history
        if ($row = $stmt->fetch()) {
            return $row[0];
        }else {
            return 0;
        }
    } catch (PDOException $pdoe) {
        echo $pdoe->getMessage();
    } finally {
        unset($pdo);
    }
    
    return 0;
}
