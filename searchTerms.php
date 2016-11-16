<?php

/**
 * This php file will be taking care of searching keywords through the database 
 * for past search terms
 * 
 * @author Shifat Khan
 */

/**
 * Returns the search terms from the user's history table
 * @param String keyword
 */
function searchHistoryKeywords() {
    $results = array();

    try {
        $pdo = new PDO('mysql:host=localhost;dbname=homestead', "homestead", "secret");
        $tableQuery = "SELECT keyword FROM history WHERE "
                . "username = ? ORDER BY datesearched DESC "
                . "LIMIT 5;";
        $stmt = $pdo->prepare($tableQuery);

        $stmt->bindParam(1, $_SESSION['username']);

        if ($stmt->execute()) {
            $results = $stmt->fetchAll(PDO::FETCH_COLUMN);
        }
    } catch (PDOException $pdoe) {
        echo $pdoe->getMessage();
    } finally {
        unset($pdo);
    }

    return $results;
}
