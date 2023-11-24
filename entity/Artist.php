<?php

class Artists implements JsonSerializable
{
    private $artistID;
    private $artistName;
    private $genre;
    private $monthlySpotifyListeners;

    public function __construct($artistID, $artistName, $genre, $monthlySpotifyListeners)
    {
        $this->artistID = $artistID;
        $this->artistName = $artistName;
        $this->genre = $genre;
        $this->monthlySpotifyListeners = $monthlySpotifyListeners;
    }

    public function getartistID()
    {
        return $this->artistID;
    }

    public function getartistName()
    {
        return $this->artistName;
    }

    public function getgenre()
    {
        return $this->genre;
    }

    public function getmonthlySpotifyListeners()
    {
        return $this->monthlySpotifyListeners;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
// end class Artists