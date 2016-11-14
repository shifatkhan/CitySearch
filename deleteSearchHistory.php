<?php
/**
 * this php file will be deleting every search terms from the database based on 
 * the user
 * 
 * @author Shifat Khan
 */
session_start();
// Check if the keyword textbox was set OR if there's a user logged in
if (!isset($_SESSION['username'])) {
	die("");
}

$user = $_SESSION['username'];
$status = deleteSearchTerms($user);

// Return the status indicating if it was successful or not
echo $status;

/**
 * Delete search terms from the user
 * @param String $user
 */
function deleteSearchTerms($user) {
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=homestead', 'homestead', 'secret');
        $query = 'DELETE FROM history WHERE username = ?;';
        $stmt = $pdo->prepare($query);

        $stmt->bindParam(1, $user);
        $stmt->execute();
        
        return "Deleted search history.";
    } catch (PDOException $pdoe) {
        echo $pdoe->getMessage();
    } finally {
        unset($pdo);
    }
    return "Couldn't delete search history. Try again later.";
}