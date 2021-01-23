<?php
    class Users extends Controller{
        public function __construct()
        {
            
        }
        public function register()
        {
            //Check for Post
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
            }
            else{
                //init Data
                $data = [
                    'name' => '',
                    'email' => '',
                    'password' => '',
                    'password' => '',
                    'confirm_password' => '',
                    'name_err' => '',
                    'email_err' => '',
                    'password_err' => '',
                    'confirm_password_err' => ''
                ];
                //Load View
                $this->view('users/register',$data);
            }
        }
    }