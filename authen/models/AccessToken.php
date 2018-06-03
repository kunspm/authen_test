<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/models/BaseModel.php');

class AccessToken extends BaseModel
{
    protected $table = 'access_token';
    protected $conn;

    public function getTokenByUserId($userId)
    {
        $sql = "SELECT * from " . $this->table . " WHERE user_id = " . $userId;

        $result = mysqli_query($this->conn,$sql);
        if($result) $data = $result->fetch_object();
        else return false;

        if($data && $data->time_expire > time()) {
            mysqli_close($this->conn);
        }else{
            $data = $this->updateUserToken($userId);
        }
        return $data->token;

    }

    public function updateUserToken($userId){
        $newToken = $this->_createUniqueToken();
        $sql = "UPDATE " . $this->table . " SET token = '" . $newToken . "' AND time_expire = " . time() + 2592000 . " WHERE user_id = '" . $userId . "'"; // expire in 3 days
        if(mysqli_query($this->conn,$sql))
        return $this->getTokenByUserId($userId);

        else return false;


    }

    public function getByToken($token){
        $sql = "SELECT * from " . $this->table . " WHERE token = '" . $token . "'";

        $result = mysqli_query($this->conn,$sql);
        if($result) $data = $result->fetch_object();
        else return false;

        return $data;
    }

    private function _createUniqueToken()
    {
        return base64_encode('vni_token=' . microtime() . '+' . rand());
    }

}