<?php
    class User{
        private $db;

        public function __construct(){
            $this->db = new Database;
        }
        //register

        public function register($data)
        {
            $this->db->query('INSERT INTO user (`username`, `email`,`password`,`token`) VALUES (:name, :email,:password,:token)');
            //bind Value
            $this->db->bind(':email',$data['email']);
            $this->db->bind(':name',$data['name']);
            $this->db->bind(':password',$data['password']);
            $this->db->bind(':token',$data['token']);

            //execute
            return ($this->db->execute()) ? true : false;
        }

        //login user
        public function login($name,$password){
            $this->db->query('SELECT * FROM user WHERE username = :name');
            $this->db->bind(':name' ,$name);
            $row = $this->db->single();
            $hash_password = $row->password;
            return (password_verify($password, $hash_password)) ? $row : false;
        }
        //Find user by email
        public function findUserByEmail($email)
        {
            $this->db->query('SELECT * FROM user WHERE email = :email');
            $this->db->bind(':email',$email);
            $row = $this->db->single();

            //check row
            return ($this->db->rowCount() > 0) ?  true : false;
        }
        public function findUserByName($name)
        {
            $this->db->query('SELECT * FROM user WHERE username = :name');
            $this->db->bind(':name',$name);
            $row = $this->db->single();

            //check row
            return ($this->db->rowCount() > 0) ?  true : false;
        }
    }