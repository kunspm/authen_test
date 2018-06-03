<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/models/BaseModel.php');
class Users extends BaseModel {
	protected $table = 'users';
    protected $conn;

	public function readById($id){
        $sql = "SELECT id,name,phone,address,email from " . $this->table . " WHERE id = " . $id;

        $result = mysqli_query($this->conn,$sql);
        if($result) $data = $result->fetch_object();
        else return false;

        mysqli_close($this->conn);

        return $data;
	}

    public function checkLogin($email,$password){
        $sql = "SELECT * from " . $this->table . " WHERE email = '" . $email . "' AND password = '" . $password . "'";
        $result = mysqli_query($this->conn,$sql);

        if($result) $data = $result->fetch_object();
        else return false;

        mysqli_close($this->conn);
        return $data;
    }

	public function updateById($id,$data){
        $sql =  "Update " . $this->table . " SET name = '" . $data['name'] . "',address = '" . $data['address'] . "',phone = '" . $data['phone'] . "'  WHERE id = " . $id;

        $result = mysqli_query($this->conn,$sql);
        mysqli_close($this->conn);

        if ($result) return true;
        else return false;
    }


}