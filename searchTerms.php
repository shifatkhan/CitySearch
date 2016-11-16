<?php

/**
 * This php file will be taking care of searching keywords through the database
 * for past search terms
 *
 * @author Shifat Khan
 */

/**
 * Displays the search history keywords
 * @param String keyword
 */
function searchHistoryKeywords() {
    $user = $_SESSION['username'];
    echo '<p id="searchTitle">Search History</p>';
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=homestead', 'homestead', 'secret');
        $tableQuery = "SELECT keyword FROM history WHERE "
                . "username = ? ORDER BY datesearched DESC "
                . "LIMIT 5;";
        $stmt = $pdo->prepare($tableQuery);

        $stmt->bindParam(1, $user);

        if ($stmt->execute()){
            while($row = $stmt->fetch()){
                echo '<div class="search">'. $row['keyword'] .'</div>';
            }
        }else{
            echo 'didnt run';
        }
    } catch (PDOException $pdoe) {
        echo $pdoe->getMessage();
    } finally {
        unset($pdo);
    }
}
