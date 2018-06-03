<?php
class BaseModel{
    protected $host = 'localhost';
    protected $user = 'root';
    protected $password = 'root';
    protected $database = 'authen';


    public function __construct(){

        $this->conn = mysqli_connect($this->host,$this->user,$this->password,$this->database);

        if (!$this->conn) {
            die("Cannot Connect To Database " . mysqli_connect_error());
        }
    }
}