<?php
for ($pageNum = 1; $pageNum <= 100; $pageNum++) {
    scrape($pageNum);
}

function scrape($pageNum) {
    $url = "https://foodgawker.com/page/$pageNum";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $page = curl_exec($ch);
    sleep(1);
    curl_close($ch);

    $html = new DOMDocument();
    @$html->loadHTML($page);
    $xpath = new DOMXPath($html);
    
    //Look for what you need here
    $nodes = $xpath->query("//*[@class='flipwrapper']");
    foreach ($nodes as $value) {
        $recipename = $xpath->query(".//article/div/section[@class='picture']/header[@class='img-top']/h2", $value)->item(0)->nodeValue;
        $link = $xpath->query(".//article/div/section[@class='picture']/a[@class='picture-link']", $value)->item(0)->getAttribute('href');
        $description = $xpath->query(".//article/div/section[@class='description']", $value)->item(0)->nodeValue;
        $username = $xpath->query(".//article/div/section[@class='post-links']/a[@class='submitter']", $value)->item(0)->nodeValue;
        $gawked = $xpath->query(".//article/div/section[@class='post-data']/div[@class='gawked']", $value)->item(0)->nodeValue;
        
        
        //Add to database here
        addToDatabase($recipename, $link, $description, $username, $gawked);
    }//end foreach
    
    //Logging
    echo "scraped page $pageNum \n";
}

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
?>

