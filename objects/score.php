<?php
class Score
{

    // database connection and table name
    private $conn;
    private $table_name = "scores";

    // object properties
    public $id;
    public $name;
    public $device;
    public $score;
    public $id_level;
    public $created;

    // constructor with $db as database connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // read scores
    function readAll()
    {

        // select all query
        $query = "SELECT s.id, s.name, s.device, s.score, s.id_level, s.created FROM " . $this->table_name . " s ORDER BY s.score DESC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        return $stmt;
    }

    // create score
    function create()
    {

        // query to insert record
        $query = "INSERT INTO " . $this->table_name . " SET name=:name, device=:device, score=:score, id_level=:id_level, created=:created";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->device = htmlspecialchars(strip_tags($this->device));
        $this->score = htmlspecialchars(strip_tags($this->score));
        $this->id_level = htmlspecialchars(strip_tags($this->id_level));
        $this->created = htmlspecialchars(strip_tags($this->created));

        // bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":device", $this->device);
        $stmt->bindParam(":score", $this->score);
        $stmt->bindParam(":id_level", $this->id_level);
        $stmt->bindParam(":created", $this->created);

        // execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // read one score by ID
    function readOne()
    {

        // query to read single record
        $query = "SELECT s.id, s.name, s.device, s.score, s.id_level, s.created FROM " . $this->table_name . " s WHERE s.device = ? ORDER BY s.score DESC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind id of score to be listed
        $stmt->bindParam(1, $this->id);

        // execute query
        $stmt->execute();

        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set values to object properties
        $this->name = $row['name'];
        $this->device = $row['device'];
        $this->score = $row['score'];
        $this->id_level = $row['id_level'];
        $this->created = $row['created'];
    }

    // read scores by Device
    function readAllDevice()
    {

        // query to read single record
        $query = "SELECT s.id, s.name, s.device, s.score, s.id_level, s.created FROM " . $this->table_name . " s WHERE s.device = ?";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind id of score to be listed
        $stmt->bindParam(1, $this->device);

        // execute query
        $stmt->execute();

        return $stmt;
    }

    // read scores, one by Device
    function readAllUnique()
    {

        // select all query
        $query = "SELECT s.id, s.name, s.device, s.score, s.id_level, s.created FROM " . $this->table_name . " s ORDER BY s.score DESC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        return $stmt;
    }
}
