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
