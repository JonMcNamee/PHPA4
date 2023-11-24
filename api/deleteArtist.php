<?php
require_once dirname(__DIR__, 1) . '/db/ConnectionManager.php';
require_once dirname(__DIR__, 1) . '/db/ArtistAccessor.php';
require_once dirname(__DIR__, 1) . '/entity/Artist.php';
require_once dirname(__DIR__, 1) . '/utils/Constants.php';

// passed as URL parameter
$id = intval($_GET['artistID']);

// create a dummy Artist object
$ArtistsObj = new Artists($id, "dummyCat", "dummyDesc", 1);

// delete from DB
try {
    $cm = new ConnectionManager(Constants::$MYSQL_CONNECTION_STRING, Constants::$MYSQL_USERNAME, Constants::$MYSQL_PASSWORD);
    $aa = new ArtistsAccessor($cm->getConnection());
    $success = $aa->deleteItem($ArtistsObj);
    echo $success ? 1 : 0;
} catch (Exception $e) {
    echo "ERROR " . $e->getMessage();
}
