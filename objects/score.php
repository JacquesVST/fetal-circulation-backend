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
    public $email;
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
        $query = "SELECT s.id, s.name, s.device, s.email, s.score, s.id_level, s.created FROM " . $this->table_name . " s ORDER BY s.score DESC";

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
        $query = "INSERT INTO " . $this->table_name . " SET name=:name, device=:device, email=:email, score=:score, id_level=:id_level, created=:created";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->device = htmlspecialchars(strip_tags($this->device));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->score = htmlspecialchars(strip_tags($this->score));
        $this->id_level = htmlspecialchars(strip_tags($this->id_level));
        $this->created = htmlspecialchars(strip_tags($this->created));

        // bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":device", $this->device);
        $stmt->bindParam(":email", $this->email);
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
        $query = "SELECT s.id, s.name, s.device, s.email, s.score, s.id_level, s.created FROM " . $this->table_name . " s WHERE s.id = ? ORDER BY s.score DESC";

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
        $this->email = $row['email'];
        $this->score = $row['score'];
        $this->id_level = $row['id_level'];
        $this->created = $row['created'];
    }

    // read scores by Email
    function readAllEmail()
    {

        // query to read single record
        $query = "SELECT s.id, s.name, s.device, s.email, s.score, s.id_level, s.created FROM " . $this->table_name . " s WHERE s.email = ?";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind id of score to be listed
        $stmt->bindParam(1, $this->email);

        // execute query
        $stmt->execute();

        return $stmt;
    }

    // read scores, one by Email
    function readAllUnique()
    {

        // select all query
        $query = "SELECT s.id, s.name, s.device, s.email, s.score, s.id_level, s.created FROM " . $this->table_name . " s ORDER BY s.score DESC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        return $stmt;
    }
}
