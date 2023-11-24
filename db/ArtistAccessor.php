<?php
require_once dirname(__DIR__, 1) . '/entity/Artist.php';

class ArtistsAccessor
{
    private $getAllStatementString = "select * from Artists";
    private $getByIDStatementString = "select * from Artists where artistID = :artistID";
    private $deleteStatementString = "delete from Artists where artistID = :artistID";
    private $insertStatementString = "insert INTO Artists values (:artistID, :artistName, :genre, :monthlySpotifyListeners)";
    private $updateStatementString = "update Artists set artistName = :artistName, genre = :genre, monthlySpotifyListeners = :monthlySpotifyListeners where artistID = :artistID";

    private $getAllStatement = null;
    private $getByIDStatement = null;
    private $deleteStatement = null;
    private $insertStatement = null;
    private $updateStatement = null;

    /**
     * Creates a new instance of the accessor with the supplied database connection.
     * 
     * @param PDO $conn - a database connection
     */
    public function __construct($conn)
    {
        if (is_null($conn)) {
            throw new Exception("no connection");
        }

        $this->getAllStatement = $conn->prepare($this->getAllStatementString);
        if (is_null($this->getAllStatement)) {
            throw new Exception("bad statement: '" . $this->getAllStatementString . "'");
        }

        $this->getByIDStatement = $conn->prepare($this->getByIDStatementString);
        if (is_null($this->getByIDStatement)) {
            throw new Exception("bad statement: '" . $this->getByIDStatementString . "'");
        }

        $this->deleteStatement = $conn->prepare($this->deleteStatementString);
        if (is_null($this->deleteStatement)) {
            throw new Exception("bad statement: '" . $this->deleteStatementString . "'");
        }

        $this->insertStatement = $conn->prepare($this->insertStatementString);
        if (is_null($this->insertStatement)) {
            throw new Exception("bad statement: '" . $this->getAllStatementString . "'");
        }

        $this->updateStatement = $conn->prepare($this->updateStatementString);
        if (is_null($this->updateStatement)) {
            throw new Exception("bad statement: '" . $this->updateStatementString . "'");
        }
    }

    /**
     * Gets all of the Artists.
     * 
     * @return Artists[] array of Artist objects
     */
    public function getAllItems()
    {
        $results = [];

        try {
            $this->getAllStatement->execute();
            $dbresults = $this->getAllStatement->fetchAll(PDO::FETCH_ASSOC);

            foreach ($dbresults as $r) {
                $artistID = $r['artistID'];
                $artistName = $r['artistName'];
                $genre = $r['genre'];
                $monthlySpotifyListeners = $r['monthlySpotifyListeners'];
                $obj = new Artists($artistID, $artistName, $genre, $monthlySpotifyListeners);
                array_push($results, $obj);
            }
        } catch (Exception $e) {
            $results = [];
        } finally {
            if (!is_null($this->getAllStatement)) {
                $this->getAllStatement->closeCursor();
            }
        }

        return $results;
    }

    /**
     * Gets the menu item with the specified ID.
     * 
     * @param Integer $id the ID of the item to retrieve 
     * @return Artists Artist object with the specified ID, or NULL if not found
     */
    private function getItemByID($id)
    {
        $result = null;

        try {
            $this->getByIDStatement->bindParam(":artistID", $id);
            $this->getByIDStatement->execute();
            $dbresults = $this->getByIDStatement->fetch(PDO::FETCH_ASSOC); // not fetchAll

            if ($dbresults) {
                $artistID = $dbresults['artistID'];
                $artistName = $dbresults['artistName'];
                $genre = $dbresults['genre'];
                $monthlySpotifyListeners = $dbresults['monthlySpotifyListeners'];
                $result = new Artists($artistID, $artistName, $genre, $monthlySpotifyListeners);
            }
        } catch (Exception $e) {
            $result = null;
        } finally {
            if (!is_null($this->getByIDStatement)) {
                $this->getByIDStatement->closeCursor();
            }
        }

        return $result;
    }

    /**
     * Does an item exist (with the same ID)?
     * 
     * @param Artists $item the item to check
     * @return boolean true if the item exists; false if not
     */
    public function itemExists($item)
    {
        return $this->getItemByID($item->getartistID()) !== null;
    }

    /**
     * Deletes a menu item.
     * 
     * @param Artists $item an object whose ID is EQUAL TO the ID of the item to delete
     * @return boolean indicates whether the item was deleted
     */
    public function deleteItem($item)
    {
        if (!$this->itemExists($item)) {
            return false;
        }

        $success = false;
        $artistID = $item->getartistID(); // only the ID is needed

        try {
            $this->deleteStatement->bindParam(":artistID", $artistID);
            $success = $this->deleteStatement->execute(); // this doesn't mean what you think it means
            $success = $success && $this->deleteStatement->rowCount() === 1;
        } catch (PDOException $e) {
            $success = false;
        } finally {
            if (!is_null($this->deleteStatement)) {
                $this->deleteStatement->closeCursor();
            }
        }
        return $success;
    }

    /**
     * Inserts a menu item into the database.
     * 
     * @param Artists $item an object of type Artists
     * @return boolean indicates if the item was inserted
     */
    public function insertItem($item)
    {
        if ($this->itemExists($item)) {
            return false;
        }

        $success = false;

        $artistID = $item->getartistID();
        $artistName = $item->getartistName();
        $genre = $item->getgenre();
        $monthlySpotifyListeners = $item->getmonthlySpotifyListeners();

        try {
            $this->insertStatement->bindParam(":artistID", $artistID);
            $this->insertStatement->bindParam(":artistName", $artistName);
            $this->insertStatement->bindParam(":genre", $genre);
            $this->insertStatement->bindParam(":monthlySpotifyListeners", $monthlySpotifyListeners);
            $success = $this->insertStatement->execute(); // this doesn't mean what you think it means
            $success = $this->insertStatement->rowCount() === 1;
        } catch (PDOException $e) {
            $success = false;
        } finally {
            if (!is_null($this->insertStatement)) {
                $this->insertStatement->closeCursor();
            }
        }
        return $success;
    }

    /**
     * Updates a menu item in the database.
     * 
     * @param Artists $item an object of type Artists, the new values to replace the database's current values
     * @return boolean indicates if the item was updated
     */
    public function updateItem($item)
    {
        if (!$this->itemExists($item)) {
            return false;
        }

        $success = false;

        $artistID = $item->getartistID();
        $artistName = $item->getartistName();
        $genre = $item->getgenre();
        $monthlySpotifyListeners = $item->getmonthlySpotifyListeners();
        try {
            $this->updateStatement->bindParam(":artistID", $artistID);
            $this->updateStatement->bindParam(":artistName", $artistName);
            $this->updateStatement->bindParam(":genre", $genre);
            $this->updateStatement->bindParam(":monthlySpotifyListeners", $monthlySpotifyListeners);
            $success = $this->updateStatement->execute(); // this doesn't mean what you think it means
            $success = $this->updateStatement->rowCount() === 1;
        } catch (PDOException $e) {
            $success = false;
        } finally {
            if (!is_null($this->updateStatement)) {
                $this->updateStatement->closeCursor();
            }
        }
        return $success;
    }
}
// end class ArtistsAccessor
