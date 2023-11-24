<?php
require_once dirname(__DIR__, 1) . '/db/ConnectionManager.php';
require_once dirname(__DIR__, 1) . '/db/ArtistAccessor.php';
require_once dirname(__DIR__, 1) . '/utils/Constants.php';

try {
    $cm = new ConnectionManager(Constants::$MYSQL_CONNECTION_STRING, Constants::$MYSQL_USERNAME, Constants::$MYSQL_PASSWORD);
    $aa = new ArtistsAccessor($cm->getConnection());
    $results = $aa->getAllItems(); // these are Artist objects
    $results = json_encode($results, JSON_NUMERIC_CHECK);
    echo $results;
} catch (Exception $e) {
    echo "ERROR " . $e->getMessage();
}
