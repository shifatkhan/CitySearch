<?php
/**
 * this php file will be storing the keyword from the client to the database 
 * as a search history.
 * 
 * @author Shifat Khan
 */
session_start();
// Check if the keyword textbox was set OR if there's a user logged in
if (!isset($_POST['keyword']) || !isset($_SESSION['username'])) {
	die("");
}

$keyword = $_POST['keyword'];
$user = $_SESSION['username'];
$keyword = strip_tags($keyword);
$status = storeKeywordAsHistory($keyword, $user);

// Return the status indicating if it was successful or not
echo $status;

/**
 * Store the keyword in the database with the username.
 * @param String $keyword
 * @param String $user
 */
function storeKeywordAsHistory($keyword, $user) {
    try {
        //Add data to database
        $pdo = new PDO('mysql:host=localhost;dbname=homestead', 'homestead', 'secret');
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

