<?php
    class Users extends Controller{
        public function __construct()
        {
            $this->userModel = $this->model('user');
        }
        public function register(){
            // Check for POST
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
              // Process form
        
              // Sanitize POST data
              $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
      
              // Init data
              $data =[
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => '',
                'token' => ''
              ];
      
              // Validate Email
              if(empty($data['email'])){
                $data['email_err'] = 'Please enter email';
              }
              else{
                  if ($this->userModel->findUserByEmail($data['email']))
                    $data['email_err'] = 'Please already used';
              }
      
              // Validate Name
              if(empty($data['name'])){
                $data['name_err'] = 'Please enter name';
              }
              else{
                if ($this->userModel->findUserByName($data['name']))
                  $data['name_err'] = 'Please already name';
            }
      
              // Validate Password
              if(empty($data['password'])){
                $data['password_err'] = 'Please enter password';
              } elseif(strlen($data['password']) < 6){
                $data['password_err'] = 'Password must be at least 6 characters';
              }
      
              // Validate Confirm Password
              if(empty($data['confirm_password'])){
                $data['confirm_password_err'] = 'Please confirm password';
              } else {
                if($data['password'] != $data['confirm_password']){
                  $data['confirm_password_err'] = 'Passwords do not match';
                }
              }
      
              // Make sure errors are empty
              if(empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])){
                // Validated
                {
                    $data['token'] = bin2hex(random_bytes(16));
                    //Hash Password
                    $data['password'] = password_hash($data['password'],PASSWORD_DEFAULT);
                    //register
                    if ($this->userModel->register($data))
                    {
                      flash('register_seccess','You are register');
                      header('location: ' . URLROOT . '/users/login');
                    }
                    else
                        die('somm');
                }
              } else {
                // Load view with errors
                $this->view('users/register', $data);
              }
      
            } else {
              // Init data
              $data =[
                'name' => '',
                'email' => '',
                'password' => '',
                'confirm_password' => '',
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
              ];
      
              // Load view
              $this->view('users/register', $data);
            }
          }
      
        public function login()
        {
            //Check for Post
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $data =[
                    'name' => trim($_POST['name']),
                    'password' => trim($_POST['password']),
                    'name_err' => '',
                    'password_err' => '',
                  ];
                if(empty($data['name'])){
                $data['name_err'] = 'Please enter name';
                }
        
                // Validate Password
                if(empty($data['password'])){
                $data['password_err'] = 'Please enter password';
                }

                //check for user
                if ($this->userModel->findUserByName($data['name'])){
                }else{
                  $data['name_err'] = 'user not found';
                }
                if(empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])){
                    // Validated
                    // Check and set logges in user
                    $loggedInUser = $this->userModel->login($data['name'],$data['password']);
                    if ($loggedInUser){
                      //Create session
                      $this->createUserSession($loggedInUser);
                    }
                    else{
                      $data['password_err'] = 'Password incorrect';
                      $this->view('/users/login',$data);
                    }
                  } else {
                    // Load view with errors
                    $this->view('users/login', $data);
                  }
            }
            else{
                //init Data
                $data = [
                    'name' => '',
                    'password' => '',
                    'name_err' => '',
                    'password_err' => '',
                ];
                //Load View
                $this->view('users/login',$data);
            }
        }
        public function createUserSession($user){
          $_SESSION['user_id'] = $user->id;
          $_SESSION['user_email'] = $user->email;
          $_session['name'] = $user->name;
          header('location: ' . URLROOT . '/pages/index');
        }
        public function logout(){
          unset($$_SESSION['user_id']);
          unset($$_SESSION['user_email']);
          unset($$_SESSION['user_name']);
          session_destroy();
          header('location: ' . URLROOT . '/users/login');
        }
        public function isloggedIn(){
           return (isset($_SESSION['user_id'])) ? true : false;
          }
    }