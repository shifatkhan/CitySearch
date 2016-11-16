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
        $query = "SELECT city, country FROM cities WHERE city LIKE ? LIMIT 5;";
        $stmt = $pdo->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'City');
        
        $stmt->bindValue(1, $keyword.'%');
        
        if($stmt->execute()){
            $results = $stmt->fetchAll();
        }
    } catch (PDOException $pdoe) {
        echo $pdoe->getMessage();
    } finally {
        unset($pdo);
    }
    
    return $results;
}
