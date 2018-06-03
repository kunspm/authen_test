<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/models/AccessToken.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/models/Users.php');

class UserApi
{
    protected $status_code = 200;
	protected $status = false;
	protected $message = '';

	protected function responseData($data = []){
        header('Content-type: application/json;charset=UTF-8');
		$response = [];
		$response['status_code'] = $this->status_code;
		$response['status'] = $this->status;
		$response['message'] = $this->message;
		$response['data'] = $data;

		echo json_encode($response);
		exit();
	}

	public function postLogin(){
        $email = isset($_POST['email']) ? $_POST['email'] : '';
		$password = isset($_POST['password']) ? $_POST['password'] : '';

		if(!$email || !$password){
		    $this->status_code = 1001;
			$this->message = 'You have no permission';
            $this->responseData();
		} 

        $hashPassword = hash('sha256',$password);

        $user = new Users;
        $userInfo = $user->checkLogin($email,$hashPassword);

        if(!$userInfo){
            $this->status_code = 1002;
            $this->message = 'Please check your email and password';
            $this->responseData();
        }

        $accessToken = new AccessToken();
        $userToken = $accessToken->getTokenByUserId($userInfo->id);

        if($userToken){
            $this->status = true;
            $this->message = 'success';
            $data['userToken'] = $userToken;
        }

        $this->responseData($data);

	}

    public function getUserInfo(){
        $token = isset($_POST['token']) ? $_POST['token'] : '';
        // Check token co the tao base API de o ham contruct.
        if(!$token){
            $this->status_code = 1001;
            $this->message = 'You have no permission';
            $this->responseData();
        }

        $accessToken = new AccessToken();
        $userToken = $accessToken->getByToken($token);

        if(!$userToken){
            $this->status_code = 1002;
            $this->message = 'Wrong AccessToken';
            $this->responseData();
        }

        if($userToken->time_expire < time()){
            $this->status_code = 1003;
            $this->message = 'Token expired, please login again';
        }

        $user = new Users();
        $userInfo = $user->readById($userToken->user_id);

        $this->status = true;
        $this->message = 'success';
        $this->responseData($userInfo);

    }

    public function updateUserInfo(){
	    $userData = [];

        $token = isset($_POST['token']) ? $_POST['token'] : '';
        $userData['name']  = isset($_POST['name']) ? $_POST['name'] : '';
        $userData['address']  = isset($_POST['address']) ? $_POST['address'] : '';
        $userData['phone']  = isset($_POST['phone']) ? $_POST['phone'] : '';

        if(!$token){
            $this->message = 'You have no permission';
            $this->responseData();
        }


        // TODO
        // Validate data gui len
        // if (validate)

        $accessToken = new AccessToken();
        $userToken = $accessToken->getByToken($token);

        if(!$userToken){
            $this->status_code = 1002;
            $this->message = 'Wrong AccessToken';
            $this->responseData();
        }

        if($userToken->time_expire < time()){
            $this->status_code = 1003;
            $this->message = 'Token expired, please login again';
        }

        $user = new Users();
        $userInfo = $user->readById($userToken->user_id);

        $updateUser = new Users();
        $updateUser->updateById($userInfo->id,$userData);

        $this->status = true;
        $this->message = 'success';
        $this->responseData();

    }

}

//    $test = new UserApi();
//    $test->updateUserInfo();

