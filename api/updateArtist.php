<?php
require_once dirname(__DIR__, 1) . '/db/ConnectionManager.php';
require_once dirname(__DIR__, 1) . '/db/ArtistAccessor.php';
require_once dirname(__DIR__, 1) . '/entity/Artist.php';
require_once dirname(__DIR__, 1) . '/utils/Constants.php';

// reading the HTTP request body
$body = file_get_contents('php://input');
$contents = json_decode($body, true);

$artistID = $contents['artistID'];
$artistName = $contents['artistName'];
$genre = $contents['genre'];
$monthlySpotifyListeners = $contents['monthlySpotifyListeners'];

// create an Artist object
$ArtistsObj = new Artists($artistID, $artistName, $genre, $monthlySpotifyListeners);

// update the DB
try {
    $cm = new ConnectionManager(Constants::$MYSQL_CONNECTION_STRING, Constants::$MYSQL_USERNAME, Constants::$MYSQL_PASSWORD);
    $aa = new ArtistsAccessor($cm->getConnection());
    $success = $aa->updateItem($ArtistsObj);
    echo $success ? 1 : 0;
} catch (Exception $e) {
    echo "ERROR " . $e->getMessage();
}
